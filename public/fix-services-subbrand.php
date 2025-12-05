<?php
/**
 * Script pentru a face sub_brand nullable în tabela services
 * Rulează acest script direct în browser: https://dariabeauty.ro/fix-services-subbrand.php
 */

define('LARAVEL_START', microtime(true));

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>🔧 Fix Services Table - sub_brand nullable</h2>";
echo "<pre>";

try {
    // Verifică dacă coloana există și e NOT NULL
    $result = DB::select("SHOW COLUMNS FROM services WHERE Field = 'sub_brand'");
    
    if (!empty($result)) {
        $column = $result[0];
        echo "✅ Coloana sub_brand există\n";
        echo "📋 Tip actual: " . $column->Type . "\n";
        echo "📋 Null: " . $column->Null . "\n\n";
        
        if ($column->Null === 'NO') {
            echo "🔄 Modificăm coloana să permită NULL...\n";
            
            DB::statement("ALTER TABLE services MODIFY COLUMN sub_brand ENUM('dariaNails', 'dariaHair', 'dariaGlow') NULL");
            
            echo "✅ SUCCES! Coloana sub_brand este acum nullable\n";
        } else {
            echo "✅ Coloana sub_brand este deja nullable. Nu e nevoie de modificări.\n";
        }
    } else {
        echo "❌ Coloana sub_brand nu există!\n";
    }
    
    echo "\n✅ Script finalizat cu succes!\n";
    echo "\n🗑️ Poți șterge acest fișier acum: fix-services-subbrand.php\n";
    
} catch (Exception $e) {
    echo "❌ EROARE: " . $e->getMessage() . "\n";
    echo "\n📋 Stack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
