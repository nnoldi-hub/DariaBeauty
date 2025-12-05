# 📱 Integrare SMS cu Twilio - Documentație

## 🎯 Prezentare Generală

Sistemul de notificări SMS automate folosind Twilio pentru DariaBeauty.

## ✨ Funcționalități Implementate

### 1. **Notificări Automate pentru Clienți**
- ✅ Confirmare programare (imediat după rezervare)
- ✅ Reminder 24h înainte de programare
- ✅ Notificare anulare/modificare programare
- ✅ Cod de verificare pentru înregistrare (2FA)
- ✅ Cod resetare parolă

### 2. **Notificări pentru Specialiști**
- ✅ Programare nouă primită
- ✅ Anulare de către client
- ✅ Review nou primit

### 3. **Sistem de Management**
- ✅ Panou admin pentru configurare Twilio
- ✅ Test SMS direct din admin
- ✅ Istoric complet SMS-uri trimise
- ✅ Statistici (total trimise, eșuate, astăzi, luna curentă)
- ✅ Rate limiting (max 10 SMS/user/zi, max 3 coduri verificare/oră)

### 4. **Logging & Monitoring**
- ✅ Tabel `sms_logs` cu toate SMS-urile
- ✅ Status tracking (sent/failed/pending)
- ✅ Error messages pentru debugging
- ✅ Relații cu users și appointments

## 🚀 Setup & Configurare

### Pas 1: Cont Twilio

1. Creează cont pe [Twilio.com](https://www.twilio.com/try-twilio)
2. Verifică numărul tău de telefon
3. Obține credențialele:
   - **Account SID**: găsit în Console Dashboard
   - **Auth Token**: găsit în Console Dashboard
   - **Phone Number**: cumpără un număr sau folosește cel de trial

### Pas 2: Configurare în Aplicație

1. **Accesează Admin Panel**:
   ```
   https://dariabeauty.ro/admin/sms
   ```

2. **Completează formularul**:
   - Twilio Account SID: `AC...`
   - Auth Token: `...`
   - Phone Number: `+40XXXXXXXXX`
   - Bifează "Activează Serviciul SMS"

3. **Salvează Configurația**

4. **Testează**:
   - Introdu numărul tău de telefon
   - Scrie un mesaj test
   - Click "Trimite SMS Test"

### Pas 3: Programare Taskuri

Pentru reminder-uri automate, adaugă în cron (pe server):

```bash
# Adaugă în crontab
* * * * * cd /path/to/dariabeauty && php artisan schedule:run >> /dev/null 2>&1
```

Sau rulează manual din admin: **"Trimite Reminder-uri Acum"**

## 📝 Template-uri Mesaje

Template-urile sunt configurabile în `config/twilio.php`:

```php
'templates' => [
    'appointment_confirmed' => 'Buna {name}! Programarea ta la {service} pe {date} la {time} a fost confirmata. DariaBeauty',
    'appointment_reminder' => 'Reminder: Ai programare la {service} maine la {time}. Te asteptam! DariaBeauty',
    'appointment_cancelled' => 'Programarea ta din {date} la {time} a fost anulata. DariaBeauty',
    'new_appointment_specialist' => 'Programare noua: {client} pentru {service} pe {date} la {time}. DariaBeauty',
    'verification_code' => 'Codul tau de verificare DariaBeauty: {code}. Valabil 10 minute.',
    'password_reset' => 'Cod resetare parola DariaBeauty: {code}. Valabil 15 minute.',
]
```

## 💻 Utilizare în Cod

### Trimitere SMS Programare

```php
use App\Services\SmsService;

$smsService = app(SmsService::class);

// Confirmare programare
$smsService->sendAppointmentConfirmation($appointment);

// Reminder
$smsService->sendAppointmentReminder($appointment);

// Anulare
$smsService->sendAppointmentCancellation($appointment);

// Notificare specialist
$smsService->notifySpecialistNewAppointment($appointment, $specialist);
```

### Trimitere SMS Generic

```php
$smsService = app(SmsService::class);
$smsService->send('+40712345678', 'Mesajul tau aici');
```

### Cod Verificare

```php
$code = rand(100000, 999999);
$smsService->sendVerificationCode($phone, $code);
```

## 🗄️ Structură Bază de Date

### Tabel: `sms_logs`

| Coloană | Tip | Descriere |
|---------|-----|-----------|
| id | bigint | Primary key |
| to | string | Număr telefon destinatar |
| message | text | Conținutul mesajului |
| type | enum | Tipul SMS-ului |
| status | enum | sent/failed/pending |
| error_message | text | Mesaj eroare (dacă eșuat) |
| user_id | bigint | FK la users (nullable) |
| appointment_id | bigint | FK la appointments (nullable) |
| created_at | timestamp | Data trimiterii |

### Coloane Noi în `users`

| Coloană | Tip | Descriere |
|---------|-----|-----------|
| phone_verified_at | timestamp | Data verificării telefonului |
| sms_notifications | boolean | Permite notificări SMS |
| sms_reminders | boolean | Permite reminder-uri SMS |
| sms_marketing | boolean | Permite SMS marketing |

## 📊 Costuri Estimate

**Twilio Pricing (România):**
- SMS: ~$0.015 per mesaj (€0.014)

**Estimări lunare:**
- 100 programări × 3 SMS = 300 SMS = **~$4.5/lună**
- 200 programări × 3 SMS = 600 SMS = **~$9/lună**
- 500 programări × 3 SMS = 1500 SMS = **~$22.5/lună**

## 🔒 Securitate & Rate Limiting

### Rate Limits Implementate:

1. **Per User**: Max 10 SMS/zi
2. **Coduri Verificare**: Max 3/oră per număr
3. **Cache-based**: Resetare la sfârșitul zilei

### Validări:

- Normalizare număr telefon la format E.164 (+40...)
- Check dacă serviciul este enabled
- Logging complet pentru audit

## 🛠️ Comenzi Artisan

### Trimitere Reminder-uri Manual

```bash
php artisan appointments:send-reminders
```

### Clear Config Cache (după modificări .env)

```bash
php artisan config:clear
```

## 📈 Monitoring & Debug

### Verificare Log-uri

1. **Admin Panel**: `/admin/sms` - Istoric SMS recent
2. **Laravel Logs**: `storage/logs/laravel.log`
3. **Twilio Console**: [console.twilio.com/monitor/logs](https://console.twilio.com/monitor/logs)

### Troubleshooting Comun

**SMS nu se trimit:**
- ✓ Verifică că `TWILIO_ENABLED=true` în `.env`
- ✓ Verifică credențialele Twilio
- ✓ Verifică numărul de telefon este în format +40...
- ✓ Verifică rate limits nu au fost atinse
- ✓ Verifică log-urile pentru erori

**Eroare "Invalid Phone Number":**
- Numărul trebuie să fie în format E.164: `+40712345678`
- Service-ul normalizează automat 0712... în +40712...

## 🎓 Best Practices

1. **Testare**: Folosește SMS de test din admin înainte de production
2. **Trial Mode**: Twilio trial poate trimite doar la numere verificate
3. **Compliance**: Respectă GDPR - users pot opta-out din SMS
4. **Timing**: Reminder-uri la 10:00 AM (configurabil în Kernel.php)
5. **Mesaje**: Max 160 caractere pentru 1 segment SMS (mai ieftin)

## 📚 Resurse

- [Twilio PHP SDK Docs](https://www.twilio.com/docs/libraries/php)
- [Twilio Console](https://console.twilio.com/)
- [Pricing Calculator](https://www.twilio.com/pricing)
- [SMS Best Practices](https://www.twilio.com/docs/sms/tutorials/best-practices-for-sms)

## 🆘 Support

Pentru probleme:
1. Verifică log-urile Laravel: `storage/logs/laravel.log`
2. Verifică Twilio logs în Console
3. Testează cu numărul tău verificat
4. Contactează Twilio Support dacă persistă

---

**Status**: ✅ Functional
**Versiune**: 1.0
**Data**: Decembrie 2025
