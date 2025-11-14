@extends('layout')

@section('title', $specialist->name . ' - Specialist ' . ucfirst($specialist->sub_brand) . ' - DariaBeauty')

@section('content')
<div class="specialist-profile">
    <!-- Hero Section -->
    <section class="specialist-hero relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-100 to-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-200"></div>
        
        <div class="container mx-auto px-4 py-16 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                    <!-- Profile Image -->
                    <div class="lg:col-span-1">
                        <div class="relative">
                            <div class="w-80 h-80 mx-auto rounded-3xl overflow-hidden shadow-2xl">
                                @if($specialist->profile_image)
                                <img src="{{ asset('storage/' . $specialist->profile_image) }}" 
                                     alt="{{ $specialist->name }}" 
                                     class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full bg-white flex items-center justify-center">
                                    <i class="fas fa-user text-6xl text-gray-400"></i>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Verification Badge -->
                            <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2">
                                <div class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Verificat DariaBeauty
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="lg:col-span-2 text-center lg:text-left">
                        <!-- Sub-brand Badge -->
                        <div class="mb-4">
                            <span class="inline-block bg-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-500 text-white px-6 py-2 rounded-full text-lg font-semibold">
                                {{ ucfirst($specialist->sub_brand) }}
                            </span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                            {{ $specialist->name }}
                        </h1>

                        <!-- Rating -->
                        <div class="flex items-center justify-center lg:justify-start mb-6">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($specialist->average_rating))
                                        <i class="fas fa-star text-yellow-400 text-2xl"></i>
                                    @elseif($i == ceil($specialist->average_rating) && $specialist->average_rating - floor($specialist->average_rating) >= 0.5)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-2xl"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-2xl"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-4 text-xl text-gray-600">
                                {{ number_format($specialist->average_rating, 1) }} din 5
                            </span>
                            <span class="ml-4 text-lg text-gray-500">
                                ({{ $specialist->reviews_count }} review-uri)
                            </span>
                        </div>

                        @if($specialist->description)
                        <p class="text-xl text-gray-700 mb-8 leading-relaxed">
                            {{ $specialist->description }}
                        </p>
                        @endif

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <div class="stat-item text-center">
                                <div class="text-2xl font-bold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                    {{ $stats['completed_appointments'] }}
                                </div>
                                <div class="text-sm text-gray-600">Servicii Completate</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="text-2xl font-bold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                    {{ $stats['years_experience'] }}
                                </div>
                                <div class="text-sm text-gray-600">Ani Experienta</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="text-2xl font-bold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                    {{ $stats['coverage_zones'] }}
                                </div>
                                <div class="text-sm text-gray-600">Zone Acoperite</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="text-2xl font-bold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                    {{ $stats['response_time'] }}
                                </div>
                                <div class="text-sm text-gray-600">Timp Raspuns</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('specialists.booking', $specialist->slug) }}" 
                               class="bg-gradient-to-r from-gold to-yellow-500 text-white px-8 py-4 rounded-xl font-bold text-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-calendar-check mr-2"></i>
                                Rezerva Acum
                            </a>
                            <button onclick="shareProfile()" 
                                    class="bg-white text-gray-700 border-2 border-gray-300 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-50 transition-all duration-300">
                                <i class="fas fa-share-alt mr-2"></i>
                                Distribuie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Navigation Tabs -->
    <section class="profile-nav sticky top-0 bg-white shadow-md z-40">
        <div class="container mx-auto px-4">
            <nav class="flex overflow-x-auto">
                <a href="#servicii" class="nav-link px-6 py-4 font-semibold text-gray-600 hover:text-gold border-b-2 border-transparent hover:border-gold transition-all whitespace-nowrap">
                    Servicii
                </a>
                <a href="#galerie" class="nav-link px-6 py-4 font-semibold text-gray-600 hover:text-gold border-b-2 border-transparent hover:border-gold transition-all whitespace-nowrap">
                    Galerie
                </a>
                <a href="#reviews" class="nav-link px-6 py-4 font-semibold text-gray-600 hover:text-gold border-b-2 border-transparent hover:border-gold transition-all whitespace-nowrap">
                    Review-uri
                </a>
                <a href="#acoperire" class="nav-link px-6 py-4 font-semibold text-gray-600 hover:text-gold border-b-2 border-transparent hover:border-gold transition-all whitespace-nowrap">
                    Zone Acoperite
                </a>
                <a href="#contact" class="nav-link px-6 py-4 font-semibold text-gray-600 hover:text-gold border-b-2 border-transparent hover:border-gold transition-all whitespace-nowrap">
                    Contact
                </a>
            </nav>
        </div>
    </section>

    <!-- Servicii Section -->
    <section id="servicii" class="services-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Servicii Oferite</h2>
            
            @if($servicesByCategory->count() > 0)
            @foreach($servicesByCategory as $category => $services)
            <div class="category-section mb-12">
                <h3 class="text-2xl font-semibold mb-6 text-gray-800">{{ $category }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                    <div class="service-card bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                        @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->name }}" 
                             class="w-full h-32 object-cover rounded-lg mb-4">
                        @endif
                        
                        <h4 class="text-xl font-semibold mb-2">{{ $service->name }}</h4>
                        <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                        
                        <div class="service-details space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Durata:</span>
                                <span class="font-semibold">{{ $service->duration }} min</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pret:</span>
                                <span class="font-bold text-gold text-lg">{{ $service->price }} lei</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('specialists.booking', [$specialist->slug, 'service_id' => $service->id]) }}" 
                           class="block w-full bg-gradient-to-r from-gold to-yellow-500 text-white text-center py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                            Rezerva Serviciul
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            @else
            <div class="text-center py-12">
                <i class="fas fa-spa text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">Nu sunt servicii disponibile momentan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Galerie Section -->
    <section id="galerie" class="gallery-section py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Galerie</h2>
            
            @if($gallery['all']->count() > 0)
            <!-- Gallery Tabs -->
            <div class="gallery-tabs flex justify-center mb-8">
                <button onclick="showGalleryTab('featured')" class="gallery-tab-btn active px-6 py-2 mx-2 bg-gold text-white rounded-lg">
                    Imagini Favorite
                </button>
                <button onclick="showGalleryTab('before-after')" class="gallery-tab-btn px-6 py-2 mx-2 bg-gray-200 text-gray-700 rounded-lg">
                    Inainte/Dupa
                </button>
                <button onclick="showGalleryTab('all')" class="gallery-tab-btn px-6 py-2 mx-2 bg-gray-200 text-gray-700 rounded-lg">
                    Toate Imaginile
                </button>
            </div>

            <!-- Featured Gallery -->
            <div id="gallery-featured" class="gallery-tab active">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($gallery['featured'] as $image)
                    <div class="gallery-item cursor-pointer" onclick="openLightbox('{{ asset('storage/' . $image->image_path) }}', '{{ $image->caption }}')">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="{{ $image->caption }}" 
                             class="w-full h-64 object-cover rounded-lg hover:scale-105 transition-transform duration-300">
                        @if($image->caption)
                        <p class="text-sm text-gray-600 mt-2 text-center">{{ $image->caption }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Before/After Gallery -->
            <div id="gallery-before-after" class="gallery-tab hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach($gallery['before_after']->chunk(2) as $pair)
                    <div class="before-after-pair">
                        @if($pair->count() == 2)
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <img src="{{ asset('storage/' . $pair[0]->image_path) }}" 
                                     alt="Inainte" 
                                     class="w-full h-64 object-cover rounded-lg">
                                <p class="text-center text-sm text-gray-600 mt-2">Inainte</p>
                            </div>
                            <div class="w-1/2">
                                <img src="{{ asset('storage/' . $pair[1]->image_path) }}" 
                                     alt="Dupa" 
                                     class="w-full h-64 object-cover rounded-lg">
                                <p class="text-center text-sm text-gray-600 mt-2">Dupa</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- All Gallery -->
            <div id="gallery-all" class="gallery-tab hidden">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($gallery['all'] as $image)
                    <div class="gallery-item cursor-pointer" onclick="openLightbox('{{ asset('storage/' . $image->image_path) }}', '{{ $image->caption }}')">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="{{ $image->caption }}" 
                             class="w-full h-64 object-cover rounded-lg hover:scale-105 transition-transform duration-300">
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-camera text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">Nu sunt imagini in galerie momentan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews" class="reviews-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Review-uri de la Clienti</h2>
            
            @if($reviews->count() > 0)
            <div class="max-w-4xl mx-auto">
                @foreach($reviews as $review)
                <div class="review-card bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="avatar w-12 h-12 bg-gradient-to-br from-gold to-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-lg">{{ $review->client_name }}</h4>
                                <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                            </div>
                            
                            <!-- Service -->
                            @if($review->appointment && $review->appointment->service)
                            <p class="text-sm text-gray-600 mb-3">
                                <i class="fas fa-spa mr-1"></i>
                                {{ $review->appointment->service->name }}
                            </p>
                            @endif
                            
                            <!-- Comment -->
                            <p class="text-gray-700 mb-4">{{ $review->comment }}</p>
                            
                            <!-- Detailed Ratings -->
                            <div class="detailed-ratings grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                                <div class="text-center">
                                    <div class="font-semibold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                        {{ $review->service_quality_rating }}/5
                                    </div>
                                    <div class="text-gray-600">Calitate</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                        {{ $review->punctuality_rating }}/5
                                    </div>
                                    <div class="text-gray-600">Punctualitate</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                        {{ $review->cleanliness_rating }}/5
                                    </div>
                                    <div class="text-gray-600">Curatenie</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-600">
                                        {{ $review->overall_experience }}/5
                                    </div>
                                    <div class="text-gray-600">Experienta</div>
                                </div>
                            </div>
                            
                            <!-- Photos -->
                            @if($review->photos && count($review->photos) > 0)
                            <div class="review-photos flex gap-2 mb-4">
                                @foreach($review->photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" 
                                     alt="Poza review" 
                                     class="w-16 h-16 object-cover rounded cursor-pointer"
                                     onclick="openLightbox('{{ asset('storage/' . $photo) }}')">
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Specialist Response -->
                            @if($review->specialist_response)
                            <div class="specialist-response bg-gray-50 rounded-lg p-4 mt-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-reply text-gold mr-2"></i>
                                    <span class="font-semibold text-gold">Raspuns de la {{ $specialist->name }}</span>
                                </div>
                                <p class="text-gray-700">{{ $review->specialist_response }}</p>
                            </div>
                            @endif
                            
                            <!-- Helpful Button -->
                            <div class="flex items-center justify-between mt-4">
                                <button onclick="markHelpful({{ $review->id }})" class="helpful-btn text-gray-600 hover:text-gold">
                                    <i class="fas fa-thumbs-up mr-1"></i>
                                    Util
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $reviews->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">Nu sunt review-uri momentan</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Zone Acoperite Section -->
    <section id="acoperire" class="coverage-section py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Zone de Acoperire</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Coverage Info -->
                    <div class="coverage-info">
                        <h3 class="text-2xl font-semibold mb-6">Informații Transport</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Taxa de bază transport:</span>
                                <span class="font-bold text-gold">{{ $specialist->transport_fee ?? 20 }} lei</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Distanță maximă:</span>
                                <span class="font-bold text-gold">{{ $specialist->max_distance ?? 30 }} km</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="text-gray-700">Taxa suplimentară:</span>
                                <span class="font-bold text-gold">2 lei/km (peste 10km)</span>
                            </div>
                        </div>
                        
                        @if($specialist->mobile_equipment)
                        <h3 class="text-2xl font-semibold mb-6 mt-8">Echipament Mobil</h3>
                        <div class="space-y-2">
                            @foreach($specialist->mobile_equipment as $equipment)
                            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">{{ $equipment }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    
                    <!-- Coverage Zones -->
                    <div class="coverage-zones">
                        <h3 class="text-2xl font-semibold mb-6">Zone Deservite</h3>
                        @if($specialist->coverage_area)
                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $areas = is_array($specialist->coverage_area) ? $specialist->coverage_area : json_decode($specialist->coverage_area, true);
                            @endphp
                            @foreach($areas as $area)
                            <div class="zone-badge bg-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-100 text-{{ $specialist->sub_brand == 'dariaNails' ? 'pink' : ($specialist->sub_brand == 'dariaHair' ? 'purple' : 'orange') }}-700 px-4 py-2 rounded-lg text-center font-medium">
                                {{ $area }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Contact & Social Media</h2>
            
            <div class="max-w-2xl mx-auto text-center">
                <!-- Contact Info -->
                <div class="contact-info mb-8">
                    @if($specialist->phone)
                    <a href="tel:{{ $specialist->phone }}" 
                       class="inline-flex items-center bg-green-500 text-white px-6 py-3 rounded-lg font-semibold mr-4 mb-4 hover:bg-green-600 transition-all duration-300">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $specialist->phone }}
                    </a>
                    @endif
                    
                    @if($specialist->email)
                    <a href="mailto:{{ $specialist->email }}" 
                       class="inline-flex items-center bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold mb-4 hover:bg-blue-600 transition-all duration-300">
                        <i class="fas fa-envelope mr-2"></i>
                        Trimite Email
                    </a>
                    @endif
                </div>
                
                <!-- Social Links -->
                @if($specialist->socialLinks->count() > 0)
                <div class="social-links">
                    <h3 class="text-xl font-semibold mb-4">Urmărește-mă pe</h3>
                    <div class="flex justify-center gap-4">
                        @foreach($specialist->socialLinks as $social)
                        <a href="{{ $social->url }}" 
                           target="_blank"
                           class="social-btn w-12 h-12 rounded-full flex items-center justify-center text-white text-xl hover:scale-110 transition-all duration-300
                           @if($social->platform == 'instagram') bg-gradient-to-r from-purple-500 to-pink-500
                           @elseif($social->platform == 'facebook') bg-blue-600
                           @elseif($social->platform == 'tiktok') bg-black
                           @elseif($social->platform == 'youtube') bg-red-600
                           @elseif($social->platform == 'whatsapp') bg-green-500
                           @else bg-gray-500 @endif">
                            <i class="fab fa-{{ $social->platform }}"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl z-10">
            <i class="fas fa-times"></i>
        </button>
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
        <p id="lightbox-caption" class="text-white text-center mt-4"></p>
    </div>
</div>

<script>
// Gallery Tabs
function showGalleryTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.gallery-tab').forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById('gallery-' + tabName).classList.remove('hidden');
    document.getElementById('gallery-' + tabName).classList.add('active');
    
    // Update tab buttons
    document.querySelectorAll('.gallery-tab-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-gold', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    event.target.classList.add('active', 'bg-gold', 'text-white');
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
}

// Lightbox
function openLightbox(imageSrc, caption = '') {
    document.getElementById('lightbox-image').src = imageSrc;
    document.getElementById('lightbox-caption').textContent = caption;
    document.getElementById('lightbox').classList.remove('hidden');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
}

// Smooth scrolling for navigation
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
            const offsetTop = targetElement.offsetTop - 80; // Account for sticky nav
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

// Update active nav link on scroll
window.addEventListener('scroll', function() {
    const sections = ['servicii', 'galerie', 'reviews', 'acoperire', 'contact'];
    const scrollPos = window.scrollY + 100;
    
    sections.forEach(section => {
        const element = document.getElementById(section);
        if (element) {
            const offsetTop = element.offsetTop;
            const height = element.offsetHeight;
            
            if (scrollPos >= offsetTop && scrollPos < offsetTop + height) {
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('border-gold', 'text-gold');
                    link.classList.add('border-transparent', 'text-gray-600');
                });
                
                const activeLink = document.querySelector(`a[href="#${section}"]`);
                if (activeLink) {
                    activeLink.classList.add('border-gold', 'text-gold');
                    activeLink.classList.remove('border-transparent', 'text-gray-600');
                }
            }
        }
    });
});

// Share Profile
function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $specialist->name }} - DariaBeauty',
            text: 'Specialist {{ ucfirst($specialist->sub_brand) }} - {{ $specialist->name }}',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link-ul a fost copiat in clipboard!');
        });
    }
}

// Mark Review as Helpful
function markHelpful(reviewId) {
    // This would normally make an AJAX request
    console.log('Marking review ' + reviewId + ' as helpful');
}

// Close lightbox on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>

<style>
.specialist-hero {
    min-height: 60vh;
}

.stat-item {
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
}

.profile-nav {
    backdrop-filter: blur(10px);
}

.nav-link {
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-4px);
}

.gallery-item {
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.review-card {
    transition: transform 0.3s ease;
}

.review-card:hover {
    transform: translateY(-2px);
}

.social-btn {
    transition: all 0.3s ease;
}

.zone-badge {
    transition: all 0.3s ease;
}

.zone-badge:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .specialist-hero {
        padding: 2rem 0;
    }
    
    .profile-nav nav {
        padding: 0 1rem;
    }
    
    .stat-item {
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection