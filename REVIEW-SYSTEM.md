# ⭐ Sistem Review-uri DariaBeauty

## 📋 Prezentare Generală

Sistemul de review-uri DariaBeauty este conceput pentru a asigura **autenticitate și încredere** între clienți și specialiști. Review-urile pot fi lăsate DOAR de clienți care au avut efectiv o programare finalizată cu specialistul respectiv.

---

## 🔄 Fluxul Complet al Review-urilor

```
1. CLIENT FACE PROGRAMARE
   ↓
   Status: pending
   
2. SPECIALIST CONFIRMĂ
   ↓
   Status: confirmed
   
3. SERVICIU PRESTAT
   ↓
   Status: completed
   
4. CLIENT POATE LĂSA REVIEW
   ↓
   Review cu rating 1-5 ⭐
   
5. REVIEW APARE PE PROFIL
   ↓
   Vizibil public
   
6. SPECIALIST POATE RĂSPUNDE
   ↓
   Interacțiune pozitivă
```

---

## 🎯 Unde și Cum se Lasă Review-uri

### 1️⃣ **Din Profilul Specialistului** (NOU!)

**Locație**: `/specialisti/{slug}`

Dacă ești autentificat și ai programări finalizate cu acel specialist:

```php
[Buton: "Lasă Review" ▼]
  ↓ Dropdown cu lista programărilor tale completate
  ↓ Click pe serviciu → formular review
```

**Caracteristici:**
- ✅ Dropdown cu toate programările finalizate (fără review)
- ✅ Afișează serviciul și data programării
- ✅ Un click → formular review
- ✅ Vizibil DOAR pentru clienți autentificați cu programări completed

### 2️⃣ **Din Dashboard Client**

**Locație**: `/dashboard` sau `/programari`

În lista de programări, după ce serviciul e finalizat:

```php
[Card Programare]
  Status: Completat ✓
  [Buton: "Lasă Review"]
```

### 3️⃣ **Din Email Post-Serviciu** (Planned)

După 24h de la finalizarea serviciului:

```
Subiect: Cum a fost experiența ta cu {Specialist}?

"Salut {Client},

Sperăm că ai fost mulțumit de serviciul de {Service}.
Ne-ar ajuta foarte mult părerea ta!

[Lasă un Review] ← Link direct către formular
```

---

## 📝 Formular Review - Câmpuri

**Ruta**: `/reviews/creeaza/{appointment_id}`

### Câmpuri Obligatorii:
- ⭐ **Rating**: 1-5 stele (required)
- 💬 **Comentariu**: Text liber (optional, dar recomandat)

### Câmpuri Auto-Populate:
- 👤 **Nume Client**: Din cont sau din programare
- 📧 **Email**: Pentru notificări
- 📅 **Data Serviciului**: Din programare
- 💅 **Serviciu**: Din programare
- 👨‍💼 **Specialist**: Din programare

### Validări:
```php
'rating' => 'required|integer|min:1|max:5',
'comment' => 'nullable|string|max:1000',
'appointment_id' => 'required|exists:appointments,id',
```

---

## 🔒 Reguli și Restricții

### ✅ Poți lăsa review DOAR dacă:
1. ✓ Ești autentificat ca **client**
2. ✓ Ai avut o programare cu specialistul
3. ✓ Programarea are status **"completed"**
4. ✓ Nu ai lăsat deja review pentru acea programare
5. ✓ Serviciul s-a finalizat (data programării < azi)

### ❌ NU poți lăsa review dacă:
1. ✗ Nu ești autentificat
2. ✗ Programarea e încă **"pending"** sau **"confirmed"**
3. ✗ Programarea a fost **"cancelled"**
4. ✗ Ai deja un review pentru acea programare
5. ✗ Ești specialist sau admin (nu client)

### 🔐 Protecții Implementate:

```php
// Middleware
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews/{appointment}', [ReviewController::class, 'store']);
});

// Controller - Verificări
public function store(Request $request, $appointmentId) {
    $appointment = Appointment::findOrFail($appointmentId);
    
    // 1. Verifică ownership
    if ($appointment->user_id !== auth()->id()) {
        abort(403, 'Nu poți lăsa review pentru programări care nu îți aparțin');
    }
    
    // 2. Verifică status
    if ($appointment->status !== 'completed') {
        abort(403, 'Poți lăsa review doar după finalizarea serviciului');
    }
    
    // 3. Verifică duplicat
    if ($appointment->review()->exists()) {
        abort(403, 'Ai lăsat deja un review pentru această programare');
    }
    
    // OK - creează review
}
```

---

## 🎨 Afișare Review-uri pe Profil

### Card Review cu Toate Detaliile:

```html
┌──────────────────────────────────────────┐
│ 👤 Nume Client [✓ Verificat]           │
│ ⭐⭐⭐⭐⭐ 5.0                         │
│                          ⏰ 2 zile în urmă│
├──────────────────────────────────────────┤
│ "Serviciu impecabil! Recomand cu încredere│
│  pentru machiaj de seară. Daria a fost    │
│  foarte profesionistă."                    │
│                                            │
│ 💅 Machiaj seara                          │
│                                            │
│ ┌────────────────────────────────────┐   │
│ │ 💬 Răspuns de la Daria Iliescu     │   │
│ │ "Mulțumesc mult pentru apreciere!  │   │
│ │  A fost o plăcere să lucrez cu tine"│   │
│ └────────────────────────────────────┘   │
└──────────────────────────────────────────┘
```

### Elemente Vizuale:

1. **Badge Verificat** (✓): Apare dacă review-ul e de la user autentificat
2. **Rating Vizual**: Stele colorate (⭐) + scor numeric (5.0)
3. **Timestamp**: "2 zile în urmă" (diffForHumans)
4. **Serviciu Tag**: Numele serviciului pentru context
5. **Răspuns Specialist**: Box indented cu fundal gri
6. **Avatare**: Poze profil (viitor)

---

## 👨‍💼 Funcționalități Specialist

### Răspuns la Review-uri

**Ruta**: `/specialist/reviews/{review}/raspunde`

Specialistul poate răspunde la fiecare review pentru:
- ✅ Mulțumi clienților pentru feedback pozitiv
- ✅ Rezolva probleme menționate în review-uri negative
- ✅ Arăta profesionalism și grija față de clienți

**Limitări:**
- Un singur răspuns per review
- Maxim 500 caractere
- Nu poate șterge review-ul (doar admin)

### Dashboard Reviews

**Ruta**: `/specialist/reviews`

Vizualizare toate review-urile primite cu:
- 📊 Statistici: Medie rating, distribuție 1-5 stele
- 🔍 Filtrare: După rating, dată, serviciu
- 📥 Export: CSV cu toate review-urile
- 💬 Răspunsuri rapide la review-uri noi

---

## 👨‍💼 Funcționalități Admin

### Moderare Review-uri

**Ruta**: `/admin/reviews`

Admin poate:
- ✅ Aproba/Respinge review-uri (dacă e activată moderarea)
- ✅ Șterge review-uri spam/offensive
- ✅ Vedea rapoarte de la utilizatori
- ✅ Exporta toate review-urile platformei

### Setări Sistem Review

**Ruta**: `/admin/settings`

```php
'reviews' => [
    'require_moderation' => false,  // Auto-approve sau moderare manuală
    'min_rating_to_show' => 1,      // Rating minim pentru afișare
    'allow_anonymous' => false,      // Review-uri doar de la conturi
    'edit_time_limit' => 24,        // Ore în care poți edita review-ul
]
```

---

## 📊 Statistici și Metrici

### Pe Profilul Specialist:

```
⭐ 4.8 din 5 (127 review-uri)

Distribuție:
5 ⭐ ████████████████████ 85%
4 ⭐ ███░░░░░░░░░░░░░░░░░ 10%
3 ⭐ █░░░░░░░░░░░░░░░░░░░  3%
2 ⭐ ░░░░░░░░░░░░░░░░░░░░  1%
1 ⭐ ░░░░░░░░░░░░░░░░░░░░  1%
```

### În Dashboard Specialist:

- 📈 **Evoluție rating** în timp (grafic)
- 🔝 **Top servicii** cu cele mai multe review-uri
- 📝 **Review-uri recente** (ultimele 10)
- ⏰ **Timp mediu de răspuns** la review-uri

---

## 🚀 Features Viitoare (Roadmap)

### V2.0 - Review System Enhanced
- [ ] **Imagini în review-uri**: Clienții pot atașa before/after
- [ ] **Review Questions**: Template întrebări pentru ghidare
- [ ] **Verified Badges**: Badge special pentru clienți recurenți
- [ ] **Review Reminders**: Email automat după 48h de la serviciu
- [ ] **Review Rewards**: Puncte/discount pentru review-uri detaliate

### V3.0 - Social Integration
- [ ] **Share Review**: Partajare pe Facebook/Instagram
- [ ] **Review Gallery**: Galerie cu poze din review-uri
- [ ] **Video Reviews**: Suport testimoniale video
- [ ] **Review Widget**: Embed reviews pe website-uri externe

---

## 🐛 Troubleshooting

### Problema: "Nu văd butonul de review"

**Soluții:**
1. ✓ Verifică dacă ești autentificat
2. ✓ Asigură-te că ai o programare **completed**
3. ✓ Verifică dacă nu ai lăsat deja review
4. ✓ Refresh pagina (Ctrl + F5)

### Problema: "403 Forbidden la submit review"

**Cauze posibile:**
- Programarea nu îți aparține
- Status programare != completed
- Ai deja un review pentru acea programare
- Token CSRF expirat (reîncarcă pagina)

### Problema: "Review-ul nu apare"

**Verificări:**
1. Dacă moderarea e activată → așteaptă aprobare admin
2. Check în dashboard client → "Review-uri în așteptare"
3. Verifică logs: `storage/logs/laravel.log`

---

## 📞 Support

Pentru întrebări despre sistemul de review-uri:

- 📧 Email: support@dariabeauty.ro
- 💬 Chat: Din dashboard după autentificare
- 📚 Docs: [docs.dariabeauty.ro/reviews](https://docs.dariabeauty.ro/reviews)

---

**Ultima actualizare**: 4 decembrie 2025  
**Versiune**: 1.0.0  
**Autor**: DariaBeauty Development Team
