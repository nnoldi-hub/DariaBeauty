<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Gallery;
use App\Models\Review;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SpecialistController extends Controller
{
    /**
     * Dashboard specialist - overview
     */
    public function dashboard()
    {
        $specialist = Auth::user();
        
        // Statistici dashboard
        $stats = [
            'total_appointments' => $specialist->appointments()->count(),
            'today_appointments' => $specialist->appointments()->today()->count(),
            'upcoming_appointments' => $specialist->appointments()->upcoming()->count(),
            'completed_appointments' => $specialist->appointments()->completed()->count(),
            'total_reviews' => $specialist->reviews()->approved()->count(),
            'average_rating' => $specialist->reviews()->approved()->avg('rating') ?? 0,
            'total_services' => $specialist->services()->active()->count(),
            'gallery_images' => $specialist->gallery()->count()
        ];

        // Programari recente
        $recentAppointments = $specialist->appointments()
                                       ->with(['service', 'review'])
                                       ->latest()
                                       ->limit(5)
                                       ->get();

        // Reviews recente
        $recentReviews = $specialist->reviews()
                                  ->approved()
                                  ->with('appointment')
                                  ->latest()
                                  ->limit(3)
                                  ->get();

        // Venituri luna aceasta (estimare)
        $thisMonthEarnings = $specialist->appointments()
                                      ->completed()
                                      ->whereMonth('appointment_date', now()->month)
                                      ->whereYear('appointment_date', now()->year)
                                      ->sum('total_amount');

        return view('specialist.dashboard', compact('specialist', 'stats', 'recentAppointments', 'recentReviews', 'thisMonthEarnings'));
    }

    /**
     * Afiseaza toate specialistii (pagina publica)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'specialist')
                    ->where('is_active', true)
                    ->with(['services', 'gallery', 'reviews'])
                    ->withCount(['reviews as reviews_count'])
                    ->withAvg('reviews as average_rating', 'rating');

        // Filtrare dupa sub-brand
        if ($request->filled('sub_brand')) {
            $query->where('sub_brand', $request->sub_brand);
        }

        // Filtrare dupa zona
        if ($request->filled('zone')) {
            $query->whereJsonContains('coverage_area', ['zone' => $request->zone]);
        }

        // Filtrare dupa rating minim
        if ($request->filled('min_rating')) {
            $query->having('average_rating', '>=', $request->min_rating);
        }

        // Sortare
        switch ($request->get('sort', 'rating')) {
            case 'rating':
                $query->orderByDesc('average_rating');
                break;
            case 'reviews':
                $query->orderByDesc('reviews_count');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->latest();
                break;
        }

        $specialists = $query->paginate(12);

        // Sub-branduri pentru filtru
        $subBrands = [
            'dariaNails' => 'dariaNails - Manichiura & Pedichiura',
            'dariaHair' => 'dariaHair - Coafura & Styling',
            'dariaGlow' => 'dariaGlow - Skincare & Makeup'
        ];

        return view('specialists.index', compact('specialists', 'subBrands'));
    }

    /**
     * Profil public specialist
     */
    public function show($slug)
    {
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->where('slug', $slug)
                         ->with(['services.appointments', 'gallery', 'reviews.appointment', 'socialLinks'])
                         ->withCount(['reviews as reviews_count'])
                         ->withAvg('reviews as average_rating', 'rating')
                         ->firstOrFail();

        // Services grupate pe categorii
        $servicesByCategory = $specialist->services()
                                       ->active()
                                       ->get()
                                       ->groupBy('category');

        // Gallery grupat pe before/after
        $gallery = [
            'featured' => $specialist->gallery()->featured()->limit(8)->get(),
            'before_after' => $specialist->gallery()->beforeAfter()->limit(6)->get(),
            'all' => $specialist->gallery()->limit(20)->get()
        ];

        // Reviews cu paginare
        $reviews = $specialist->reviews()
                            ->approved()
                            ->with('appointment')
                            ->latest()
                            ->paginate(10);

        // Servicii active
        $services = $specialist->services()->active()->get();

        // Statistici specialist
        $stats = [
            'completed_appointments' => $specialist->appointments()->completed()->count(),
            'years_experience' => $specialist->created_at->diffInYears(now()),
            'coverage_zones' => count($specialist->coverage_area ?? []),
            'response_time' => '< 2 ore' // Simulat
        ];

        return view('specialists.show', compact('specialist', 'servicesByCategory', 'gallery', 'reviews', 'stats', 'services'));
    }

    /**
     * Formular de booking pentru specialist
     */
    public function booking($slug, Request $request)
    {
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->where('slug', $slug)
                         ->with('services')
                         ->firstOrFail();

        $services = $specialist->services()->active()->get();
        $selectedService = null;

        if ($request->filled('service_id')) {
            $selectedService = $services->where('id', $request->service_id)->first();
        }

        // Zone de acoperire
        $coverageAreas = $specialist->coverage_area ?? [];

        return view('specialists.booking', compact('specialist', 'services', 'selectedService', 'coverageAreas'));
    }

    /**
     * Proceseaza rezervarea
     */
    public function storeBooking($slug, Request $request)
    {
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->where('slug', $slug)
                         ->firstOrFail();

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'service_location' => 'required|in:salon,home',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'date' => 'required|date|after:today',
            'time' => 'required',
            'address' => 'required_if:service_location,home|nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        // ========== VERIFICARE DISPONIBILITATE ==========
        // Verificăm dacă ora solicitată nu se suprapune cu programările existente
        $requestedDate = \Carbon\Carbon::parse($request->date);
        $requestedTime = \Carbon\Carbon::parse($request->time);
        $requestedStart = $requestedDate->copy()->setTimeFrom($requestedTime);
        $requestedEnd = $requestedStart->copy()->addMinutes($service->duration);
        
        // Obține toate programările active pentru acest specialist în ziua respectivă
        $existingAppointments = Appointment::where('specialist_id', $specialist->id)
            ->whereDate('appointment_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('service')
            ->get();
        
        // Verifică suprapunerea
        $isOverlapping = false;
        foreach ($existingAppointments as $existing) {
            $existingStart = \Carbon\Carbon::parse($existing->appointment_date->format('Y-m-d') . ' ' . $existing->appointment_time);
            // Folosește durata din programare sau durata serviciului ca fallback
            $duration = $existing->duration ?? ($existing->service->duration ?? 60);
            $existingEnd = $existingStart->copy()->addMinutes($duration);
            
            // Verifică dacă intervalele se suprapun
            if ($requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart)) {
                $isOverlapping = true;
                break;
            }
        }
        
        if ($isOverlapping) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['time' => 'Această oră nu mai este disponibilă. Specialistul are deja o programare în intervalul selectat. Vă rugăm alegeți altă oră.']);
        }
        // ========== SFÂRȘIT VERIFICARE DISPONIBILITATE ==========
        
        $isHomeService = $request->service_location === 'home';
        
        // Calculează totalul cu taxe
        $totalAmount = $service->price;
        if ($isHomeService) {
            // Adaugă taxa de serviciu la domiciliu (dacă există)
            if ($service->home_service_fee > 0) {
                $totalAmount += $service->home_service_fee;
            }
            // Adaugă taxa de transport a specialistului
            if ($specialist->transport_fee > 0) {
                $totalAmount += $specialist->transport_fee;
            }
        }

        // Creaza programarea
        $appointment = Appointment::create([
            'specialist_id' => $specialist->id,
            'service_id' => $service->id,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'appointment_date' => $request->date,
            'appointment_time' => $request->time,
            'duration' => $service->duration, // Salvăm durata serviciului pentru calcul disponibilitate
            'client_address' => $isHomeService ? $request->address : $specialist->salon_address,
            'notes' => $request->notes,
            'is_home_service' => $isHomeService,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

        // Incarca relatia service pentru notificare
        $appointment->load('service');

        // Trimite notificare SMS catre specialist
        \Log::info("=== BOOKING CREATED - SENDING SMS TO SPECIALIST ===", [
            'appointment_id' => $appointment->id,
            'specialist_id' => $specialist->id,
            'specialist_phone' => $specialist->phone,
            'client_name' => $appointment->client_name
        ]);

        try {
            $smsService = app(\App\Services\SmsService::class);
            $result = $smsService->notifySpecialistNewAppointment($appointment, $specialist);
            \Log::info("SMS to specialist result: " . ($result ? 'SUCCESS' : 'FAILED'));
        } catch (\Exception $e) {
            \Log::error("Failed to send SMS to specialist", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $locationText = $isHomeService ? 'la domiciliu' : 'la salon';
        return redirect()->route('specialists.show', $specialist->slug)
                        ->with('success', "Programarea {$locationText} a fost trimisă cu succes! Vei primi confirmare pe email/telefon în curând.");
    }

    /**
     * Gestionare servicii specialist
     */
    public function services()
    {
        $specialist = Auth::user();
        $services = $specialist->services()->with('appointments')->get();

        return view('specialist.services.index', compact('services'));
    }

    /**
     * Adauga serviciu nou
     */
    public function createService()
    {
        $specialist = Auth::user();
        
        $categories = [
            'Tratamente de baza',
            'Servicii premium', 
            'Pachete speciale',
            'Servicii sezoniere',
            'Evenimente speciale'
        ];

        $equipment = [
            'Kit profesional mobil',
            'Sterilizator UV',
            'Lampa LED/UV',
            'Produse premium',
            'Instrumentar sterilizat',
            'Materiale consumabile'
        ];

        return view('specialist.services.create', compact('categories', 'equipment'));
    }

    /**
     * Salveaza serviciu nou
     */
    public function storeService(Request $request)
    {
        $specialist = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'category' => 'required|string',
            'preparation_time' => 'nullable|integer|min:0',
            'equipment_needed' => 'nullable|array',
            'available_at_salon' => 'nullable|boolean',
            'available_at_home' => 'nullable|boolean',
            'home_service_fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = $specialist->id;
        $data['sub_brand'] = $specialist->sub_brand ?? 'dariaNails'; // Default la dariaNails dacă nu e setat
        $data['is_mobile'] = true; // Toate serviciile DariaBeauty sunt mobile
        
        // Setă boolean-urile pentru locație
        $data['available_at_salon'] = $request->has('available_at_salon');
        $data['available_at_home'] = $request->has('available_at_home');
        $data['home_service_fee'] = $request->input('home_service_fee', 0);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image');
            $imageName = time() . '_' . $imagePath->getClientOriginalName();
            $imagePath->move(public_path('storage/services'), $imageName);
            $data['image'] = 'services/' . $imageName;
        }

        Service::create($data);

        return redirect()->route('specialist.services.index')->with('success', 'Serviciul a fost adaugat cu succes!');
    }

    /**
     * Editeaza serviciu
     */
    public function editService($service_id)
    {
        $specialist = Auth::user();
        
        // Gaseste serviciul DOAR daca apartine specialistului curent
        $service = Service::where('id', $service_id)
                         ->where('user_id', $specialist->id)
                         ->firstOrFail();

        $categories = [
            'Tratamente de baza',
            'Servicii premium',
            'Pachete speciale',
            'Servicii sezoniere',
            'Evenimente speciale'
        ];

        $equipment = [
            'Kit profesional mobil',
            'Sterilizator UV',
            'Lampa LED/UV',
            'Produse premium',
            'Instrumentar sterilizat',
            'Materiale consumabile'
        ];

        return view('specialist.services.edit', compact('service', 'categories', 'equipment'));
    }

    /**
     * Actualizeaza serviciu
     */
    public function updateService(Request $request, $service_id)
    {
        $specialist = Auth::user();
        
        // Gaseste serviciul DOAR daca apartine specialistului curent
        $service = Service::where('id', $service_id)
                         ->where('user_id', $specialist->id)
                         ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'category' => 'required|string',
            'preparation_time' => 'nullable|integer|min:0',
            'equipment_needed' => 'nullable|array',
            'available_at_salon' => 'nullable|boolean',
            'available_at_home' => 'nullable|boolean',
            'home_service_fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except(['image']);
        
        // Setă boolean-urile pentru locație
        $data['available_at_salon'] = $request->has('available_at_salon');
        $data['available_at_home'] = $request->has('available_at_home');
        $data['home_service_fee'] = $request->input('home_service_fee', 0);

        if ($request->hasFile('image')) {
            // Sterge imaginea veche
            if ($service->image) {
                $oldImagePath = public_path('storage/' . $service->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $imagePath = $request->file('image');
            $imageName = time() . '_' . $imagePath->getClientOriginalName();
            $imagePath->move(public_path('storage/services'), $imageName);
            $data['image'] = 'services/' . $imageName;
        }

        $service->update($data);

        return redirect()->route('specialist.services.index')->with('success', 'Serviciul a fost actualizat cu succes!');
    }

    /**
     * Sterge serviciu
     */
    public function destroyService($service_id)
    {
        $specialist = Auth::user();
        
        // Gaseste serviciul DOAR daca apartine specialistului curent
        $service = Service::where('id', $service_id)
                         ->where('user_id', $specialist->id)
                         ->firstOrFail();

        // Sterge imaginea
        if ($service->image) {
            $imagePath = public_path('storage/' . $service->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $service->delete();

        return redirect()->route('specialist.services.index')->with('success', 'Serviciul a fost sters cu succes!');
    }

    /**
     * Gestionare galerie specialist
     */
    public function gallery()
    {
        $specialist = Auth::user();
        $gallery = $specialist->gallery()->with('service')->latest()->paginate(20);
        $services = $specialist->services()->active()->get();

        return view('specialist.gallery.index', compact('gallery', 'services'));
    }

    /**
     * Upload imagine in galerie
     */
    public function storeGalleryImage(Request $request)
    {
        $specialist = Auth::user();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'caption' => 'nullable|string|max:255',
            'before_after' => 'required|in:before,after,single',
            'service_id' => 'nullable|exists:services,id',
            'tags' => 'nullable|string',
            'is_featured' => 'nullable|boolean'
        ]);

        $imageFile = $request->file('image');
        $imageName = time() . '_' . $imageFile->getClientOriginalName();
        $imageFile->move(public_path('storage/gallery'), $imageName);
        $imagePath = 'gallery/' . $imageName;

        $tags = $request->tags ? explode(',', $request->tags) : [];

        Gallery::create([
            'user_id' => $specialist->id,
            'image_path' => $imagePath,
            'caption' => $request->caption,
            'sub_brand' => $specialist->sub_brand,
            'before_after' => $request->before_after,
            'service_id' => $request->service_id,
            'tags' => $tags,
            'is_featured' => $request->is_featured ?? false
        ]);

        return redirect()->back()->with('success', 'Imaginea a fost adaugata in galerie!');
    }

    /**
     * Actualizeaza imagine din galerie
     */
    public function updateGalleryImage(Request $request, $gallery_id)
    {
        $specialist = Auth::user();

        // Gaseste imaginea DOAR daca apartine specialistului curent
        $image = Gallery::where('id', $gallery_id)
                       ->where('user_id', $specialist->id)
                       ->firstOrFail();

        $request->validate([
            'caption' => 'nullable|string|max:255',
            'before_after' => 'required|in:before,after,single',
            'service_id' => 'nullable|exists:services,id',
            'is_featured' => 'nullable|boolean'
        ]);

        $image->update([
            'caption' => $request->caption,
            'before_after' => $request->before_after,
            'service_id' => $request->service_id,
            'is_featured' => $request->has('is_featured') ? true : false
        ]);

        return redirect()->back()->with('success', 'Imaginea a fost actualizata!');
    }

    /**
     * Sterge imagine din galerie
     */
    public function destroyGalleryImage($gallery_id)
    {
        $specialist = Auth::user();

        // Gaseste imaginea DOAR daca apartine specialistului curent
        $image = Gallery::where('id', $gallery_id)
                       ->where('user_id', $specialist->id)
                       ->firstOrFail();

        // Sterge fisierul
        if ($image->image_path) {
            $imageFullPath = public_path('storage/' . $image->image_path);
            if (file_exists($imageFullPath)) {
                unlink($imageFullPath);
            }
        }

        $image->delete();

        return redirect()->back()->with('success', 'Imaginea a fost stearsa din galerie!');
    }

    /**
     * Gestionare linkuri sociale
     */
    public function socialLinks()
    {
        $specialist = Auth::user();
        $socialLinks = $specialist->socialLinks()->get()->keyBy('platform');

        $platforms = [
            'instagram' => 'Instagram',
            'facebook' => 'Facebook', 
            'tiktok' => 'TikTok',
            'youtube' => 'YouTube',
            'whatsapp' => 'WhatsApp Business'
        ];

        return view('specialist.social-links', compact('socialLinks', 'platforms'));
    }

    /**
     * Actualizeaza linkuri sociale
     */
    public function updateSocialLinks(Request $request)
    {
        $specialist = Auth::user();

        $request->validate([
            'platforms' => 'required|array',
            'platforms.*.url' => 'nullable|url',
            'platforms.*.username' => 'nullable|string|max:100',
            'platforms.*.is_active' => 'boolean'
        ]);

        foreach ($request->platforms as $platform => $data) {
            if (!empty($data['url'])) {
                SocialLink::updateOrCreate(
                    [
                        'user_id' => $specialist->id,
                        'platform' => $platform
                    ],
                    [
                        'url' => $data['url'],
                        'username' => $data['username'] ?? null,
                        'is_active' => $data['is_active'] ?? true
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Linkurile sociale au fost actualizate!');
    }

    /**
     * Profil specialist - editare
     */
    public function profile()
    {
        $specialist = Auth::user();
        
        $subBrands = [
            'dariaNails' => 'dariaNails - Manichiura & Pedichiura',
            'dariaHair' => 'dariaHair - Coafura & Styling',
            'dariaGlow' => 'dariaGlow - Skincare & Makeup'
        ];

        $zones = [
            'Sector 1', 'Sector 2', 'Sector 3', 'Sector 4', 'Sector 5', 'Sector 6',
            'Baneasa', 'Pipera', 'Floreasca', 'Herastrau', 'Dorobanti', 'Amzei'
        ];

        return view('specialist.profile', compact('specialist', 'subBrands', 'zones'));
    }

    /**
     * Actualizare profil specialist
     */
    public function updateProfile(Request $request)
    {
        $specialist = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $specialist->id,
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'sub_brand' => 'required|in:dariaNails,dariaHair,dariaGlow',
            'offers_at_salon' => 'nullable|boolean',
            'offers_at_home' => 'nullable|boolean',
            'salon_address' => 'nullable|string|max:255',
            'transport_fee' => 'nullable|numeric|min:0',
            'max_distance' => 'nullable|integer|min:5|max:100',
            'coverage_area' => 'nullable|array',
            'mobile_equipment' => 'nullable|array',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Validare: cel puțin una dintre opțiuni trebuie selectată
        if (!$request->has('offers_at_salon') && !$request->has('offers_at_home')) {
            return redirect()->back()->withErrors(['offers_at_salon' => 'Trebuie să selectezi cel puțin o opțiune: servicii la salon sau la domiciliu!'])->withInput();
        }

        $data = $request->except(['profile_image']);
        
        // Setează boolean-urile corect
        $data['offers_at_salon'] = $request->has('offers_at_salon');
        $data['offers_at_home'] = $request->has('offers_at_home');

        if ($request->hasFile('profile_image')) {
            // Sterge imaginea veche
            if ($specialist->profile_image) {
                $oldImagePath = public_path('storage/' . $specialist->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $imagePath = $request->file('profile_image');
            $imageName = time() . '_' . $imagePath->getClientOriginalName();
            $imagePath->move(public_path('storage/profiles'), $imageName);
            $data['profile_image'] = 'profiles/' . $imageName;
        }

        $specialist->update($data);

        return redirect()->back()->with('success', 'Profilul a fost actualizat cu succes!');
    }

    /**
     * Programari specialist
     */
    public function appointments(Request $request)
    {
        $specialist = Auth::user();

        $query = $specialist->appointments()->with(['service', 'review']);

        // Filtrare dupa status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrare dupa serviciu
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Filtrare dupa data
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->latest('appointment_date')->paginate(15);

        // Serviciile specialistului pentru filtru
        $services = $specialist->services()->active()->get();

        // Statistici
        $stats = [
            'total' => $specialist->appointments()->count(),
            'confirmed' => $specialist->appointments()->where('status', 'confirmed')->count(),
            'pending' => $specialist->appointments()->where('status', 'pending')->count(),
            'cancelled' => $specialist->appointments()->where('status', 'cancelled')->count()
        ];

        return view('specialist.appointments.index', compact('appointments', 'stats', 'services'));
    }
}