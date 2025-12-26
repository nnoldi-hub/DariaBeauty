<?php
/**
 * Script pentru verificare telefon specialist
 * Rulează: php check-specialist-phone.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Găsește toți specialiștii
$specialists = \App\Models\User::where('role', 'specialist')->get();

echo "\n=== VERIFICARE TELEFOANE SPECIALISTI ===\n\n";

if ($specialists->isEmpty()) {
    echo "Nu există specialiști în baza de date!\n";
} else {
    foreach ($specialists as $specialist) {
        echo "ID: {$specialist->id}\n";
        echo "Nume: {$specialist->name}\n";
        echo "Email: {$specialist->email}\n";
        echo "Telefon: " . ($specialist->phone ?: '❌ NU ARE TELEFON SETAT') . "\n";
        echo "Activ: " . ($specialist->is_active ? 'Da' : 'Nu') . "\n";
        echo "---\n\n";
    }
}

// Verifică setările Twilio
echo "\n=== SETĂRI TWILIO ===\n\n";
echo "TWILIO_ENABLED: " . (config('twilio.enabled') ? 'Da' : 'Nu') . "\n";
echo "TWILIO_SID: " . (config('twilio.sid') ? 'Setat' : 'NU E SETAT') . "\n";
echo "TWILIO_AUTH_TOKEN: " . (config('twilio.auth_token') ? 'Setat' : 'NU E SETAT') . "\n";
echo "TWILIO_PHONE: " . config('twilio.from') . "\n";

echo "\n";
