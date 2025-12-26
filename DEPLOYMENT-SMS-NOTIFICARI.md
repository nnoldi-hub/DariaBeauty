# 🚀 Deployment - Notificări SMS Suplimentare

## 📋 Ce am implementat

Am adăugat două notificări SMS noi:
1. **Notificare client după finalizare** - cu link pentru review
2. **Notificare specialist la review primit** - cu nota și link

Notificarea reminder 24h era deja implementată și funcționează.

## 🔧 Pași pentru Deployment

### 1. Backup baza de date (IMPORTANT!)

```bash
# Pe server
cd /path/to/dariabeauty
php artisan db:backup  # sau
mysqldump -u user -p dariabeauty > backup_$(date +%Y%m%d).sql
```

### 2. Pull ultimele modificări

```bash
git pull origin main
```

### 3. Instalează dependențele (dacă e necesar)

```bash
composer install --no-dev --optimize-autoloader
```

### 4. Rulează migrația

```bash
php artisan migrate
```

Aceasta adaugă coloana `completed_at` în tabelul `appointments`.

### 5. Clear cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 6. Optimizează pentru producție

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Verifică configurarea Twilio

Accesează: `https://dariabeauty.ro/admin/sms`

- [ ] Verifică că serviciul SMS este activat
- [ ] Testează trimiterea unui SMS
- [ ] Verifică istoricul SMS-urilor

### 8. Verifică cron job

Asigură-te că cron job-ul pentru reminders rulează:

```bash
crontab -e
```

Trebuie să existe:
```
* * * * * cd /path/to/dariabeauty && php artisan schedule:run >> /dev/null 2>&1
```

Sau poți rula manual din admin panel: **"Trimite Reminder-uri Acum"**

## ✅ Testare

### Test 1: Notificare review după finalizare

1. **Creează o programare de test**
   - Logare ca specialist
   - Marchează o programare ca "finalizată"
   
2. **Verifică SMS-ul**
   - Clientul ar trebui să primească SMS cu link review
   - Verifică în `Admin → SMS → Istoric` pentru status

3. **Verifică logging**
   ```sql
   SELECT * FROM sms_logs 
   WHERE type = 'appointment_completed' 
   ORDER BY created_at DESC LIMIT 5;
   ```

### Test 2: Notificare specialist la review

1. **Lasă un review pe o programare finalizată**
   - Accesează link-ul de review din SMS
   - Completează formular cu rating și comentariu
   
2. **Verifică notificare specialist**
   - Specialistul ar trebui să primească SMS cu nota
   - Verifică în `Admin → SMS → Istoric`

3. **Verifică logging**
   ```sql
   SELECT * FROM sms_logs 
   WHERE type = 'specialist_review_received' 
   ORDER BY created_at DESC LIMIT 5;
   ```

### Test 3: Reminder 24h (existent)

1. **Creează programare pentru mâine**
   
2. **Rulează command manual**
   ```bash
   php artisan appointments:send-reminders
   ```
   
3. **Verifică output-ul**
   - Ar trebui să trimită SMS-uri pentru programările de mâine
   - Verifică logs

## 📊 Monitoring

### Verifică SMS-uri trimise

```bash
# În terminal pe server
php artisan tinker

# Apoi în tinker:
\App\Models\SmsLog::whereDate('created_at', today())->count();
\App\Models\SmsLog::where('status', 'failed')->count();
\App\Models\SmsLog::where('type', 'appointment_completed')->count();
```

### Verifică în Admin Panel

`https://dariabeauty.ro/admin/sms`

- Total SMS trimise
- SMS eșuate
- Statistici pe tip
- Istoric complet

## 🐛 Troubleshooting

### SMS-urile nu se trimit

1. **Verifică configurarea Twilio**
   ```bash
   php artisan tinker
   config('twilio.enabled')  # trebuie să fie true
   config('twilio.sid')      # trebuie să fie setat
   ```

2. **Verifică logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Test SMS direct**
   - Accesează Admin → SMS
   - Trimite un SMS de test

### Reminder-urile nu se trimit automat

1. **Verifică cron job**
   ```bash
   crontab -l
   ```

2. **Rulează manual pentru test**
   ```bash
   php artisan appointments:send-reminders
   ```

3. **Verifică logs Laravel**
   ```bash
   grep "send-reminders" storage/logs/laravel.log
   ```

### Notificarea review nu funcționează

1. **Verifică că programarea are review_token**
   ```sql
   SELECT id, review_token FROM appointments WHERE status = 'completed';
   ```

2. **Verifică că se generează token**
   - Token-ul se generează automat la finalizare
   - Verifică logs când se finalizează programarea

3. **Verifică template-ul**
   ```bash
   php artisan tinker
   config('twilio.templates.appointment_completed')
   ```

## 📝 Fișiere Modificate

```
dariabeauty/
├── config/
│   └── twilio.php (template-uri noi)
├── app/
│   ├── Services/
│   │   └── SmsService.php (metode noi + logging)
│   ├── Http/Controllers/
│   │   ├── AppointmentController.php (integrare review SMS)
│   │   └── ReviewController.php (notificare specialist)
│   └── Models/
│       └── Appointment.php (completed_at + relație smsLogs)
├── database/migrations/
│   └── 2024_12_10_000001_add_completed_at_to_appointments_table.php
└── SMS-NOTIFICARI-SUPLIMENTARE.md (documentație nouă)
```

## 🔄 Rollback (dacă e necesar)

Dacă apar probleme, poți face rollback:

```bash
# Rollback migrație
php artisan migrate:rollback --step=1

# Restore backup
mysql -u user -p dariabeauty < backup_YYYYMMDD.sql

# Revert la commit anterior
git revert HEAD
```

## 📞 Contact pentru Support

Pentru probleme:
1. Verifică `storage/logs/laravel.log`
2. Verifică Twilio Console pentru detalii SMS
3. Verifică Admin Panel → SMS pentru statistici

---

**Data deployment:** 10 Decembrie 2024
**Status:** ✅ Ready for Production
**Testat:** ✅ Local Environment
