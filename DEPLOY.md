# 🚀 Ghid Deploy DariaBeauty pe Hostico (cPanel)

## 📋 Cerințe Minime Hosting

- **PHP**: 8.1 sau superior
- **MySQL**: 8.0+
- **Extensii PHP**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo
- **Composer**: Instalat pe server
- **Git**: Pentru deployment automat
- **Spațiu disk**: Minimum 500 MB

---

## 🔧 Pași de Configurare pe Hostico

### 1️⃣ Pregătire cPanel

1. **Creează Baza de Date MySQL**
   - Accesează **MySQL Database Wizard** din cPanel
   - Creează o bază nouă: `dariabeauty_db`
   - Creează user: `dariabeauty_user`
   - Setează parolă puternică
   - Acordă **ALL PRIVILEGES**

2. **Activează SSH Access** (opțional, recomandat)
   - Contact support Hostico pentru activare SSH
   - Generează SSH key dacă e nevoie

3. **Verifică Versiunea PHP**
   - MultiPHP Manager → Selectează **PHP 8.1** sau **8.2**
   - Activează extensiile necesare

---

### 2️⃣ Deployment prin Git (Recomandat)

#### A. Configurare Git Deployment în cPanel

1. Accesează **Git™ Version Control** din cPanel
2. Click **Create** pentru repository nou
3. Completează:
   - **Clone URL**: `https://github.com/nnoldi-hub/DariaBeauty.git`
   - **Repository Path**: `/home/USERNAME/repositories/DariaBeauty`
   - **Repository Name**: `DariaBeauty`
4. Click **Create**

#### B. Configurare Deployment Path

1. După clonare, click **Manage** pe repository
2. În secțiunea **Deployment**, setează:
   - **Deployment Path**: `/home/USERNAME/public_html`
   - Bifează **Enable Automatic Deployment**
3. Click **Update**

#### C. Deploy Manual (prima dată)

1. Click **Pull or Deploy** → **Deploy HEAD Commit**
2. Așteaptă finalizarea (verifică logs)

---

### 3️⃣ Configurare Post-Deploy

#### A. Editare `.env`

1. Conectează-te prin **File Manager** sau **SSH**
2. Navighează la `/home/USERNAME/public_html`
3. Copiază `.env.example` → `.env`:
   ```bash
   cp .env.example .env
   ```

4. Editează `.env` cu datele tale:
   ```env
   APP_NAME=DariaBeauty
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://dariabeauty.ro

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=dariabeauty_db
   DB_USERNAME=dariabeauty_user
   DB_PASSWORD=your_secure_password

   MAIL_MAILER=smtp
   MAIL_HOST=mail.dariabeauty.ro
   MAIL_PORT=587
   MAIL_USERNAME=noreply@dariabeauty.ro
   MAIL_PASSWORD=your_email_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@dariabeauty.ro
   ```

#### B. Rulare Comenzi Laravel (prin SSH)

```bash
# Navighează la directorul proiectului
cd /home/USERNAME/public_html

# Instalează dependencies
composer install --no-dev --optimize-autoloader

# Generează Application Key
php artisan key:generate

# Rulează migrările
php artisan migrate --force

# Populează baza de date
php artisan db:seed --force

# Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Creează symbolic link pentru storage
php artisan storage:link

# Setează permisiuni
chmod -R 775 storage bootstrap/cache
```

---

### 4️⃣ Configurare Document Root

**IMPORTANT**: Laravel folosește `/public` ca document root!

#### Opțiunea A: Modificare Document Root (Recomandat)

1. **cPanel → Domains → Domains**
2. Click **Manage** pe domeniul tău
3. Schimbă **Document Root** la:
   ```
   /home/USERNAME/public_html/public
   ```
4. Click **Change**

#### Opțiunea B: .htaccess Redirect (Alternativă)

Dacă nu poți modifica Document Root, creează `.htaccess` în root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### 5️⃣ Configurare SSL (HTTPS)

1. **cPanel → SSL/TLS Status**
2. Bifează domeniul tău
3. Click **Run AutoSSL** (pentru Let's Encrypt gratuit)
4. Așteaptă 5-10 minute pentru activare
5. Verifică: `https://dariabeauty.ro`

---

### 6️⃣ Setare Cron Job pentru Scheduler (Opțional)

Dacă folosești Laravel Scheduler pentru task-uri automate:

1. **cPanel → Cron Jobs**
2. Adaugă job nou:
   - **Minute**: `*`
   - **Hour**: `*`
   - **Day**: `*`
   - **Month**: `*`
   - **Weekday**: `*`
   - **Command**:
     ```bash
     cd /home/USERNAME/public_html && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
     ```

---

## 🔄 Update-uri Viitoare (Deploy Automat)

După configurare inițială, pentru update-uri:

### Metoda 1: Git Deploy din cPanel
1. Faci push pe GitHub cu modificările
2. Accesezi **Git™ Version Control** în cPanel
3. Click **Manage** pe repository
4. Click **Pull or Deploy** → **Deploy HEAD Commit**

### Metoda 2: SSH (mai rapid)
```bash
cd /home/USERNAME/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ✅ Verificare Finală

După deploy, verifică:

1. **Homepage**: `https://dariabeauty.ro` ✅
2. **Login Admin**: `https://dariabeauty.ro/login`
   - Email: `admin@dariabeauty.ro`
   - Parola: `password` (schimbă-o imediat!)
3. **Specialist Panel**: `https://dariabeauty.ro/specialist`
4. **Public Pages**: Servicii, Galerie, Contact, etc.

---

## 🐛 Troubleshooting

### Eroare: "500 Internal Server Error"

```bash
# Verifică permisiuni
chmod -R 775 storage bootstrap/cache

# Verifică logs
tail -n 50 storage/logs/laravel.log
```

### Eroare: "SQLSTATE[HY000] [2002] Connection refused"

- Verifică credențiale DB în `.env`
- Testează conexiunea MySQL din cPanel → phpMyAdmin

### Assets nu se încarcă (CSS/JS/Images)

```bash
# Regenerează cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Verifică symbolic link
php artisan storage:link
```

### Permisiuni Denied

```bash
# Setează owner corect (înlocuiește USERNAME)
chown -R USERNAME:USERNAME /home/USERNAME/public_html
chmod -R 755 /home/USERNAME/public_html
chmod -R 775 storage bootstrap/cache
```

---

## 📧 Contact Support Hostico

Dacă întâmpini probleme:
- **Email**: support@hostico.ro
- **Telefon**: +40 XXX XXX XXX
- **LiveChat**: din contul cPanel

---

## 🎉 Post-Deploy Tasks

După deploy reușit:

1. ✅ Schimbă parola admin din `/admin/settings`
2. ✅ Configurează SMTP pentru email-uri
3. ✅ Testează formularele (contact, booking)
4. ✅ Verifică toate paginile publice
5. ✅ Testează panel-ul specialist
6. ✅ Adaugă logo și imagini reale
7. ✅ Configurează Google Analytics (opțional)
8. ✅ Submit sitemap la Google Search Console

---

**Succes cu deployment-ul! 🚀**

*Created by [conectica-it.ro](https://conectica-it.ro)*
