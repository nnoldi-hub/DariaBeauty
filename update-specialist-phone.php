<?php
/**
 * Script pentru actualizare telefon specialist
 * Rulează: php update-specialist-phone.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n=== ACTUALIZARE TELEFON SPECIALIST ===\n\n";

// Găsește specialistul Nyikora Noldi
$specialist = \App\Models\User::where('email', 'conectica.it.ro@gmail.com')
                               ->where('role', 'specialist')
                               ->first();

if (!$specialist) {
    echo "❌ Specialistul nu a fost găsit!\n\n";
    exit(1);
}

echo "Specialist găsit:\n";
echo "  ID: {$specialist->id}\n";
echo "  Nume: {$specialist->name}\n";
echo "  Email: {$specialist->email}\n";
echo "  Telefon actual: " . ($specialist->phone ?: 'NU ARE') . "\n\n";

// Actualizează telefonul
$newPhone = '+40740173581';
$specialist->phone = $newPhone;
$specialist->save();

echo "✅ Telefon actualizat la: {$newPhone}\n\n";

// Verifică actualizarea
$specialist->refresh();
echo "Verificare:\n";
echo "  Telefon în baza de date: {$specialist->phone}\n\n";

echo "✅ ACTUALIZARE COMPLETĂ!\n\n";
