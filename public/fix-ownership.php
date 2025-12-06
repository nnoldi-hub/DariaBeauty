<?php
/**
 * Fix ownership pentru servicii
 * Mută toate serviciile către user-ul corect
 * 
 * ⚠️ ȘTERGE după folosire!
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Fix Ownership</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:900px;margin:0 auto;}";
echo "table{border-collapse:collapse;width:100%;margin:20px 0;}";
echo "th,td{border:1px solid #ddd;padding:10px;text-align:left;}";
echo "th{background:#333;color:white;}.success{background:#d4edda;}.error{background:#f8d7da;}";
echo ".btn{display:inline-block;padding:10px 20px;margin:10px 5px;text-decoration:none;color:white;border-radius:5px;}";
echo ".btn-primary{background:#007bff;}.btn-danger{background:#dc3545;}</style></head><body>";

echo "<h1>🔧 Fix Ownership Servicii</h1>";

// Găsește toți userii specialiști
$specialists = DB::table('users')->where('role', 'specialist')->get();

echo "<h2>👥 Specialiști în baza de date:</h2>";
echo "<table><tr><th>ID</th><th>Nume</th><th>Email</th><th>Servicii</th><th>Acțiune</th></tr>";

foreach ($specialists as $specialist) {
    $servicesCount = DB::table('services')->where('user_id', $specialist->id)->count();
    echo "<tr>";
    echo "<td><strong>{$specialist->id}</strong></td>";
    echo "<td>{$specialist->name}</td>";
    echo "<td>{$specialist->email}</td>";
    echo "<td>{$servicesCount} servicii</td>";
    echo "<td>";
    
    if ($servicesCount > 0) {
        echo "<a href='?view={$specialist->id}' class='btn btn-primary'>Vezi Servicii</a>";
    } else {
        echo "<span style='color:#999;'>Fără servicii</span>";
    }
    
    echo "</td></tr>";
}
echo "</table>";

// Găsește servicii orfane (user inexistent)
$orphanServices = DB::table('services')
    ->leftJoin('users', 'services.user_id', '=', 'users.id')
    ->whereNull('users.id')
    ->select('services.*')
    ->get();

if ($orphanServices->count() > 0) {
    echo "<div style='background:#fff3cd;padding:20px;margin:20px 0;border-radius:5px;'>";
    echo "<h3>⚠️ Servicii Orfane (user-ul nu mai există):</h3>";
    echo "<p>Găsite <strong>{$orphanServices->count()}</strong> servicii ale căror useri au fost șterși.</p>";
    echo "<a href='?fix_orphan=yes' class='btn btn-danger'>Șterge Serviciile Orfane</a>";
    echo "</div>";
}

// Vizualizare servicii pentru un user
if (isset($_GET['view'])) {
    $userId = (int)$_GET['view'];
    $user = DB::table('users')->where('id', $userId)->first();
    
    if ($user) {
        echo "<hr><h2>📋 Serviciile lui {$user->name}</h2>";
        
        $services = DB::table('services')->where('user_id', $userId)->get();
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Nume Serviciu</th><th>Preț</th><th>Status</th><th>Acțiune</th></tr>";
        
        foreach ($services as $service) {
            echo "<tr>";
            echo "<td><strong>{$service->id}</strong></td>";
            echo "<td>{$service->name}</td>";
            echo "<td>{$service->price} RON</td>";
            echo "<td>" . ($service->is_active ? "✅ Activ" : "❌ Inactiv") . "</td>";
            echo "<td><a href='?delete_service={$service->id}&from={$userId}' class='btn btn-danger' onclick='return confirm(\"Ștergi {$service->name}?\")'>Șterge</a></td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<a href='?' class='btn btn-primary'>Înapoi</a>";
    }
}

// Transfer servicii de la un user la altul
if (isset($_GET['transfer'])) {
    echo "<hr><h2>🔄 Transfer Servicii</h2>";
    echo "<form method='GET'>";
    echo "<p><strong>De la user (ID):</strong> <input type='number' name='from_user' required style='padding:8px;'></p>";
    echo "<p><strong>Către user (ID):</strong> <input type='number' name='to_user' required style='padding:8px;'></p>";
    echo "<p><label><input type='checkbox' name='confirm_transfer' value='yes' required> Confirm transferul</label></p>";
    echo "<button type='submit' name='do_transfer' class='btn btn-primary'>Transfer</button> ";
    echo "<a href='?' class='btn btn-danger'>Anulează</a>";
    echo "</form>";
}

// Execută transferul
if (isset($_GET['do_transfer']) && isset($_GET['from_user']) && isset($_GET['to_user']) && isset($_GET['confirm_transfer'])) {
    $fromUser = (int)$_GET['from_user'];
    $toUser = (int)$_GET['to_user'];
    
    $fromUserData = DB::table('users')->where('id', $fromUser)->first();
    $toUserData = DB::table('users')->where('id', $toUser)->first();
    
    if (!$fromUserData || !$toUserData) {
        echo "<div class='error' style='padding:20px;margin:20px 0;'>";
        echo "<p>❌ Unul dintre useri nu există!</p>";
        echo "</div>";
    } else {
        $count = DB::table('services')
            ->where('user_id', $fromUser)
            ->update(['user_id' => $toUser]);
        
        echo "<div class='success' style='padding:20px;margin:20px 0;'>";
        echo "<h3>✅ Transfer Complet!</h3>";
        echo "<p>Mutate <strong>{$count}</strong> servicii de la:</p>";
        echo "<p><strong>{$fromUserData->name}</strong> (#{$fromUser})</p>";
        echo "<p>către:</p>";
        echo "<p><strong>{$toUserData->name}</strong> (#{$toUser})</p>";
        echo "<a href='?' class='btn btn-primary'>OK</a>";
        echo "</div>";
    }
}

// Șterge serviciu individual
if (isset($_GET['delete_service'])) {
    $serviceId = (int)$_GET['delete_service'];
    $fromUser = (int)$_GET['from'];
    
    $service = DB::table('services')->where('id', $serviceId)->first();
    
    if ($service) {
        // Șterge imaginea
        if ($service->image) {
            $imagePath = __DIR__ . '/../storage/app/public/' . $service->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        DB::table('services')->where('id', $serviceId)->delete();
        
        echo "<div class='success' style='padding:20px;margin:20px 0;'>";
        echo "<p>✅ Serviciul <strong>{$service->name}</strong> a fost șters!</p>";
        echo "<a href='?view={$fromUser}' class='btn btn-primary'>Înapoi la Lista Servicii</a>";
        echo "</div>";
    }
}

// Șterge servicii orfane
if (isset($_GET['fix_orphan']) && $_GET['fix_orphan'] === 'yes') {
    $orphans = DB::table('services')
        ->leftJoin('users', 'services.user_id', '=', 'users.id')
        ->whereNull('users.id')
        ->select('services.*')
        ->get();
    
    $deletedCount = 0;
    foreach ($orphans as $service) {
        if ($service->image) {
            $imagePath = __DIR__ . '/../storage/app/public/' . $service->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        DB::table('services')->where('id', $service->id)->delete();
        $deletedCount++;
    }
    
    echo "<div class='success' style='padding:20px;margin:20px 0;'>";
    echo "<p>✅ Șterse <strong>{$deletedCount}</strong> servicii orfane!</p>";
    echo "<a href='?' class='btn btn-primary'>OK</a>";
    echo "</div>";
}

echo "<hr>";
echo "<div style='margin-top:30px;'>";
echo "<a href='?transfer=yes' class='btn btn-primary'>🔄 Transfer Servicii între Useri</a>";
echo "</div>";

echo "<p style='margin-top:30px;color:#999;'>⚠️ Șterge acest fișier după folosire: <code>rm fix-ownership.php</code></p>";
echo "</body></html>";
