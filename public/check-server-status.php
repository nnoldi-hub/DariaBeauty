<?php
/**
 * Script de diagnostic pentru server
 * Verifică: storage link, versiune controller, route cache
 * 
 * ⚠️ ȘTERGE ACEST FIȘIER DUPĂ FOLOSIRE!
 */

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Status Server</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;}";
echo ".success{color:green;}.error{color:red;}.warning{color:orange;}";
echo "h2{border-bottom:2px solid #333;padding-bottom:10px;}pre{background:#f4f4f4;padding:15px;overflow:auto;}</style></head><body>";

echo "<h1>🔍 Diagnostic Server DariaBeauty</h1>";

// 1. Verifică storage link
echo "<h2>1. Storage Symlink</h2>";
$publicStorage = __DIR__ . '/storage';
$actualStorage = __DIR__ . '/../storage/app/public';

if (file_exists($publicStorage)) {
    if (is_link($publicStorage)) {
        $target = readlink($publicStorage);
        echo "<p class='success'>✅ Storage link există!</p>";
        echo "<p>Link: <code>$publicStorage</code></p>";
        echo "<p>Target: <code>$target</code></p>";
        
        if (file_exists($actualStorage)) {
            echo "<p class='success'>✅ Directorul storage/app/public există!</p>";
            
            // Verifică dacă există imagini
            $files = glob($actualStorage . '/services/*');
            if ($files) {
                echo "<p class='success'>✅ Găsite " . count($files) . " fișiere în storage/services/</p>";
                echo "<p>Exemple:</p><ul>";
                foreach (array_slice($files, 0, 5) as $file) {
                    $basename = basename($file);
                    $url = '/storage/services/' . $basename;
                    echo "<li><code>$basename</code> → <a href='$url' target='_blank'>Test</a></li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='warning'>⚠️ Niciun fișier în storage/services/</p>";
            }
        } else {
            echo "<p class='error'>❌ Directorul storage/app/public NU există!</p>";
        }
    } else {
        echo "<p class='warning'>⚠️ public/storage există dar NU e symlink!</p>";
        echo "<p>E director normal? " . (is_dir($publicStorage) ? 'DA' : 'NU') . "</p>";
    }
} else {
    echo "<p class='error'>❌ Storage link NU există!</p>";
    echo "<p>Trebuie creat: <code>ln -s ../storage/app/public public/storage</code></p>";
    
    // Încearcă să-l creeze
    if (function_exists('symlink')) {
        try {
            if (symlink($actualStorage, $publicStorage)) {
                echo "<p class='success'>✅ Am creat storage link-ul acum!</p>";
            } else {
                echo "<p class='error'>❌ Nu am putut crea storage link!</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>❌ Eroare la creare: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>❌ Funcția symlink() nu e disponibilă!</p>";
    }
}

// 2. Verifică versiunea controlerului
echo "<h2>2. Versiune Controller</h2>";
$controllerPath = __DIR__ . '/../app/Http/Controllers/SpecialistController.php';

if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Verifică fix-ul pentru destroyService
    if (strpos($content, "redirect()->route('specialist.services.index')") !== false) {
        echo "<p class='success'>✅ Controller are fix-ul pentru redirect corect!</p>";
    } else {
        echo "<p class='error'>❌ Controller NU are fix-ul! Încă folosește route vechi!</p>";
        
        // Caută ce route folosește
        preg_match("/destroyService.*?redirect\(\)->route\('([^']+)'/s", $content, $matches);
        if ($matches) {
            echo "<p class='error'>Versiune curentă: <code>{$matches[1]}</code></p>";
            echo "<p>Trebuie să fie: <code>specialist.services.index</code></p>";
        }
    }
    
    // Verifică fix-ul pentru storeService
    if (strpos($content, "\$data['sub_brand'] = \$specialist->sub_brand ?? 'dariaNails'") !== false) {
        echo "<p class='success'>✅ Controller are fix-ul pentru sub_brand default!</p>";
    } else {
        echo "<p class='warning'>⚠️ Controller nu are fix-ul pentru sub_brand (poate cauza erori)!</p>";
    }
    
    // Arată ultimele modificări
    $lastModified = filemtime($controllerPath);
    echo "<p>Ultima modificare: <strong>" . date('Y-m-d H:i:s', $lastModified) . "</strong></p>";
    
} else {
    echo "<p class='error'>❌ SpecialistController.php NU există!</p>";
}

// 3. Verifică create.blade.php pentru JavaScript
echo "<h2>3. JavaScript Prevenire Duplicate</h2>";
$viewPath = __DIR__ . '/../resources/views/specialist/services/create.blade.php';

if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    
    if (strpos($content, "@section('scripts')") !== false && 
        strpos($content, "submitBtn.disabled") !== false) {
        echo "<p class='success'>✅ View-ul are JavaScript pentru prevenire duplicate!</p>";
    } else {
        echo "<p class='error'>❌ View-ul NU are fix-ul JavaScript!</p>";
    }
    
    $lastModified = filemtime($viewPath);
    echo "<p>Ultima modificare: <strong>" . date('Y-m-d H:i:s', $lastModified) . "</strong></p>";
} else {
    echo "<p class='error'>❌ create.blade.php NU există!</p>";
}

// 4. Verifică route cache
echo "<h2>4. Cache Status</h2>";
$routeCachePath = __DIR__ . '/../bootstrap/cache/routes-v7.php';
$configCachePath = __DIR__ . '/../bootstrap/cache/config.php';
$viewCachePath = __DIR__ . '/../storage/framework/views';

if (file_exists($routeCachePath)) {
    $age = time() - filemtime($routeCachePath);
    $ageStr = gmdate('H:i:s', $age);
    echo "<p class='warning'>⚠️ Route cache există (vârstă: $ageStr)</p>";
    echo "<p>Ultima modificare: " . date('Y-m-d H:i:s', filemtime($routeCachePath)) . "</p>";
    echo "<p><strong>Recomandare:</strong> <code>php artisan route:clear</code></p>";
} else {
    echo "<p class='success'>✅ Nu există route cache</p>";
}

if (file_exists($configCachePath)) {
    echo "<p class='warning'>⚠️ Config cache există</p>";
    echo "<p><strong>Recomandare:</strong> <code>php artisan config:clear</code></p>";
} else {
    echo "<p class='success'>✅ Nu există config cache</p>";
}

// Verifică view cache
if (is_dir($viewCachePath)) {
    $files = glob($viewCachePath . '/*');
    if ($files && count($files) > 0) {
        echo "<p class='warning'>⚠️ Există " . count($files) . " view-uri cached</p>";
        echo "<p><strong>Recomandare:</strong> <code>php artisan view:clear</code></p>";
    } else {
        echo "<p class='success'>✅ Nu există view cache</p>";
    }
}

// 5. Verifică baza de date pentru duplicate
echo "<h2>5. Servicii Duplicate în DB</h2>";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $services = DB::table('services')
        ->select('name', 'user_id', DB::raw('COUNT(*) as count'))
        ->groupBy('name', 'user_id')
        ->having('count', '>', 1)
        ->get();
    
    if ($services->count() > 0) {
        echo "<p class='error'>❌ Găsite servicii duplicate:</p>";
        echo "<table border='1' cellpadding='10' style='border-collapse:collapse;width:100%;'>";
        echo "<tr><th>Nume Serviciu</th><th>User ID</th><th>Număr duplicate</th></tr>";
        foreach ($services as $service) {
            echo "<tr><td>{$service->name}</td><td>{$service->user_id}</td><td>{$service->count}</td></tr>";
        }
        echo "</table>";
        
        echo "<p><strong>Soluție:</strong> Rulează <code>delete-duplicate-services.php</code></p>";
    } else {
        echo "<p class='success'>✅ Nu există servicii duplicate!</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Nu pot verifica DB: " . $e->getMessage() . "</p>";
}

// 6. Info despre versiunea PHP și Laravel
echo "<h2>6. Environment Info</h2>";
echo "<p>PHP Version: <strong>" . PHP_VERSION . "</strong></p>";
echo "<p>Server Software: <strong>" . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</strong></p>";
echo "<p>Document Root: <strong>" . $_SERVER['DOCUMENT_ROOT'] . "</strong></p>";

if (function_exists('symlink')) {
    echo "<p class='success'>✅ Funcția symlink() e disponibilă</p>";
} else {
    echo "<p class='error'>❌ Funcția symlink() NU e disponibilă (probleme cu storage link!)</p>";
}

echo "<hr>";
echo "<h2>📋 Checklist Deployment</h2>";
echo "<ol>";
echo "<li>Upload <code>app/Http/Controllers/SpecialistController.php</code></li>";
echo "<li>Upload <code>resources/views/specialist/services/create.blade.php</code></li>";
echo "<li>Upload <code>public/delete-duplicate-services.php</code></li>";
echo "<li>Rulează: <code>php artisan route:clear</code></li>";
echo "<li>Rulează: <code>php artisan view:clear</code></li>";
echo "<li>Rulează: <code>php artisan cache:clear</code></li>";
echo "<li>Execută: <code>delete-duplicate-services.php</code></li>";
echo "<li>Șterge: <code>fix-services-subbrand.php</code></li>";
echo "<li>Șterge: <code>delete-duplicate-services.php</code></li>";
echo "<li>Șterge: <code>check-server-status.php</code> (acest fișier!)</li>";
echo "</ol>";

echo "<p style='margin-top:30px;padding:20px;background:#fff3cd;border:2px solid #ffc107;'>";
echo "⚠️ <strong>IMPORTANT:</strong> Șterge acest fișier după folosire!<br>";
echo "<code>rm check-server-status.php</code>";
echo "</p>";

echo "</body></html>";
