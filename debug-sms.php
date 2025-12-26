<?php
/**
 * Script debug complet pentru SMS
 * Rulează: php debug-sms.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n╔══════════════════════════════════════════════════════════╗\n";
echo "║         DEBUG SMS NOTIFICARI - DARIA BEAUTY          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// 1. Verifică configurația Twilio
echo "📋 CONFIGURAȚIE TWILIO:\n";
echo "   TWILIO_ENABLED: " . (config('twilio.enabled') ? '✅ DA' : '❌ NU') . "\n";
echo "   TWILIO_SID: " . (config('twilio.sid') ? '✅ ' . substr(config('twilio.sid'), 0, 10) . '...' : '❌ NU E SETAT') . "\n";
echo "   TWILIO_AUTH_TOKEN: " . (config('twilio.auth_token') ? '✅ Setat' : '❌ NU E SETAT') . "\n";
echo "   TWILIO_PHONE: " . config('twilio.phone_number') . "\n\n";

// 2. Verifică specialiștii
echo "👤 SPECIALIȘTI:\n";
$specialists = \App\Models\User::where('role', 'specialist')->get();

if ($specialists->isEmpty()) {
    echo "   ❌ Nu există specialiști!\n\n";
} else {
    foreach ($specialists as $specialist) {
        echo "   ID: {$specialist->id}\n";
        echo "   Nume: {$specialist->name}\n";
        echo "   Email: {$specialist->email}\n";
        echo "   Telefon: " . ($specialist->phone ? "✅ {$specialist->phone}" : '❌ NU ARE') . "\n";
        echo "   Activ: " . ($specialist->is_active ? '✅ Da' : '❌ Nu') . "\n";
        echo "   ---\n";
    }
}

// 3. Test inițializare SmsService
echo "\n🔧 TEST INIȚIALIZARE SMS SERVICE:\n";
try {
    $smsService = app(\App\Services\SmsService::class);
    echo "   ✅ SmsService inițializat cu succes\n";
    
    // Verifică dacă este enabled folosind reflection
    $reflection = new \ReflectionClass($smsService);
    $enabledProperty = $reflection->getProperty('enabled');
    $enabledProperty->setAccessible(true);
    $isEnabled = $enabledProperty->getValue($smsService);
    
    echo "   Service enabled: " . ($isEnabled ? '✅ DA' : '❌ NU') . "\n";
    
} catch (\Exception $e) {
    echo "   ❌ EROARE: {$e->getMessage()}\n";
}

// 4. Verifică ultima programare
echo "\n📅 ULTIMA PROGRAMARE:\n";
$lastAppointment = \App\Models\Appointment::with(['service', 'specialist'])
    ->orderBy('created_at', 'desc')
    ->first();

if ($lastAppointment) {
    echo "   ID: {$lastAppointment->id}\n";
    echo "   Client: {$lastAppointment->client_name} ({$lastAppointment->client_phone})\n";
    echo "   Serviciu: " . ($lastAppointment->service ? $lastAppointment->service->name : '❌ NU EXISTĂ') . "\n";
    echo "   Specialist: " . ($lastAppointment->specialist ? $lastAppointment->specialist->name : '❌ NU EXISTĂ') . "\n";
    echo "   Status: {$lastAppointment->status}\n";
    echo "   Data: {$lastAppointment->appointment_date->format('d.m.Y')} la {$lastAppointment->appointment_time}\n";
} else {
    echo "   ❌ Nu există programări\n";
}

// 5. Verifică ultimele loguri
echo "\n📝 ULTIMELE 10 LOGURI SMS (din Laravel log):\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file($logFile);
    $smsLogs = array_filter($logs, function($line) {
        return stripos($line, 'SMS') !== false || 
               stripos($line, 'specialist') !== false ||
               stripos($line, 'NOTIFY') !== false;
    });
    $lastLogs = array_slice($smsLogs, -10);
    
    if (empty($lastLogs)) {
        echo "   ℹ️ Nu există loguri SMS recente\n";
    } else {
        foreach ($lastLogs as $log) {
            echo "   " . trim($log) . "\n";
        }
    }
} else {
    echo "   ❌ Fișierul de log nu există\n";
}

echo "\n╔══════════════════════════════════════════════════════════╗\n";
echo "║                    DEBUG COMPLET                        ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

echo "📌 PAȘI URMĂTORI:\n";
echo "   1. Verifică că TWILIO_ENABLED=true în .env\n";
echo "   2. Verifică că specialistul are telefon setat\n";
echo "   3. Încarcă fișierele actualizate pe server\n";
echo "   4. Creează o programare de test\n";
echo "   5. Monitorizează: tail -f storage/logs/laravel.log\n\n";
