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
            'client_address' => $isHomeService ? $request->address : $specialist->salon_address,
            'notes' => $request->notes,
            'is_home_service' => $isHomeService,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending'
        ]);

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = $specialist->id;
        $data['sub_brand'] = $specialist->sub_brand ?? 'dariaNails'; // Default la dariaNails dacă nu e setat
        $data['is_mobile'] = true; // Toate serviciile DariaBeauty sunt mobile

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        Service::create($data);

        return redirect()->route('specialist.services.index')->with('success', 'Serviciul a fost adaugat cu succes!');
    }

    /**
     * Editeaza serviciu
     */
    public function editService(Service $service)
    {
        $specialist = Auth::user();

        // Verifica ca serviciul apartine specialistului
        if ($service->user_id !== $specialist->id) {
            abort(403, 'Unauthorized');
        }

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
    public function updateService(Request $request, Service $service)
    {
        $specialist = Auth::user();

        // Verifica ca serviciul apartine specialistului
        if ($service->user_id !== $specialist->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'category' => 'required|string',
            'preparation_time' => 'nullable|integer|min:0',
            'equipment_needed' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            // Sterge imaginea veche
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return redirect()->route('specialist.services.index')->with('success', 'Serviciul a fost actualizat cu succes!');
    }

    /**
     * Sterge serviciu
     */
    public function destroyService(Service $service)
    {
        $specialist = Auth::user();

        // Verifica ca serviciul apartine specialistului
        if ($service->user_id !== $specialist->id) {
            abort(403, 'Unauthorized');
        }

        // Sterge imaginea
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
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

        $imagePath = $request->file('image')->store('gallery', 'public');

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
    public function updateGalleryImage(Request $request, Gallery $image)
    {
        $specialist = Auth::user();

        // Verifica ca imaginea apartine specialistului
        if ($image->user_id !== $specialist->id) {
            abort(403, 'Unauthorized');
        }

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
    public function destroyGalleryImage(Gallery $image)
    {
        $specialist = Auth::user();

        // Verifica ca imaginea apartine specialistului
        if ($image->user_id !== $specialist->id) {
            abort(403, 'Unauthorized');
        }

        // Sterge fisierul
        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
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
                Storage::disk('public')->delete($specialist->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
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