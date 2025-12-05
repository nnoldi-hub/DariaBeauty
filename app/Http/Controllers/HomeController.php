<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Review;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Afiseaza pagina principala DariaBeauty
     */
    public function index()
    {
        // Statistici pentru homepage
        $specialistsCount = User::where('role', 'specialist')
                               ->where('is_active', true)
                               ->count();
        
        $featuredReviews = Review::approved()
                                ->featured()
                                ->with(['specialist', 'appointment'])
                                ->limit(6)
                                ->latest()
                                ->get();
        
        // Gallery featured pentru fiecare sub-brand
        $featuredGallery = [
            'dariaNails' => Gallery::bySubBrand('dariaNails')->featured()->limit(4)->get(),
            'dariaHair' => Gallery::bySubBrand('dariaHair')->featured()->limit(4)->get(),
            'dariaGlow' => Gallery::bySubBrand('dariaGlow')->featured()->limit(4)->get()
        ];

        // Servicii populare pentru fiecare sub-brand
        $popularServices = [
            'dariaNails' => Service::bySubBrand('dariaNails')->active()->limit(3)->get(),
            'dariaHair' => Service::bySubBrand('dariaHair')->active()->limit(3)->get(),
            'dariaGlow' => Service::bySubBrand('dariaGlow')->active()->limit(3)->get()
        ];

        // Specialisti featured
        $featuredSpecialists = User::where('role', 'specialist')
                                  ->where('is_active', true)
                                  ->with(['reviews', 'gallery', 'services'])
                                  ->withCount(['reviews as reviews_count'])
                                  ->withAvg('reviews as average_rating', 'rating')
                                  ->limit(6)
                                  ->get();

        return view('home', compact(
            'specialistsCount',
            'featuredReviews', 
            'featuredGallery',
            'popularServices',
            'featuredSpecialists'
        ));
    }

    /**
     * Cautare specialisti dupa zona geografica
     */
    public function searchSpecialists(Request $request)
    {
        $request->validate([
            'location' => 'required|string|min:3',
            'sub_brand' => 'nullable|in:dariaNails,dariaHair,dariaGlow',
            'service_type' => 'nullable|string',
            'max_distance' => 'nullable|integer|min:1|max:100'
        ]);

        $location = $request->input('location');
        $subBrand = $request->input('sub_brand');
        $serviceType = $request->input('service_type');
        $maxDistance = $request->input('max_distance', 25); // 25km default

        // Query pentru specialisti
        $specialists = User::where('role', 'specialist')
                          ->where('is_active', true)
                          ->with(['services', 'gallery', 'reviews'])
                          ->withCount(['reviews as reviews_count'])
                          ->withAvg('reviews as average_rating', 'rating');

        // Filtrare dupa sub-brand
        if ($subBrand) {
            $specialists->where('sub_brand', $subBrand);
        }

        // Filtrare dupa zona de acoperire (simulat pentru demo)
        // In realitate aici ar fi logica cu Google Maps API
        if ($location) {
            $specialists = $specialists->whereJsonContains('coverage_area', ['zone' => $location])
                                     ->orWhere(function($query) use ($location) {
                                         $query->whereJsonContains('coverage_area', ['city' => $location]);
                                     });
        }

        // Filtrare dupa distanta maxima
        if ($maxDistance) {
            $specialists->where('max_distance', '>=', $maxDistance);
        }

        $results = $specialists->paginate(12);

        // Adauga distanta estimata pentru fiecare specialist (simulat)
        $results->getCollection()->transform(function ($specialist) {
            $specialist->estimated_distance = rand(5, 25); // km simulat
            $specialist->estimated_travel_time = $specialist->estimated_distance * 2; // minute
            $specialist->estimated_transport_fee = $specialist->transport_fee * $specialist->estimated_distance;
            return $specialist;
        });

        return view('search-results', compact('results', 'location', 'subBrand', 'serviceType', 'maxDistance'));
    }

    /**
     * Afiseaza toate serviciile pentru un sub-brand
     */
    public function subBrandServices($subBrand = null)
    {
        // Map URL format to database format
        $urlToDb = [
            'darianails' => 'dariaNails',
            'dariahair' => 'dariaHair',
            'dariaglow' => 'dariaGlow'
        ];

        // Get the database format from URL
        $subBrandKey = $urlToDb[strtolower($subBrand)] ?? null;
        
        if (!$subBrandKey) {
            abort(404);
        }

        $specialists = User::where('role', 'specialist')
                          ->where('sub_brand', $subBrandKey)
                          ->where('is_active', true)
                          ->with(['services' => function($query) use ($subBrandKey) {
                              $query->where('sub_brand', $subBrandKey)->active();
                          }, 'gallery', 'reviews'])
                          ->withCount(['reviews as reviews_count'])
                          ->withAvg('reviews as average_rating', 'rating')
                          ->paginate(12);

        $services = Service::bySubBrand($subBrandKey)
                           ->active()
                           ->with(['specialist', 'specialist.reviews'])
                           ->get()
                           ->groupBy('category');

        $featuredGallery = Gallery::bySubBrand($subBrandKey)
                                 ->featured()
                                 ->with('specialist')
                                 ->limit(8)
                                 ->get();

        $subBrandInfo = $this->getSubBrandInfo($subBrandKey);

        return view('sub-brand', compact('specialists', 'services', 'featuredGallery', 'subBrandInfo', 'subBrand'));
    }

    /**
     * Contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Procesare formular contact
     */
    public function contactStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'preferred_sub_brand' => 'nullable|in:dariaNails,dariaHair,dariaGlow'
        ]);

        // Aici ar fi logica de trimitere email
        // Mail::to('contact@dariabeauty.ro')->send(new ContactMessage($request->all()));

        return redirect()->back()->with('success', 'Mesajul tau a fost trimis cu succes! Iti vom raspunde in cel mai scurt timp.');
    }

    /**
     * Informații despre sub-branduri
     */
    private function getSubBrandInfo($subBrand)
    {
        $info = [
            'dariaNails' => [
                'name' => 'dariaNails',
                'title' => 'Manichiura & Pedichiura Premium',
                'description' => 'Servicii profesionale de manichiura si pedichiura la domiciliu. Unghii perfect ingrijite cu produse premium.',
                'color' => '#E91E63',
                'icon' => 'fas fa-hand-sparkles',
                'services' => [
                    'Manichiura clasica',
                    'Manichiura gel/semipermanent', 
                    'Pedichiura & SPA',
                    'Nail art & design',
                    'Tratamente unghii',
                    'Intretinere & reparatii'
                ]
            ],
            'dariaHair' => [
                'name' => 'dariaHair',
                'title' => 'Coafura & Styling Professional',
                'description' => 'Servicii complete de coafura si styling la domiciliu. De la tunsori la coafuri pentru evenimente speciale.',
                'color' => '#9C27B0',
                'icon' => 'fas fa-cut',
                'services' => [
                    'Tunsori feminine',
                    'Styling & coafuri',
                    'Colorare & suvite',
                    'Tratamente pentru par',
                    'Coafuri pentru evenimente',
                    'Consultanta styling'
                ]
            ],
            'dariaGlow' => [
                'name' => 'dariaGlow', 
                'title' => 'Skincare & Makeup Expert',
                'description' => 'Tratamente faciale si machiaj profesional la domiciliu. Descopera-ti stralucirea naturala.',
                'color' => '#FF9800',
                'icon' => 'fas fa-spa',
                'services' => [
                    'Tratamente faciale',
                    'Curatare faciala profunda',
                    'Machiaj pentru evenimente',
                    'Machiaj de zi/seara',
                    'Consultanta skincare',
                    'Tratamente anti-aging'
                ]
            ]
        ];

        return $info[$subBrand] ?? null;
    }

    /**
     * Pagina publica: lista servicii (toate sub-brandurile)
     */
    public function services()
    {
        $servicesByBrand = [
            'dariaNails' => Service::bySubBrand('dariaNails')->active()->with('specialist')->get()->groupBy('category'),
            'dariaHair'  => Service::bySubBrand('dariaHair')->active()->with('specialist')->get()->groupBy('category'),
            'dariaGlow'  => Service::bySubBrand('dariaGlow')->active()->with('specialist')->get()->groupBy('category'),
        ];

        return view('services', compact('servicesByBrand'));
    }

    /**
     * Pagina publica: galerie (toate sub-brandurile)
     */
    public function gallery()
    {
        $gallery = [
            'nails' => Gallery::bySubBrand('dariaNails')->latest()->limit(24)->get(),
            'hair'  => Gallery::bySubBrand('dariaHair')->latest()->limit(24)->get(),
            'glow'  => Gallery::bySubBrand('dariaGlow')->latest()->limit(24)->get(),
        ];

        return view('gallery', compact('gallery'));
    }

    /**
     * Pagina publica: Programeaza-te (landing simplu)
     */
    public function bookingLanding(Request $request)
    {
        // Sugereaza specialisti activi si servicii populare
        $specialists = User::where('role','specialist')->where('is_active',true)
            ->withAvg('reviews as average_rating', 'rating')
            ->limit(12)->get();

        $services = Service::active()->with('specialist')->limit(18)->get();

        return view('booking', compact('specialists','services'));
    }

    /**
     * Formular inregistrare specialist (public)
     */
    public function specialistRegister()
    {
        $subBrands = [
            'dariaNails' => 'dariaNails - Manichiura & Pedichiura',
            'dariaHair' => 'dariaHair - Coafura & Styling',
            'dariaGlow' => 'dariaGlow - Skincare & Makeup'
        ];

        $zones = [
            'Sector 1', 'Sector 2', 'Sector 3', 'Sector 4', 'Sector 5', 'Sector 6',
            'Baneasa', 'Pipera', 'Floreasca', 'Herastrau', 'Dorobanti', 'Amzei'
        ];

        return view('auth.specialist-register', compact('subBrands','zones'));
    }

    /**
     * Procesare inregistrare specialist
     */
    public function specialistRegisterStore(Request $request)
    {
        // Validare de bază
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'sub_brand' => 'required|in:dariaNails,dariaHair,dariaGlow',
            'offers_at_salon' => 'nullable|boolean',
            'offers_at_home' => 'nullable|boolean',
        ];

        // Validări condiționale
        if ($request->has('offers_at_salon') && $request->offers_at_salon) {
            $rules['salon_address'] = 'required|string|max:500';
            $rules['salon_lat'] = 'nullable|numeric|between:-90,90';
            $rules['salon_lng'] = 'nullable|numeric|between:-180,180';
        }

        if ($request->has('offers_at_home') && $request->offers_at_home) {
            $rules['coverage_area'] = 'required|array|min:1';
            $rules['transport_fee'] = 'required|numeric|min:0';
            $rules['max_distance'] = 'required|integer|min:5|max:100';
        }

        $data = $request->validate($rules);

        // Asigură că cel puțin o opțiune este bifată
        if (empty($data['offers_at_salon']) && empty($data['offers_at_home'])) {
            return back()->withErrors(['offers_at_home' => 'Trebuie să oferi servicii cel puțin la salon SAU la domiciliu!'])
                        ->withInput();
        }

        // Convertește checkboxurile în boolean
        $data['offers_at_salon'] = !empty($request->offers_at_salon);
        $data['offers_at_home'] = !empty($request->offers_at_home);

        $data['role'] = 'specialist';
        $data['is_active'] = false; // așteaptă aprobare admin

        // Laravel 10 va aplica hashing automat prin cast-ul "password => hashed"
        $user = User::create($data);

        // TODO: Notifica un admin despre cererea nouă (mail/notification)

        return redirect()->route('home')->with('success', 'Cererea ta a fost înregistrată. Vei primi un email după aprobare.');
    }
}