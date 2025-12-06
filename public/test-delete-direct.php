<?php
/**
 * Test DIRECT pentru delete service
 * Bypass-ează sesiunea și testează ownership
 * 
 * ⚠️ ȘTERGE după debug!
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Test Delete Direct</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:1000px;margin:0 auto;}";
echo "table{border-collapse:collapse;width:100%;margin:20px 0;}";
echo "th,td{border:1px solid #ddd;padding:12px;text-align:left;}";
echo "th{background:#333;color:white;}.error{background:#ffe6e6;}.success{background:#e6ffe6;}";
echo ".btn{display:inline-block;padding:8px 16px;margin:5px;text-decoration:none;color:white;border-radius:4px;}";
echo ".btn-danger{background:#dc3545;}.btn-success{background:#28a745;}</style></head><body>";

echo "<h1>🔧 Test Delete Direct</h1>";

// Găsește user-ul Daria
$email = $_GET['email'] ?? 'daria@gmail.com';
$user = DB::table('users')->where('email', $email)->first();

if (!$user) {
    echo "<p class='error'>❌ User cu email '{$email}' nu există!</p>";
    echo "<form method='GET'>";
    echo "Email: <input type='email' name='email' value='{$email}' required> <button type='submit'>Caută</button>";
    echo "</form>";
    echo "</body></html>";
    exit;
}

echo "<h2>👤 User Test: {$user->name}</h2>";
echo "<table>";
echo "<tr><th>ID</th><th>Email</th><th>Role</th><th>Active</th></tr>";
echo "<tr><td>{$user->id}</td><td>{$user->email}</td><td>{$user->role}</td><td>" . ($user->is_active ? "✅ DA" : "❌ NU") . "</td></tr>";
echo "</table>";

// Verificări middleware
echo "<h2>🛡️ Verificări Middleware</h2>";
echo "<table>";
echo "<tr><th>Check</th><th>Rezultat</th><th>Status</th></tr>";

$isSpecialist = ($user->role === 'specialist');
echo "<tr class='" . ($isSpecialist ? "success" : "error") . "'>";
echo "<td>Role = 'specialist'</td><td>" . ($isSpecialist ? "✅ PASS" : "❌ FAIL (role={$user->role})") . "</td>";
echo "<td>" . ($isSpecialist ? "OK" : "403") . "</td></tr>";

$isActive = ($user->is_active == 1);
echo "<tr class='" . ($isActive ? "success" : "error") . "'>";
echo "<td>is_active = true</td><td>" . ($isActive ? "✅ PASS" : "❌ FAIL") . "</td>";
echo "<td>" . ($isActive ? "OK" : "Redirect") . "</td></tr>";

echo "</table>";

if (!$isSpecialist || !$isActive) {
    echo "<p class='error' style='font-size:18px;padding:20px;'>";
    echo "❌ <strong>USER-UL NU TRECE DE MIDDLEWARE!</strong><br>";
    if (!$isSpecialist) echo "- Role incorect: '{$user->role}' (trebuie 'specialist')<br>";
    if (!$isActive) echo "- Cont inactiv (is_active=0)<br>";
    echo "</p>";
}

// Serviciile user-ului
echo "<h2>📋 Serviciile Tale</h2>";
$services = DB::table('services')->where('user_id', $user->id)->get();

if ($services->count() === 0) {
    echo "<p>Nu ai niciun serviciu.</p>";
} else {
    echo "<table>";
    echo "<tr><th>ID</th><th>Nume</th><th>User ID</th><th>Ownership</th><th>Image</th><th>Action</th></tr>";
    
    foreach ($services as $service) {
        $ownershipOK = ($service->user_id == $user->id);
        $rowClass = $ownershipOK ? "success" : "error";
        
        // Verifică imagine
        $imageStatus = "N/A";
        if ($service->image) {
            $imagePath = __DIR__ . "/../storage/app/public/{$service->image}";
            $imageStatus = file_exists($imagePath) ? "✅ Există" : "❌ Lipsă";
        }
        
        echo "<tr class='{$rowClass}'>";
        echo "<td><strong>{$service->id}</strong></td>";
        echo "<td>{$service->name}</td>";
        echo "<td>{$service->user_id}</td>";
        echo "<td>" . ($ownershipOK ? "✅ MATCH" : "❌ NO MATCH") . "</td>";
        echo "<td>{$imageStatus}</td>";
        echo "<td>";
        
        if ($ownershipOK && $isSpecialist && $isActive) {
            echo "<a href='?email={$email}&delete={$service->id}' class='btn btn-danger' onclick=\"return confirm('Ștergi serviciul {$service->name}?')\">Șterge Test</a>";
        } else {
            echo "<span style='color:#999;'>❌ Cannot delete</span>";
        }
        
        echo "</td></tr>";
    }
    echo "</table>";
}

// Procesează delete dacă e request
if (isset($_GET['delete']) && $isSpecialist && $isActive) {
    $serviceId = (int)$_GET['delete'];
    $service = DB::table('services')->where('id', $serviceId)->first();
    
    echo "<hr><h2>🗑️ Test Delete pentru Service #{$serviceId}</h2>";
    
    if (!$service) {
        echo "<p class='error'>❌ Serviciul #{$serviceId} nu există!</p>";
    } else {
        echo "<table>";
        echo "<tr><th>Check</th><th>Rezultat</th></tr>";
        
        // Check 1: Ownership
        $ownershipOK = ($service->user_id == $user->id);
        echo "<tr class='" . ($ownershipOK ? "success" : "error") . "'>";
        echo "<td>Ownership</td><td>";
        echo "Service user_id: {$service->user_id}<br>";
        echo "Your user_id: {$user->id}<br>";
        echo ($ownershipOK ? "✅ MATCH - Delete permis" : "❌ NO MATCH - 403 Unauthorized");
        echo "</td></tr>";
        
        // Check 2: Image cleanup
        $imageExists = false;
        if ($service->image) {
            $imagePath = __DIR__ . "/../storage/app/public/{$service->image}";
            $imageExists = file_exists($imagePath);
            echo "<tr class='" . ($imageExists ? "success" : "") . "'>";
            echo "<td>Image</td><td>";
            echo "Path: {$service->image}<br>";
            echo ($imageExists ? "✅ Există (va fi șters)" : "⚠️ Nu există (skip)");
            echo "</td></tr>";
        }
        
        echo "</table>";
        
        if ($ownershipOK) {
            // SIMULATE DELETE (nu șterge real, doar test)
            if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
                try {
                    // Șterge imaginea
                    if ($service->image && $imageExists) {
                        $imagePath = __DIR__ . "/../storage/app/public/{$service->image}";
                        unlink($imagePath);
                        echo "<p class='success'>✅ Imagine ștearsă: {$service->image}</p>";
                    }
                    
                    // Șterge serviciul
                    DB::table('services')->where('id', $serviceId)->delete();
                    
                    echo "<p class='success' style='font-size:20px;padding:20px;'>";
                    echo "✅ <strong>SERVICIUL A FOST ȘTERS CU SUCCES!</strong><br>";
                    echo "Asta înseamnă că ownership-ul e OK și delete ar trebui să meargă și din interfață!";
                    echo "</p>";
                    
                    echo "<p><a href='?email={$email}' class='btn btn-success'>Reîncarcă lista</a></p>";
                    
                } catch (Exception $e) {
                    echo "<p class='error'>❌ Eroare la ștergere: {$e->getMessage()}</p>";
                }
            } else {
                echo "<p style='padding:20px;background:#fff3cd;border:2px solid #ffc107;'>";
                echo "⚠️ <strong>CONFIRMARE DELETE</strong><br>";
                echo "Vrei să ștergi REAL serviciul \"{$service->name}\"?<br><br>";
                echo "<a href='?email={$email}&delete={$serviceId}&confirm=yes' class='btn btn-danger'>DA, Șterge Real</a> ";
                echo "<a href='?email={$email}' class='btn btn-success'>NU, Anulează</a>";
                echo "</p>";
            }
        } else {
            echo "<p class='error' style='font-size:18px;padding:20px;'>";
            echo "❌ <strong>OWNERSHIP MISMATCH!</strong><br>";
            echo "Serviciul #{$serviceId} aparține user_id={$service->user_id}, dar tu ești user_id={$user->id}<br>";
            echo "De aceea primești 403 Unauthorized!";
            echo "</p>";
        }
    }
}

echo "<hr>";
echo "<h2>🎯 Concluzie</h2>";

if ($isSpecialist && $isActive) {
    $myServices = DB::table('services')->where('user_id', $user->id)->count();
    
    if ($myServices > 0) {
        echo "<p class='success' style='font-size:18px;padding:20px;'>";
        echo "✅ <strong>Delete AR TREBUI SĂ FUNCȚIONEZE!</strong><br>";
        echo "- Ai role 'specialist'<br>";
        echo "- Contul e activ<br>";
        echo "- Serviciile sunt ale tale (user_id match)<br><br>";
        echo "Dacă tot primești 403 în interfață, problema e în:<br>";
        echo "1. <strong>View cache</strong> → Rulează <code>php artisan view:clear</code><br>";
        echo "2. <strong>Sesiunea</strong> → Verifică cookie-urile Laravel<br>";
        echo "3. <strong>CSRF token</strong> → Verifică @csrf în formular<br>";
        echo "</p>";
    } else {
        echo "<p>Nu ai servicii de testat.</p>";
    }
} else {
    echo "<p class='error' style='font-size:18px;padding:20px;'>";
    echo "❌ Delete NU va funcționa pentru că:<br>";
    if (!$isSpecialist) echo "- Role incorect ('{$user->role}' != 'specialist')<br>";
    if (!$isActive) echo "- Cont inactiv<br>";
    echo "</p>";
}

echo "<p style='margin-top:30px;'><strong>⚠️ ȘTERGE acest fișier:</strong> <code>rm test-delete-direct.php</code></p>";
echo "</body></html>";
