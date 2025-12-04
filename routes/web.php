<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReviewController;
// Added missing controller imports referenced below
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\Client\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aici sunt rutele web pentru aplicatia DariaBeauty.
| Aceste rute sunt incarcate de RouteServiceProvider si sunt asignate
| middleware-ului "web", care ofera functionalitati ca sesiunile si CSRF.
|
*/

// Rute publice - Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');
// Dashboard post-autentificare (redirijeaza in functie de rol)
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && in_array($user->role, ['admin', 'superadmin'])) {
        return redirect()->route('admin.dashboard');
    }
    if ($user && $user->role === 'specialist') {
        return redirect()->route('specialist.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactStore'])->name('contact.store');
// Inregistrare specialist (public)
Route::get('/inregistrare-specialist', [HomeController::class, 'specialistRegister'])->name('specialist.register');
Route::post('/inregistrare-specialist', [HomeController::class, 'specialistRegisterStore'])->name('specialist.register.store');
// Meniu public suplimentar
Route::get('/servicii', [HomeController::class, 'services'])->name('services');
Route::get('/galerie', [HomeController::class, 'gallery'])->name('gallery');
// Termeni si conditii + Politica de confidentialitate + Cookies
Route::view('/termeni-si-conditii', 'terms')->name('terms');
Route::view('/politica-confidentialitate', 'privacy')->name('privacy');
Route::view('/setari-cookies', 'cookies')->name('cookies');
Route::get('/programeaza-te', [HomeController::class, 'bookingLanding'])->name('booking.landing');

// Cautare si filtrare
Route::get('/cautare', [HomeController::class, 'searchSpecialists'])->name('search');
Route::get('/zona/{zone}', [HomeController::class, 'searchByZone'])->name('search.zone');

// Sub-branduri - pass the sub_brand as route parameter
Route::get('/darianails', function() {
    return app(HomeController::class)->subBrandServices('darianails');
})->name('darianails');
Route::get('/dariahair', function() {
    return app(HomeController::class)->subBrandServices('dariahair');
})->name('dariahair');
Route::get('/dariaglow', function() {
    return app(HomeController::class)->subBrandServices('dariaglow');
})->name('dariaglow');

// API endpoints pentru sub-branduri
Route::get('/api/sub-brand/{brand}', [HomeController::class, 'getSubBrandInfo'])->name('api.subbrand');

// Specialisti - vizualizare publica
Route::prefix('specialisti')->name('specialists.')->group(function () {
    Route::get('/', [SpecialistController::class, 'index'])->name('index');
    Route::get('/{slug}', [SpecialistController::class, 'show'])->name('show');
    Route::get('/{slug}/rezervare', [SpecialistController::class, 'booking'])->name('booking');
    Route::post('/{slug}/rezervare', [SpecialistController::class, 'storeBooking'])->name('booking.store');
});

// Reviews publice
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/specialist/{specialist}', [ReviewController::class, 'index'])->name('specialist');
    Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
});

// Rute care necesita autentificare
Route::middleware(['auth'])->group(function () {
    
    // Programari - client
    Route::prefix('programari')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/creeaza', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
        Route::post('/{appointment}/anuleaza', [AppointmentController::class, 'cancel'])->name('cancel');
        Route::post('/{appointment}/reprogrameaza', [AppointmentController::class, 'reschedule'])->name('reschedule');
    });

    // Reviews - client
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/creeaza/{appointment}', [ReviewController::class, 'create'])->name('create');
        Route::post('/{appointment}', [ReviewController::class, 'store'])->name('store');
        Route::post('/{review}/util', [ReviewController::class, 'markHelpful'])->name('helpful');
        Route::post('/{review}/raporteaza', [ReviewController::class, 'report'])->name('report');
    });

    // API pentru disponibilitate
    Route::get('/api/disponibilitate/{specialist}', [AppointmentController::class, 'availability'])->name('api.availability');
    
});

// Rute pentru clienți (necesită autentificare)
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    // Profil client
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/parola', [ProfileController::class, 'updatePassword'])->name('password.update');
    
    // Programări client
    Route::get('/programari', [AppointmentController::class, 'clientAppointments'])->name('appointments');
    
    // Review-uri client
    Route::get('/recenzii', [ReviewController::class, 'clientReviews'])->name('reviews');
});

// Rute pentru specialisti (necesita auth si rol de specialist)
Route::middleware(['auth', 'specialist'])->prefix('specialist')->name('specialist.')->group(function () {
    
    // Dashboard specialist
    Route::get('/dashboard', [SpecialistController::class, 'dashboard'])->name('dashboard');
    
    // Profil specialist
    Route::get('/profil', [SpecialistController::class, 'profile'])->name('profile');
    Route::put('/profil', [SpecialistController::class, 'updateProfile'])->name('profile.update');
    
    // Servicii
    Route::prefix('servicii')->name('services.')->group(function () {
        Route::get('/', [SpecialistController::class, 'services'])->name('index');
        Route::get('/adauga', [SpecialistController::class, 'createService'])->name('create');
        Route::post('/', [SpecialistController::class, 'storeService'])->name('store');
        Route::get('/{service}/editeaza', [SpecialistController::class, 'editService'])->name('edit');
        Route::put('/{service}', [SpecialistController::class, 'updateService'])->name('update');
        Route::delete('/{service}', [SpecialistController::class, 'destroyService'])->name('destroy');
    });
    
    // Galerie
    Route::prefix('galerie')->name('gallery.')->group(function () {
        Route::get('/', [SpecialistController::class, 'gallery'])->name('index');
        Route::post('/upload', [SpecialistController::class, 'storeGalleryImage'])->name('store');
        Route::put('/{image}', [SpecialistController::class, 'updateGalleryImage'])->name('update');
        Route::delete('/{image}', [SpecialistController::class, 'destroyGalleryImage'])->name('destroy');
    });
    
    // Linkuri sociale
    Route::get('/social', [SpecialistController::class, 'socialLinks'])->name('social');
    Route::put('/social', [SpecialistController::class, 'updateSocialLinks'])->name('social.update');
    
    // Programari - specialist
    Route::prefix('programari')->name('appointments.')->group(function () {
        Route::get('/', [SpecialistController::class, 'appointments'])->name('index');
        Route::post('/{appointment}/confirma', [AppointmentController::class, 'confirm'])->name('confirm');
        Route::post('/{appointment}/finalizeaza', [AppointmentController::class, 'complete'])->name('complete');
    });
    
    // Reviews - specialist
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'specialistReviews'])->name('index');
        Route::post('/{review}/aproba', [ReviewController::class, 'approve'])->name('approve');
        Route::post('/{review}/respinge', [ReviewController::class, 'reject'])->name('reject');
        Route::post('/{review}/raspunde', [ReviewController::class, 'respond'])->name('respond');
        Route::get('/export', [ReviewController::class, 'exportReviews'])->name('export');
    });
    
});

// Rute pentru administratori (necesita auth si rol de admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestionare specialisti - CRUD complet
    Route::prefix('specialists')->name('specialists.')->group(function () {
        Route::get('/pending', [AdminController::class, 'pendingSpecialists'])->name('pending');
        Route::post('/{id}/approve', [AdminController::class, 'approveSpecialist'])->name('approve');
        Route::delete('/{id}/reject', [AdminController::class, 'rejectSpecialist'])->name('reject');
    });
    
    // Gestionare utilizatori - CRUD
    Route::prefix('users')->name('users-crud.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('toggle');
    });
    
    // Gestionare servicii - CRUD
    Route::prefix('services')->name('services-crud.')->group(function () {
        Route::get('/', [AdminServiceController::class, 'index'])->name('index');
        Route::get('/create', [AdminServiceController::class, 'create'])->name('create');
        Route::post('/', [AdminServiceController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [AdminServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [AdminServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [AdminServiceController::class, 'destroy'])->name('destroy');
        Route::post('/{service}/toggle', [AdminServiceController::class, 'toggleStatus'])->name('toggle');
    });
    
    // Pagini admin simple (redirect la CRUD)
    Route::get('/utilizatori', function() {
        return redirect()->route('admin.users-crud.index');
    })->name('users');
    Route::get('/servicii', function() {
        return redirect()->route('admin.services-crud.index');
    })->name('services');
    Route::get('/programari', [AdminAppointmentController::class, 'index'])->name('appointments');
    Route::get('/programari/export', [AdminAppointmentController::class, 'export'])->name('appointments.export');
    Route::get('/rapoarte', [AdminController::class, 'reports'])->name('reports');
    
    // Gestionare reviews - CRUD
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [AdminReviewController::class, 'show'])->name('show');
        Route::patch('/{review}/approve', [AdminReviewController::class, 'approve'])->name('approve');
        Route::patch('/{review}/reject', [AdminReviewController::class, 'reject'])->name('reject');
        Route::patch('/{review}/respond', [AdminReviewController::class, 'respond'])->name('respond');
        Route::delete('/{review}', [AdminReviewController::class, 'destroy'])->name('destroy');
    });
    
    // Setari aplicatie
    Route::get('/setari', [AdminController::class, 'settings'])->name('settings');
    Route::put('/setari', [AdminController::class, 'updateSettings'])->name('settings.update');
    
});

// Rute pentru API (daca este necesar) - TO BE IMPLEMENTED
// Route::prefix('api/v1')->name('api.')->group(function () {
//     
//     // API public
//     Route::get('/specialisti', [SpecialistController::class, 'apiIndex']);
//     Route::get('/servicii', [HomeController::class, 'apiServices']);
//     Route::get('/zone-acoperire', [HomeController::class, 'apiCoverageZones']);
//     
//     // API cu autentificare
//     Route::middleware(['auth:sanctum'])->group(function () {
//         Route::apiResource('programari', 'ApiAppointmentController');
//         Route::apiResource('reviews', 'ApiReviewController');
//     });
//     
// });

// Rute pentru webhook-uri (plati, notificari, etc.)
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::post('/stripe', [PaymentController::class, 'stripeWebhook'])->name('stripe');
    Route::post('/sms-status', [NotificationController::class, 'smsStatus'])->name('sms');
});

// Ruta de fallback pentru 404 personalizat
Route::fallback(function () {
    return view('errors.404');
});

/*
|--------------------------------------------------------------------------
| Rute specializate pentru DariaBeauty
|--------------------------------------------------------------------------
*/

// Rute pentru servicii mobile speciale
Route::prefix('servicii-mobile')->name('mobile.')->group(function () {
    Route::get('/zone-acoperire', [HomeController::class, 'coverageZones'])->name('coverage');
    Route::get('/calculator-transport', [HomeController::class, 'transportCalculator'])->name('transport');
    Route::post('/verifica-zona', [HomeController::class, 'checkZone'])->name('check-zone');
});

// Rute pentru evenimente speciale si promotii
Route::prefix('promotii')->name('promotions.')->group(function () {
    Route::get('/', [HomeController::class, 'promotions'])->name('index');
    Route::get('/{slug}', [HomeController::class, 'promotionShow'])->name('show');
});

// Rute pentru blog/articole (optional)
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Rute pentru SEO si sitemap
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Rute pentru social media integration
Route::prefix('social')->name('social.')->group(function () {
    Route::get('/instagram-feed', [SocialController::class, 'instagramFeed'])->name('instagram');
    Route::post('/share/{specialist}', [SocialController::class, 'shareSpecialist'])->name('share');
});

// Breeze auth routes (login/logout/register, password reset)
require __DIR__.'/auth.php';
