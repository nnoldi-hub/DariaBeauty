<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DariaBeauty - Frumusetea ta, in maini bune')</title>
    <meta name="description" content="@yield('description', 'DariaBeauty - servicii de frumusete premium la domiciliu. dariaNails, dariaHair, dariaGlow. Programeaza acum!')">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #D4AF37; /* Gold elegant */
            --secondary-color: #2C1810; /* Dark brown */
            --accent-nails: #E91E63; /* Pink pentru dariaNails */
            --accent-hair: #9C27B0; /* Purple pentru dariaHair */
            --accent-glow: #FF9800; /* Orange pentru dariaGlow */
            --text-dark: #2C1810;
            --text-light: #6B7280;
            --background-light: #FFFBF7;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: var(--background-light);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 2rem;
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--background-light) 0%, #F7F3EF 100%);
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="beauty" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="%23D4AF37" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23beauty)"/></svg>');
            opacity: 0.5;
        }

        .sub-brand-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-top: 4px solid var(--primary-color);
            height: 100%;
        }

        .sub-brand-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .sub-brand-card.nails {
            border-top-color: var(--accent-nails);
        }

        .sub-brand-card.hair {
            border-top-color: var(--accent-hair);
        }

        .sub-brand-card.glow {
            border-top-color: var(--accent-glow);
        }

        .sub-brand-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .sub-brand-icon.nails {
            background: linear-gradient(135deg, var(--accent-nails), #F06292);
        }

        .sub-brand-icon.hair {
            background: linear-gradient(135deg, var(--accent-hair), #BA68C8);
        }

        .sub-brand-icon.glow {
            background: linear-gradient(135deg, var(--accent-glow), #FFB74D);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #E6C547);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #E6C547, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }

        .slogan {
            font-size: 1.2rem;
            color: var(--text-light);
            font-style: italic;
            margin-bottom: 2rem;
        }

        .section-title {
            position: relative;
            margin-bottom: 60px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color), #E6C547);
            border-radius: 2px;
        }

        .feature-item {
            text-align: center;
            padding: 30px 20px;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-color), #E6C547);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        footer {
            background: var(--secondary-color);
            color: white;
            padding: 60px 0 30px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-gem me-2"></i>DariaBeauty
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Acasa</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Servicii
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('darianails') }}">
                                <i class="fas fa-hand-sparkles text-danger me-2"></i>dariaNails
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('dariahair') }}">
                                <i class="fas fa-cut text-purple me-2"></i>dariaHair
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('dariaglow') }}">
                                <i class="fas fa-spa text-warning me-2"></i>dariaGlow
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('services') }}">Toate serviciile</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('specialists.index') }}">Specialisti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gallery') }}">Galerie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-primary" href="{{ route('booking.landing') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Programeaza-te
                        </a>
                    </li>
                    @auth
                        @if(in_array(auth()->user()->role, ['admin','superadmin']))
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                        @endif
                        <li class="nav-item ms-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link p-0">Delogare</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="{{ route('login') }}">Autentificare</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="nav-link text-primary" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Inregistrare
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-warning mb-3">DariaBeauty</h5>
                    <p class="mb-3"><em>"Frumusetea ta, in maini bune."</em></p>
                    <p>Servicii premium de frumusete la domiciliu, adaptate stilului tau de viata.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-tiktok fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6 class="text-warning mb-3">Sub-branduri</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('darianails') }}" class="text-white-50 text-decoration-none">dariaNails</a></li>
                        <li><a href="{{ route('dariahair') }}" class="text-white-50 text-decoration-none">dariaHair</a></li>
                        <li><a href="{{ route('dariaglow') }}" class="text-white-50 text-decoration-none">dariaGlow</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h6 class="text-warning mb-3">Servicii</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('services') }}" class="text-white-50 text-decoration-none">Servicii la domiciliu</a></li>
                        <li><a href="{{ route('services') }}" class="text-white-50 text-decoration-none">Consultanta frumusete</a></li>
                        <li><a href="{{ route('services') }}" class="text-white-50 text-decoration-none">Evenimente speciale</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-warning mb-3">Contact</h6>
                    <p class="text-white-50"><i class="fas fa-envelope me-2"></i>contact@dariabeauty.ro</p>
                    <p class="text-white-50"><i class="fas fa-phone me-2"></i>+40 123 456 789</p>
                    <p class="text-white-50"><i class="fas fa-map-marker-alt me-2"></i>Bucuresti & imprejurimi</p>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">&copy; 2024 DariaBeauty. Toate drepturile rezervate.</p>
                    <p class="text-white-50 mb-0 small">Created by <a href="https://conectica-it.ro" target="_blank" class="text-warning text-decoration-none">conectica-it.ro</a></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('terms') }}" class="text-white-50 text-decoration-none me-3">Termeni si conditii</a>
                    <a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none">Politica de confidentialitate</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>