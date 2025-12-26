# 📦 Fișiere pentru Deploy Salon Owner

## ✅ Checklist Upload Hostinger

### OBLIGATORIU - Migrație Database
- [ ] `database/migrations/2025_12_11_000001_add_salon_functionality.php`

### Middleware
- [ ] `app/Http/Middleware/SalonMiddleware.php`
- [ ] `app/Http/Kernel.php` ⚠️ (actualizat - adaugă 'salon' în middlewareAliases)

### Controllers
- [ ] `app/Http/Controllers/SalonReportsController.php` ⚠️ (actualizat complet)
- [ ] `app/Http/Controllers/SalonSpecialistsController.php` 🆕 (nou)

### Routes
- [ ] `routes/web.php` ⚠️ (actualizat - adaugă rute salon + SalonSpecialistsController import)

### Views - Salon Reports
- [ ] `resources/views/salon/reports/index.blade.php` ⚠️ (actualizat cu features salon owner)
- [ ] `resources/views/salon/reports/specialist-detail.blade.php`

### Views - Salon Specialists (NOU)
- [ ] `resources/views/salon/specialists/index.blade.php` 🆕

### Views - Sidebar
- [ ] `resources/views/salon/partials/sidebar.blade.php` ⚠️ (actualizat - link specialiști)
- [ ] `resources/views/specialist/partials/sidebar.blade.php` ⚠️ (actualizat - link rapoarte)

### Views - Email
- [ ] `resources/views/emails/salon-invitation.blade.php` 🆕

---

## 🔧 Comenzi SSH după upload:

```bash
cd public_html
php artisan migrate
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## 👤 Creează Salon Owner Test:

### Opțiunea 1: PhpMyAdmin
```sql
-- Setează un specialist existent ca salon owner
UPDATE users 
SET is_salon_owner = 1 
WHERE id = 1;

-- SAU creează un user nou cu rol salon
INSERT INTO users (name, email, password, role, is_active, is_salon_owner, created_at, updated_at)
VALUES ('Salon DariaBeauty', 'salon@dariabeauty.ro', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'salon', 1, 1, NOW(), NOW());
-- Password-ul este: password
```

### Opțiunea 2: SSH Tinker
```bash
php artisan tinker
```
```php
// Setează specialist existent
$user = User::find(1);
$user->is_salon_owner = true;
$user->save();

// SAU creează user nou
User::create([
    'name' => 'Salon DariaBeauty',
    'email' => 'salon@dariabeauty.ro',
    'password' => Hash::make('password123'),
    'role' => 'salon',
    'is_active' => true,
    'is_salon_owner' => true
]);

exit
```

## 🧪 Testare:

1. **Login** cu user-ul salon owner
2. **Verifică sidebar** - ar trebui să vezi "Specialiștii Mei"
3. **Accesează** `/salon/specialisti` - pagină gestionare specialiști
4. **Accesează** `/salon/rapoarte` - rapoarte cu info "Vizualizare salon"
5. **Testează invitație** - click "Invită Specialist", introdu email, verifică email-ul

## ⚠️ Troubleshooting:

### Eroare "Class SalonMiddleware not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Eroare "Route not found"
```bash
php artisan route:clear
php artisan route:list | grep salon
```

### Eroare la migrație
```bash
php artisan migrate:status
php artisan migrate --force
```

### Email-uri nu se trimit
Verifică configurația SMTP în `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your-email@dariabeauty.ro
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@dariabeauty.ro
MAIL_FROM_NAME="DariaBeauty"
```

## 📊 Verificare Structură Database:

După migrate, verifică în phpMyAdmin:

**Tabelul `users` ar trebui să aibă:**
- Coloana `role` cu valori: 'client', 'specialist', 'salon', 'superadmin'
- Coloana `salon_id` (nullable, foreign key)
- Coloana `is_salon_owner` (boolean, default 0)
- Coloana `salon_description` (text, nullable)
- Coloana `salon_logo` (varchar, nullable)
- Coloana `salon_specialists_count` (integer, default 0)

## 🎯 Features de testat:

- [ ] Login ca salon owner
- [ ] Vezi link "Specialiștii Mei" în sidebar
- [ ] Accesează pagina specialiști
- [ ] Click "Invită Specialist"
- [ ] Completează form invitație
- [ ] Verifică primirea email-ului
- [ ] Login ca specialist și acceptă invitația
- [ ] Înapoi la salon owner - vezi specialistul în listă
- [ ] Click "Vezi raport" pe specialist
- [ ] Accesează "Rapoarte & Statistici" - vezi date combinate
- [ ] Testează export CSV
- [ ] Testează eliminare specialist din salon

---

✅ **Gata! Toate fișierele sunt pregătite pentru deploy.**
