# 🌟 DariaBeauty - Platform de Servicii Beauty la Domiciliu

![DariaBeauty Logo](public/images/hero-beauty.jpg)

## 📋 Despre Proiect

**DariaBeauty** este o platformă web modernă pentru conectarea specialiștilor în frumusețe cu clienții din București. Oferim servicii profesionale de manichiură, coafură și skincare direct la domiciliul tău.

### 🎯 Sub-branduri

- **💅 dariaNails** - Manichiură & Pedichiură premium
- **✂️ dariaHair** - Coafură & Styling profesional
- **✨ dariaGlow** - Skincare & Makeup de calitate

## ✨ Features Principale

✅ **Sistem complet de autentificare** (Admin, Specialist, Client)  
✅ **Panel Admin** - Gestionare utilizatori, servicii, programări  
✅ **Panel Specialist** - Management servicii, galerie, review-uri  
✅ **Design modern și responsive** pe toate paginile  
✅ **Sistem de review-uri** cu rating și răspunsuri  
✅ **Căutare și filtrare avansată** specialiști  
✅ **Galerie foto** organizată pe sub-branduri  
✅ **Profile specialiști** cu informații detaliate  

## 🛠️ Stack Tehnologic

- **Backend**: Laravel 9+ (PHP 8.1)
- **Database**: MySQL 8.0
- **Frontend**: Blade Templates, Bootstrap 5.3
- **Icons**: Font Awesome 6
- **Authentication**: Laravel Breeze

## 📦 Instalare

### Cerințe

- PHP 8.1 sau superior
- Composer
- MySQL 8.0+
- Node.js & npm

### Pași de instalare

1. **Clonează repository-ul**
   ```bash
   git clone https://github.com/nnoldi-hub/DariaBeauty.git
   cd DariaBeauty
   ```

2. **Instalează dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Configurează environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurează baza de date în `.env`**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=dariabeauty
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Rulează migrările și seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Creează symbolic link pentru storage**
   ```bash
   php artisan storage:link
   ```

7. **Pornește serverul**
   ```bash
   php artisan serve
   ```

8. **Accesează aplicația**
   - Homepage: http://127.0.0.1:8000
   - Admin Panel: http://127.0.0.1:8000/admin
   - Specialist Panel: http://127.0.0.1:8000/specialist

## 👤 Conturi Demo

După rularea seeders, poți folosi:

### Admin
- **Email**: admin@dariabeauty.ro
- **Parola**: password

### Specialist
- **Email**: specialist@dariabeauty.ro
- **Parola**: password

## 📸 Screenshots

### Homepage
Design modern cu gradient gold și imagini produse beauty

### Pagina Specialiști
Sistem avansat de filtrare și sortare cu toggle Grid/List view

### Panel Specialist
Dashboard complet pentru gestionarea serviciilor și programărilor

## 📁 Structură Proiect

```
dariabeauty/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php
│   │   ├── SpecialistController.php
│   │   └── Admin/
│   └── Models/
│       ├── User.php
│       ├── Service.php
│       ├── Appointment.php
│       └── Review.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── home.blade.php
│       ├── specialists/
│       ├── specialist/
│       └── admin/
└── routes/
    └── web.php
```

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
