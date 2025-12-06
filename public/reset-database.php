<?php
/**
 * Script pentru resetarea bazei de date
 * Șterge toate datele și ruleaza migrațiile din nou
 * Păstrează doar super admin-ul
 * 
 * ⚠️ ATENȚIE: ȘTERGE TOATE DATELE! Folosește cu grijă!
 * ⚠️ ȘTERGE ACEST FIȘIER DUPĂ FOLOSIRE!
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Reset Database</title>";
echo "<style>body{font-family:Arial;padding:40px;max-width:800px;margin:0 auto;background:#f5f5f5;}";
echo ".container{background:white;padding:30px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}";
echo "h1{color:#d32f2f;border-bottom:3px solid #d32f2f;padding-bottom:10px;}";
echo ".warning{background:#fff3cd;border:2px solid #ffc107;padding:20px;margin:20px 0;border-radius:5px;}";
echo ".success{background:#d4edda;border:2px solid #28a745;padding:20px;margin:20px 0;border-radius:5px;}";
echo ".error{background:#f8d7da;border:2px solid #dc3545;padding:20px;margin:20px 0;border-radius:5px;}";
echo ".btn{display:inline-block;padding:12px 24px;margin:10px 5px;text-decoration:none;border-radius:5px;font-weight:bold;cursor:pointer;border:none;}";
echo ".btn-danger{background:#dc3545;color:white;}.btn-success{background:#28a745;color:white;}.btn-secondary{background:#6c757d;color:white;}";
echo "table{width:100%;border-collapse:collapse;margin:20px 0;}th,td{padding:12px;border:1px solid #ddd;text-align:left;}";
echo "th{background:#333;color:white;}</style></head><body><div class='container'>";

echo "<h1>🗑️ Reset Database DariaBeauty</h1>";

// Verifică dacă există confirmare
$step = $_GET['step'] ?? 'warning';

if ($step === 'warning') {
    // Step 1: Avertisment
    echo "<div class='warning'>";
    echo "<h2>⚠️ AVERTISMENT</h2>";
    echo "<p><strong>Acest script va ȘTERGE TOATE DATELE din baza de date:</strong></p>";
    echo "<ul>";
    echo "<li>❌ Toți userii (exceptând super admin-ul)</li>";
    echo "<li>❌ Toate serviciile</li>";
    echo "<li>❌ Toate programările</li>";
    echo "<li>❌ Toate review-urile</li>";
    echo "<li>❌ Toată galeria</li>";
    echo "<li>❌ Toate imaginile uploadate</li>";
    echo "</ul>";
    echo "<p style='color:red;font-weight:bold;'>ACEASTĂ ACȚIUNE NU POATE FI ANULATĂ!</p>";
    echo "</div>";
    
    // Arată ce există acum în DB
    echo "<h2>📊 Date Curente în Baza de Date</h2>";
    echo "<table>";
    echo "<tr><th>Tabel</th><th>Număr Înregistrări</th></tr>";
    
    $tables = [
        'users' => 'Useri',
        'services' => 'Servicii',
        'appointments' => 'Programări',
        'reviews' => 'Review-uri',
        'gallery' => 'Galerie',
    ];
    
    foreach ($tables as $table => $label) {
        $count = DB::table($table)->count();
        echo "<tr><td>{$label}</td><td><strong>{$count}</strong></td></tr>";
    }
    echo "</table>";
    
    // Formular confirmare
    echo "<h2>🔐 Confirmare Reset</h2>";
    echo "<form method='GET'>";
    echo "<input type='hidden' name='step' value='confirm'>";
    echo "<p><strong>Pentru a continua, introdu emailul super admin-ului care va fi păstrat:</strong></p>";
    echo "<input type='email' name='admin_email' placeholder='admin@dariabeauty.ro' required style='width:100%;padding:10px;margin:10px 0;font-size:16px;'>";
    echo "<p><label><input type='checkbox' name='confirm_delete' value='yes' required> Confirm că vreau să șterg TOATE datele</label></p>";
    echo "<p><label><input type='checkbox' name='confirm_images' value='yes' required> Confirm că vreau să șterg și imaginile din storage</label></p>";
    echo "<button type='submit' class='btn btn-danger'>🗑️ DA, Resetează Baza de Date</button> ";
    echo "<a href='/' class='btn btn-secondary'>❌ Anulează</a>";
    echo "</form>";
    
} elseif ($step === 'confirm' && isset($_GET['confirm_delete']) && isset($_GET['admin_email'])) {
    // Step 2: Execută resetarea
    
    $adminEmail = $_GET['admin_email'];
    $deleteImages = isset($_GET['confirm_images']);
    
    echo "<h2>⚙️ Se procesează resetarea...</h2>";
    
    try {
        // Verifică că admin-ul există
        $admin = DB::table('users')->where('email', $adminEmail)->where('role', 'admin')->first();
        
        if (!$admin) {
            echo "<div class='error'>";
            echo "<p>❌ <strong>EROARE:</strong> Nu există admin cu email-ul '{$adminEmail}'!</p>";
            echo "<p>Verifică că email-ul este corect și că user-ul are role='admin'.</p>";
            echo "<a href='?step=warning' class='btn btn-secondary'>Înapoi</a>";
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<p>✅ Admin găsit: <strong>{$admin->name}</strong> (#{$admin->id})</p>";
            echo "</div>";
            
            echo "<h3>🗑️ Ștergere Date:</h3>";
            echo "<ul>";
            
            // 1. Șterge serviciile (și imaginile asociate)
            $services = DB::table('services')->get();
            $deletedImages = 0;
            
            if ($deleteImages) {
                foreach ($services as $service) {
                    if ($service->image) {
                        $imagePath = __DIR__ . '/../storage/app/public/' . $service->image;
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                            $deletedImages++;
                        }
                    }
                }
            }
            
            $servicesCount = DB::table('services')->delete();
            echo "<li>✅ Șters <strong>{$servicesCount}</strong> servicii" . ($deleteImages ? " + {$deletedImages} imagini" : "") . "</li>";
            
            // 2. Șterge galeria (și imaginile)
            $gallery = DB::table('gallery')->get();
            $deletedGalleryImages = 0;
            
            if ($deleteImages) {
                foreach ($gallery as $item) {
                    if ($item->image_path) {
                        $imagePath = __DIR__ . '/../storage/app/public/' . $item->image_path;
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                            $deletedGalleryImages++;
                        }
                    }
                }
            }
            
            $galleryCount = DB::table('gallery')->delete();
            echo "<li>✅ Șters <strong>{$galleryCount}</strong> imagini galerie" . ($deleteImages ? " + {$deletedGalleryImages} fișiere" : "") . "</li>";
            
            // 3. Șterge programările
            $appointmentsCount = DB::table('appointments')->delete();
            echo "<li>✅ Șters <strong>{$appointmentsCount}</strong> programări</li>";
            
            // 4. Șterge review-urile
            $reviewsCount = DB::table('reviews')->delete();
            echo "<li>✅ Șters <strong>{$reviewsCount}</strong> review-uri</li>";
            
            // 5. Șterge userii (păstrând admin-ul)
            $usersCount = DB::table('users')->where('id', '!=', $admin->id)->delete();
            echo "<li>✅ Șters <strong>{$usersCount}</strong> useri (păstrat admin #{$admin->id})</li>";
            
            // 6. Șterge social links
            $socialCount = DB::table('social_links')->delete();
            echo "<li>✅ Șters <strong>{$socialCount}</strong> link-uri sociale</li>";
            
            echo "</ul>";
            
            // Rezultat final
            echo "<div class='success' style='margin-top:30px;'>";
            echo "<h2>✅ Resetare Completă!</h2>";
            echo "<p><strong>Baza de date a fost curățată cu succes!</strong></p>";
            echo "<p>Rămâne doar:</p>";
            echo "<ul>";
            echo "<li>Admin: <strong>{$admin->name}</strong> ({$admin->email})</li>";
            echo "</ul>";
            echo "</div>";
            
            echo "<h3>📋 Status Final:</h3>";
            echo "<table>";
            echo "<tr><th>Tabel</th><th>Înregistrări Rămase</th></tr>";
            
            $tables = [
                'users' => 'Useri',
                'services' => 'Servicii',
                'appointments' => 'Programări',
                'reviews' => 'Review-uri',
                'gallery' => 'Galerie',
            ];
            
            foreach ($tables as $table => $label) {
                $count = DB::table($table)->count();
                echo "<tr><td>{$label}</td><td><strong>{$count}</strong></td></tr>";
            }
            echo "</table>";
            
            echo "<div class='warning' style='margin-top:30px;'>";
            echo "<h3>🧹 Nu uita să:</h3>";
            echo "<ol>";
            echo "<li>Ștergi acest fișier: <code>rm reset-database.php</code></li>";
            echo "<li>Ștergi și celelalte scripturi de debug: <code>rm debug-delete.php test-delete-direct.php check-server-status.php</code></li>";
            echo "<li>Clear cache: <code>php artisan cache:clear</code></li>";
            echo "</ol>";
            echo "</div>";
            
            echo "<a href='/' class='btn btn-success'>🏠 Mergi la Homepage</a>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<h2>❌ Eroare la Resetare</h2>";
        echo "<p><strong>{$e->getMessage()}</strong></p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "<a href='?step=warning' class='btn btn-secondary'>Încearcă din nou</a>";
        echo "</div>";
    }
    
} else {
    echo "<div class='error'>";
    echo "<p>❌ Parametri invalizi! Folosește link-ul corect.</p>";
    echo "<a href='?step=warning' class='btn btn-secondary'>Înapoi</a>";
    echo "</div>";
}

echo "</div></body></html>";
