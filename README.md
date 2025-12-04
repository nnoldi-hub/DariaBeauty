# 💎 DariaBeauty - Platformă Premium de Servicii Beauty la Domiciliu

![DariaBeauty](https://img.shields.io/badge/Laravel-9+-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

> **Conectăm specialiști în frumusețe cu clienții din București și împrejurimi. Servicii profesionale de beauty, direct la domiciliul tău!**

---

## 📋 Despre DariaBeauty

**DariaBeauty** este o platformă web completă și modernă care revoluționează industria de beauty prin servicii mobile premium. Oferim o experiență seamless pentru clienți să rezerve servicii de înfrumusețare la domiciliu, iar specialiștii să își gestioneze businessul eficient.

### 🎯 Sub-branduri Specializate

| Brand | Specializare | Servicii |
|-------|-------------|----------|
| **💅 dariaNails** | Manichiură & Pedichiură | Gelish, Sculpting, French, Nail Art, Spa Tratamente |
| **✂️ dariaHair** | Coafură & Styling | Tuns, Vopsit, Balayage, Styling Evenimente, Tratamente |
| **✨ dariaGlow** | Skincare & Makeup | Tratamente Faciale, Makeup Profesional, Hidratare, Anti-Aging |

---

## ✨ Caracteristici Principale

### 👥 Sistem Multi-Rol Avansat
- **🔐 Autentificare Securizată**: Laravel Breeze cu hash-uri bcrypt
- **👨‍💼 Admin Panel**: Control complet al platformei
  - Dashboard cu statistici în timp real
  - Gestionare utilizatori (CRUD complet)
  - Aprobare specialiști noi
  - Management servicii globale
  - Monitorizare programări și review-uri
  
- **💼 Specialist Panel**: Business management profesional
  - Dashboard personal cu KPI-uri
  - Gestionare servicii proprii (preț, durată, descriere, imagini)
  - Galerie foto organizată (Featured, Before/After, All)
  - Management zone de acoperire
  - Răspunsuri la review-uri clienți
  - Profile editing complet cu imagine
  - Social media integration (Instagram, Facebook, TikTok, YouTube)
  
- **👤 Client Access**: Experiență utilizator optimizată
  - Căutare și filtrare specialiști avansată
  - Rezervări online cu formular complet
  - Istoric programări
  - Sistem de review-uri post-serviciu

### 🎨 Design și UX

#### Interface Modern și Responsive
- **Design System Consistent**: Golden palette (#D4AF37, #FFD700) pe tot site-ul
- **Mobile-First Approach**: Funcționare perfectă pe orice dispozitiv
- **Bootstrap 5.3**: Grid system flexibil și componente moderne
- **Font Awesome 6**: 1000+ iconițe pentru UX îmbunătățit
- **Animații Smooth**: Tranziții CSS3 și hover effects

#### Pagini Publice Optimizate
✅ **Homepage Impresionantă**
  - Hero section cu call-to-action
  - Prezentare sub-branduri cu imagini
  - Statistici live (specialiști, review-uri, zone)
  - Testimoniale clienți
  - FAQ interactiv

✅ **Listă Specialiști**
  - Grid view și List view
  - Filtrare după: sub-brand, zonă, servicii, rating
  - Sortare: cel mai bine cotat, experiență, preț
  - Cards compacte cu informații esențiale
  - Badge-uri de verificare

✅ **Profil Specialist Detaliat**
  - Header compact cu poza, rating, descriere
  - Statistici: număr servicii, ani experiență
  - Grid servicii cu imagini și prețuri
  - Review-uri clienți cu rating vizual
  - Contact direct (telefon, email)
  - Zone acoperite cu badge-uri
  - Social media links cu iconițe colorate

✅ **Pagină Servicii**
  - Organizare pe categorii
  - Cards cu imagini și descriere
  - Filtrare după sub-brand
  - CTA direct la programare

✅ **Galerie Foto**
  - Layout tip Pinterest
  - Filtrare pe sub-branduri
  - Lightbox pentru imagine mare
  - Lazy loading pentru performanță

✅ **Sistem Programări**
  - Formular intuitiv cu validare
  - Selecție serviciu, dată, oră
  - Câmpuri client (nume, email, telefon, adresă)
  - Observații speciale
  - Confirmare prin email/SMS

### 🔍 Funcționalități Avansate

#### Sistem Review-uri Complet
- **Rating 1-5 stele** cu vizualizare grafică
- **Comentarii text** cu moderare
- **Răspunsuri specialist** pentru feedback pozitiv
- **Afișare statistici**: medie rating, număr review-uri
- **Sortare și filtrare** review-uri

#### Galerie Dinamică
- **Categorii**: Featured (highlight), Before/After (transformări), All (tot portofoliul)
- **Upload multiple imagini** cu preview instant
- **Crop și resize** automat pentru optimizare
- **Organizare pe servicii** și sub-branduri

#### Căutare și Filtrare Inteligentă
- **Search bar** pentru căutare după nume
- **Filtre multiple**: 
  - Sub-brand (dariaNails, dariaHair, dariaGlow)
  - Zone (Sector 1-6, Baneasa, Pipera, Dorobanti)
  - Servicii oferite
  - Rating minim
  - Interval preț
- **Sortare flexibilă**: rating, experiență, preț, recente

#### Zone de Acoperire Geografică
- **9+ zone**: Sectoare Bucuresti (1-6), Baneasa, Pipera, Dorobanti
- **Vizualizare pe hartă**: Google Maps integration ready
- **Filtrare după proximitate**: găsește specialiști din zona ta

### 🔒 Securitate și Conformitate

✅ **GDPR Compliant**
- Politică de confidențialitate detaliată
- Termeni și condiții complete
- Cookie consent banner cu preferințe
- Pagină setări cookies (Essential, Functional, Analytics, Marketing)
- localStorage pentru persistența preferințelor

✅ **Securitate Robustă**
- **CSRF Protection**: Tokeni pentru toate formularele
- **XSS Prevention**: Escape automat în Blade
- **SQL Injection**: Eloquent ORM cu prepared statements
- **Password Hashing**: Bcrypt cu cost 12
- **HTTPS Ready**: Configurare SSL în .htaccess
- **Security Headers**: X-Frame-Options, X-Content-Type-Options, CSP

### 📱 Social Media Integration

- **Instagram**: Feed integration, link direct
- **Facebook**: Pagină business cu messenger
- **TikTok**: Profil pentru video content
- **YouTube**: Canal pentru tutoriale
- **LinkedIn**: Networking profesional
- **Twitter**: Updates și comunicare

Toate cu iconițe colorate și link-uri funcționale în profilul specialist.

---

## 🛠️ Stack Tehnologic Complet

### Backend
- **Framework**: Laravel 9.52+ (PHP 8.1+)
- **ORM**: Eloquent cu relații complexe
- **Authentication**: Laravel Breeze + Multi-guard
- **Validare**: Form Requests cu reguli custom
- **File Storage**: Laravel Storage + Symbolic Links
- **Database**: MySQL 8.0 cu migrări versionate
- **Seeders**: Date demo pentru testare

### Frontend
- **Template Engine**: Blade Components
- **CSS Framework**: Bootstrap 5.3.2
- **Icons**: Font Awesome 6.5
- **JavaScript**: Vanilla JS + Alpine.js ready
- **Asset Bundling**: Vite 4.0

### DevOps
- **Version Control**: Git + GitHub
- **Deployment**: cPanel Git Deploy + .cpanel.yml
- **Environment**: .env configuration
- **Cache**: Config, Route, View caching
- **Logging**: Laravel Log facade

---

## 🛠️ Stack Tehnologic

- **Backend**: Laravel 9+ (PHP 8.1)
- **Database**: MySQL 8.0
- **Frontend**: Blade Templates, Bootstrap 5.3
- **Icons**: Font Awesome 6
- **Authentication**: Laravel Breeze

## 📦 Instalare Locală

### 📋 Cerințe Minime

| Tehnologie | Versiune Minimă | Recomandată |
|-----------|----------------|-------------|
| PHP | 8.1 | 8.2+ |
| Composer | 2.0 | Latest |
| MySQL | 8.0 | 8.0.33+ |
| Node.js | 16.x | 18.x LTS |
| npm | 8.x | 9.x |

**Extensii PHP necesare**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo

### 🚀 Pași de Instalare (Development)

#### 1. Clonare Repository
```bash
git clone https://github.com/nnoldi-hub/DariaBeauty.git
cd DariaBeauty/dariabeauty
```

#### 2. Instalare Dependencies Backend
```bash
composer install
```

#### 3. Instalare Dependencies Frontend
```bash
npm install
npm run build
```

#### 4. Configurare Environment
```bash
# Copiază fișierul de configurare
cp .env.example .env

# Generează application key
php artisan key:generate
```

#### 5. Configurare Bază de Date

Editează `.env` cu credențialele tale MySQL:

```env
APP_NAME=DariaBeauty
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dariabeauty
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

Creează baza de date:
```bash
mysql -u root -p
CREATE DATABASE dariabeauty CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### 6. Migrări și Date Demo
```bash
# Rulează migrările
php artisan migrate

# Populează cu date demo (9 specialiști, servicii, review-uri)
php artisan db:seed

# SAU fresh install (șterge tot și populează din nou)
php artisan migrate:fresh --seed
```

#### 7. Storage Configuration
```bash
# Creează symbolic link pentru imagini
php artisan storage:link

# Verifică permisiunile (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

#### 8. Cache Optimization (Opțional pentru development)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 9. Pornire Server Development
```bash
php artisan serve
```

Aplicația va fi disponibilă la: **http://127.0.0.1:8000**

### 🌐 Accesare Aplicație

| Secțiune | URL | Rol Necesar |
|----------|-----|-------------|
| **Homepage** | http://127.0.0.1:8000 | Public |
| **Specialiști** | http://127.0.0.1:8000/specialisti | Public |
| **Servicii** | http://127.0.0.1:8000/servicii | Public |
| **Galerie** | http://127.0.0.1:8000/galerie | Public |
| **Contact** | http://127.0.0.1:8000/contact | Public |
| **Login** | http://127.0.0.1:8000/login | Toate rolurile |
| **Register** | http://127.0.0.1:8000/register | Public (Clienți) |
| **Dashboard Admin** | http://127.0.0.1:8000/admin/dashboard | Admin |
| **Dashboard Specialist** | http://127.0.0.1:8000/specialist/dashboard | Specialist |

## 👤 Conturi Demo

După rularea `php artisan db:seed`, vei avea următoarele conturi de test:

### 👨‍💼 Administrator
| Câmp | Valoare |
|------|---------|
| **Email** | admin@dariabeauty.ro |
| **Parolă** | password |
| **Rol** | Admin/Superadmin |
| **Acces** | Panel complet admin, toate funcționalitățile |

**Capabilități Admin:**
- ✅ Gestionare utilizatori (Create, Read, Update, Delete)
- ✅ Aprobare/Respingere specialiști noi
- ✅ Management servicii globale
- ✅ Monitorizare programări în timp real
- ✅ Moderare review-uri
- ✅ Rapoarte și statistici
- ✅ Setări aplicație

### 💼 Specialist Demo
| Câmp | Valoare |
|------|---------|
| **Email** | specialist@dariabeauty.ro |
| **Parolă** | password |
| **Nume** | Daria Iliescu |
| **Rol** | Specialist |
| **Sub-brand** | dariaGlow - Skincare & Makeup |

**Capabilități Specialist:**
- ✅ Dashboard personal cu KPI-uri
- ✅ CRUD servicii proprii (2 servicii demo)
- ✅ Upload galerie foto (featured, before/after)
- ✅ Management programări
- ✅ Răspunsuri la review-uri
- ✅ Editare profil complet
- ✅ Social media links

**Alți specialiști demo** (password pentru toți: `password`):
1. **Radu Marin** - dariaNails (Sector 1, Dorobanti)
2. **Cristina Stoica** - dariaNails (Sector 2, Sector 3)  
3. **Alexia Barbu** - dariaHair (Sector 4, Sector 5)
4. **Bianca Pavel** - dariaGlow (Sector 1, Sector 6)
5. **Ioana Matei** - dariaHair (Sector 3, Sector 4)
6. **Elena Popescu** - dariaNails (Baneasa, Pipera)
7. **Maria Ionescu** - dariaGlow (Sector 2, Sector 4)
8. **Laura Dumitrescu** - dariaHair (Sector 5, Sector 6)

### 👤 Client Demo
Poți crea cont de client prin **Register** sau folosește:
| Câmp | Valoare |
|------|---------|
| **Email** | client@example.com |
| **Parolă** | password |
| **Rol** | Client |

**Capabilități Client:**
- ✅ Căutare și rezervare specialiști
- ✅ Istoric programări
- ✅ Adăugare review-uri
- ✅ Management cont personal

## 📸 Screenshots

### Homepage
Design modern cu gradient gold și imagini produse beauty

### Pagina Specialiști
Sistem avansat de filtrare și sortare cu toggle Grid/List view

### Panel Specialist
Dashboard complet pentru gestionarea serviciilor și programărilor

## 📁 Structură Proiect Detaliată

```
dariabeauty/
├── 📂 app/
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   ├── HomeController.php          # Homepage, servicii, galerie
│   │   │   ├── SpecialistController.php    # Profile, booking, dashboard specialist
│   │   │   ├── AppointmentController.php   # Gestionare programări
│   │   │   ├── ReviewController.php        # Sistem review-uri
│   │   │   └── 📂 Admin/                  # Controllers pentru admin panel
│   │   ├── 📂 Middleware/
│   │   │   ├── Authenticate.php           # Auth middleware
│   │   │   ├── AdminMiddleware.php        # Protecție rute admin
│   │   │   └── SpecialistMiddleware.php   # Protecție rute specialist
│   │   └── 📂 Requests/                   # Form Request Validation
│   ├── 📂 Models/
│   │   ├── User.php                       # Model utilizatori (admin, specialist, client)
│   │   ├── Service.php                    # Servicii oferite de specialiști
│   │   ├── Appointment.php                # Programări clienți
│   │   ├── Review.php                     # Review-uri și rating-uri
│   │   ├── Gallery.php                    # Galerie foto specialiști
│   │   └── SocialLink.php                 # Link-uri social media
│   └── 📂 Providers/
│       ├── AppServiceProvider.php
│       ├── AuthServiceProvider.php
│       └── RouteServiceProvider.php
│
├── 📂 database/
│   ├── 📂 migrations/                      # Migrări bază de date (6 tabele)
│   │   ├── *_create_users_table.php       # Utilizatori cu roluri
│   │   ├── *_create_services_table.php    # Servicii și prețuri
│   │   ├── *_create_appointments_table.php # Programări
│   │   ├── *_create_reviews_table.php     # Review-uri cu rating
│   │   ├── *_create_gallery_table.php     # Galerie imagini
│   │   └── *_create_social_links_table.php # Social media
│   └── 📂 seeders/
│       ├── DatabaseSeeder.php             # Seeder principal
│       ├── AdminUserSeeder.php            # Admin demo
│       ├── SpecialistUserSeeder.php       # 9 specialiști demo
│       ├── SpecialistGallerySeeder.php    # Imagini demo
│       └── SpecialistReviewSeeder.php     # Review-uri demo
│
├── 📂 resources/
│   ├── 📂 views/
│   │   ├── layout.blade.php               # Layout master (navbar, footer)
│   │   ├── home.blade.php                 # Homepage cu hero section
│   │   ├── services.blade.php             # Pagină servicii
│   │   ├── gallery.blade.php              # Galerie foto publică
│   │   ├── contact.blade.php              # Contact form
│   │   ├── booking.blade.php              # Landing programări
│   │   ├── terms.blade.php                # Termeni și condiții (GDPR)
│   │   ├── privacy.blade.php              # Politică confidențialitate
│   │   ├── cookies.blade.php              # Setări cookies
│   │   ├── 📂 specialists/                # Views publice specialiști
│   │   │   ├── index.blade.php           # Listă specialiști (grid/list)
│   │   │   ├── show.blade.php            # Profil public specialist
│   │   │   └── booking.blade.php         # Formular rezervare
│   │   ├── 📂 specialist/                 # Dashboard specialist
│   │   │   ├── dashboard.blade.php       # KPI-uri și statistici
│   │   │   ├── profile.blade.php         # Edit profil
│   │   │   ├── 📂 services/              # CRUD servicii
│   │   │   ├── 📂 gallery/               # Upload imagini
│   │   │   └── 📂 reviews/               # Management review-uri
│   │   ├── 📂 admin/                      # Panel admin
│   │   │   ├── dashboard.blade.php       # Admin dashboard
│   │   │   ├── users.blade.php           # Gestionare utilizatori
│   │   │   ├── specialists.blade.php     # Aprobare specialiști
│   │   │   └── settings.blade.php        # Setări aplicație
│   │   └── 📂 components/
│   │       ├── cookie-consent.blade.php  # Banner GDPR cookies
│   │       └── navbar.blade.php          # Componentă navbar
│   ├── 📂 css/
│   │   └── app.css                       # Stiluri custom
│   └── 📂 js/
│       └── app.js                        # JavaScript custom
│
├── 📂 routes/
│   ├── web.php                           # Toate rutele web (292 linii)
│   └── auth.php                          # Rute autentificare Laravel Breeze
│
├── 📂 public/
│   ├── index.php                         # Entry point
│   ├── 📂 images/                        # Imagini statice
│   ├── 📂 storage/                       # Symlink către storage/app/public
│   └── .htaccess                         # Configurare Apache (HTTPS, security)
│
├── 📂 storage/
│   └── 📂 app/
│       └── 📂 public/                    # Imagini uploadate
│           ├── 📂 services/              # Imagini servicii
│           ├── 📂 gallery/               # Galerie specialiști
│           └── 📂 profiles/              # Poze profil
│
├── 📂 config/                            # Configurări Laravel
│   ├── app.php
│   ├── database.php
│   ├── filesystems.php                   # Configurare storage disk
│   └── session.php
│
├── .env.example                          # Template configurare environment
├── .gitignore                            # Git ignore (vendor, node_modules, .env)
├── .cpanel.yml                           # Deploy automat cPanel
├── composer.json                         # Dependencies PHP
├── package.json                          # Dependencies Node.js
├── README.md                             # Acest fișier
├── DEPLOY.md                             # Ghid deployment Hostico
├── CREDENTIALS.md                        # Credențiale (exclus din Git)
└── LICENSE                               # Licență MIT
```

### 📊 Statistici Cod

- **Controllers**: 15+ fișiere
- **Models**: 6 modele principale
- **Migrations**: 8 migrări
- **Views**: 50+ fișiere Blade
- **Routes**: 100+ rute definite
- **Seeders**: 5 seeders cu date realiste
- **Linii de cod**: ~15,000 (PHP + Blade + CSS + JS)

## 🚀 Features Viitoare

Pentru lista completă de funcționalități planificate, consultă [PROIECT-DARIABEAUTY.md](PROIECT-DARIABEAUTY.md)

Highlights:
- 📅 Sistem booking cu calendar interactiv
- 💳 Plăți online (Stripe/PayPal)
- 📧 Notificări email/SMS
- 💬 Chat în timp real
- 📊 Analytics și rapoarte
- 📱 Mobile app (React Native)

## 🤝 Contribuție

Contribuțiile sunt binevenite! Pentru schimbări majore, te rog:

1. Fork repository-ul
2. Creează o branch nouă (`git checkout -b feature/AmazingFeature`)
3. Commit modificările (`git commit -m 'Add some AmazingFeature'`)
4. Push pe branch (`git push origin feature/AmazingFeature`)
5. Deschide un Pull Request

## 📝 Licență

Acest proiect este licențiat sub [MIT License](LICENSE)

## 📧 Contact

**DariaBeauty Team**

- Website: [dariabeauty.ro](https://dariabeauty.ro)
- Email: contact@dariabeauty.ro
- GitHub: [@nnoldi-hub](https://github.com/nnoldi-hub)

---

**Created by [conectica-it.ro](https://conectica-it.ro)**

Dezvolvat cu ❤️ și ☕ în București

⭐ Dacă îți place proiectul, dă-ne un star pe GitHub!
