<?php
/**
 * Script pentru a șterge serviciile duplicate
 * Rulează: https://dariabeauty.ro/delete-duplicate-services.php
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>🗑️ Delete Duplicate Services</h2>";
echo "<pre>";

try {
    // Găsește serviciile duplicate pentru Daria Nyikora
    $user = DB::table('users')->where('email', 'daria@gmail.com')->first();
    
    if (!$user) {
        echo "❌ User Daria Nyikora nu a fost găsit!\n";
        exit;
    }
    
    echo "✅ User găsit: {$user->name} (ID: {$user->id})\n\n";
    
    // Găsește toate serviciile "Extensii Unghii"
    $services = DB::table('services')
        ->where('user_id', $user->id)
        ->where('name', 'Extensii Unghii')
        ->orderBy('id', 'asc')
        ->get();
    
    echo "📋 Servicii găsite: " . count($services) . "\n\n";
    
    if (count($services) <= 1) {
        echo "✅ Nu există duplicate. Totul OK!\n";
    } else {
        // Păstrează primul, șterge restul
        $keepFirst = $services->first();
        echo "✅ Păstrăm serviciul ID: {$keepFirst->id} (primul creat)\n\n";
        
        $deleted = 0;
        foreach ($services as $service) {
            if ($service->id !== $keepFirst->id) {
                DB::table('services')->where('id', $service->id)->delete();
                echo "🗑️ Șters serviciu duplicate ID: {$service->id}\n";
                $deleted++;
            }
        }
        
        echo "\n✅ SUCCES! Au fost șterse {$deleted} servicii duplicate.\n";
    }
    
    // Verifică și repară storage link pentru imagini
    $storagePath = __DIR__.'/../storage/app/public';
    $publicLink = __DIR__.'/storage';
    
    echo "\n📁 Verificare storage link...\n";
    
    if (!file_exists($publicLink)) {
        echo "⚠️ Storage link lipsește. Creăm...\n";
        if (is_dir($storagePath)) {
            symlink($storagePath, $publicLink);
            echo "✅ Storage link creat!\n";
        } else {
            echo "❌ Director storage lipsește: {$storagePath}\n";
        }
    } else {
        echo "✅ Storage link există deja.\n";
    }
    
    echo "\n✅ Script finalizat!\n";
    echo "\n🗑️ Șterge acest fișier acum: delete-duplicate-services.php\n";
    
} catch (Exception $e) {
    echo "❌ EROARE: " . $e->getMessage() . "\n";
    echo "\n📋 Stack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
