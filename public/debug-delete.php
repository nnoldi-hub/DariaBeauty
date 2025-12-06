<?php
/**
 * Debug script pentru problema de delete
 * Verifică ownership și permisiuni
 * 
 * ⚠️ ȘTERGE după folosire!
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Debug Delete</title>";
echo "<style>body{font-family:monospace;padding:20px;max-width:1200px;margin:0 auto;}";
echo "table{border-collapse:collapse;width:100%;margin:20px 0;}";
echo "th,td{border:1px solid #ddd;padding:8px;text-align:left;}";
echo "th{background:#333;color:white;}.error{color:red;}.success{color:green;}</style></head><body>";

echo "<h1>🔍 Debug Delete Service</h1>";

// 1. Verifică user-ul autentificat
echo "<h2>1. User Autentificat</h2>";
if (Auth::check()) {
    $user = Auth::user();
    echo "<p class='success'>✅ User autentificat:</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Email</th><th>Nume</th><th>Role</th></tr>";
    echo "<tr><td>{$user->id}</td><td>{$user->email}</td><td>{$user->name}</td><td>{$user->role}</td></tr>";
    echo "</table>";
} else {
    echo "<p class='error'>❌ Niciun user autentificat! Loghează-te mai întâi!</p>";
    echo "<p><a href='/login'>Login</a></p>";
    echo "</body></html>";
    exit;
}

// 2. Verifică serviciile user-ului
echo "<h2>2. Serviciile Tale</h2>";
$services = DB::table('services')->where('user_id', $user->id)->get();

if ($services->count() > 0) {
    echo "<p class='success'>✅ Găsite {$services->count()} servicii</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nume</th><th>User ID</th><th>Image</th><th>Created</th><th>Test Ownership</th></tr>";
    
    foreach ($services as $service) {
        $ownershipCheck = ($service->user_id === $user->id) ? "✅ MATCH" : "❌ NO MATCH";
        $ownershipClass = ($service->user_id === $user->id) ? "success" : "error";
        
        $imagePath = $service->image ? "/storage/{$service->image}" : "N/A";
        $imageExists = $service->image && file_exists(__DIR__ . "/../storage/app/public/{$service->image}");
        $imageStatus = $imageExists ? "✅ Există" : "❌ Lipsă";
        
        echo "<tr>";
        echo "<td>{$service->id}</td>";
        echo "<td>{$service->name}</td>";
        echo "<td>{$service->user_id}</td>";
        echo "<td>{$imagePath}<br><small>{$imageStatus}</small></td>";
        echo "<td>" . date('Y-m-d H:i', strtotime($service->created_at)) . "</td>";
        echo "<td class='{$ownershipClass}'><strong>{$ownershipCheck}</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ Nu ai niciun serviciu!</p>";
}

// 3. Verifică toate serviciile (pentru a vedea dacă sunt ale altui user)
echo "<h2>3. Toate Serviciile din DB</h2>";
$allServices = DB::table('services')
    ->join('users', 'services.user_id', '=', 'users.id')
    ->select('services.*', 'users.name as user_name', 'users.email')
    ->orderBy('services.created_at', 'desc')
    ->limit(20)
    ->get();

echo "<table>";
echo "<tr><th>Service ID</th><th>Service Name</th><th>Owner ID</th><th>Owner Name</th><th>Owner Email</th><th>E al tău?</th></tr>";

foreach ($allServices as $service) {
    $isMine = ($service->user_id === $user->id);
    $mineClass = $isMine ? "success" : "";
    $mineText = $isMine ? "✅ DA" : "❌ NU";
    
    echo "<tr class='{$mineClass}'>";
    echo "<td>{$service->id}</td>";
    echo "<td>{$service->name}</td>";
    echo "<td>{$service->user_id}</td>";
    echo "<td>{$service->user_name}</td>";
    echo "<td>{$service->email}</td>";
    echo "<td><strong>{$mineText}</strong></td>";
    echo "</tr>";
}
echo "</table>";

// 4. Test delete permission pentru primul serviciu
echo "<h2>4. Test Delete Permission</h2>";
if ($services->count() > 0) {
    $testService = $services->first();
    echo "<p>Testăm delete pentru serviciul: <strong>{$testService->name}</strong> (ID: {$testService->id})</p>";
    
    echo "<table>";
    echo "<tr><th>Check</th><th>Valoare</th><th>Status</th></tr>";
    
    // Check 1: Ownership
    $ownershipOK = ($testService->user_id === $user->id);
    $ownershipStatus = $ownershipOK ? "✅ PASS" : "❌ FAIL";
    echo "<tr><td>Ownership</td><td>Service user_id ({$testService->user_id}) === Auth user_id ({$user->id})</td><td class='" . ($ownershipOK ? "success" : "error") . "'>{$ownershipStatus}</td></tr>";
    
    // Check 2: Role
    $isSpecialist = ($user->role === 'specialist');
    $roleStatus = $isSpecialist ? "✅ PASS" : "❌ FAIL";
    echo "<tr><td>Role</td><td>User role: {$user->role}</td><td class='" . ($isSpecialist ? "success" : "error") . "'>{$roleStatus}</td></tr>";
    
    // Check 3: Route exists
    try {
        $routeURL = route('specialist.services.destroy', $testService->id);
        echo "<tr><td>Route</td><td>{$routeURL}</td><td class='success'>✅ Există</td></tr>";
    } catch (Exception $e) {
        echo "<tr><td>Route</td><td>Error: {$e->getMessage()}</td><td class='error'>❌ NU EXISTĂ</td></tr>";
    }
    
    // Check 4: Method
    echo "<tr><td>Method</td><td>DELETE (via @method('DELETE'))</td><td class='success'>✅ OK</td></tr>";
    
    // Check 5: CSRF
    $csrfToken = csrf_token();
    echo "<tr><td>CSRF Token</td><td>" . substr($csrfToken, 0, 20) . "...</td><td class='success'>✅ Există</td></tr>";
    
    echo "</table>";
    
    // Verdict
    echo "<h3>📋 Verdict:</h3>";
    if ($ownershipOK && $isSpecialist) {
        echo "<p class='success' style='font-size:20px;'>✅ Delete AR TREBUI să funcționeze!</p>";
        echo "<p>Dacă primești 403, problema e în:</p>";
        echo "<ul>";
        echo "<li>Controller-ul încă verifică altceva (verifică linia 397 din SpecialistController)</li>";
        echo "<li>Middleware-ul specialist e mai restrictiv</li>";
        echo "<li>View cache-ul nu e cleared (rulează <code>php artisan view:clear</code>)</li>";
        echo "</ul>";
    } else {
        echo "<p class='error' style='font-size:20px;'>❌ Delete VA DA 403 pentru că:</p>";
        echo "<ul>";
        if (!$ownershipOK) echo "<li>Serviciul NU e al tău! (user_id mismatch)</li>";
        if (!$isSpecialist) echo "<li>Nu ai role de specialist!</li>";
        echo "</ul>";
    }
}

// 5. Verifică imaginile
echo "<h2>5. Status Imagini</h2>";
$storagePublic = __DIR__ . '/../storage/app/public';
$publicStorage = __DIR__ . '/storage';

echo "<table>";
echo "<tr><th>Check</th><th>Path</th><th>Status</th></tr>";

// Storage path
$storageExists = is_dir($storagePublic);
echo "<tr><td>Storage Dir</td><td>{$storagePublic}</td><td class='" . ($storageExists ? "success" : "error") . "'>" . ($storageExists ? "✅ Există" : "❌ Lipsă") . "</td></tr>";

// Public link
$linkExists = file_exists($publicStorage);
$isLink = is_link($publicStorage);
echo "<tr><td>Public Link</td><td>{$publicStorage}</td><td class='" . ($linkExists ? "success" : "error") . "'>" . ($linkExists ? "✅ Există" : "❌ Lipsă") . "</td></tr>";

if ($isLink) {
    $target = readlink($publicStorage);
    echo "<tr><td>Link Target</td><td>{$target}</td><td class='success'>✅ E symlink</td></tr>";
}

// Services folder
$servicesFolder = $storagePublic . '/services';
$servicesFolderExists = is_dir($servicesFolder);
echo "<tr><td>Services Folder</td><td>{$servicesFolder}</td><td class='" . ($servicesFolderExists ? "success" : "error") . "'>" . ($servicesFolderExists ? "✅ Există" : "❌ Lipsă") . "</td></tr>";

if ($servicesFolderExists) {
    $files = glob($servicesFolder . '/*');
    echo "<tr><td>Fișiere Services</td><td>" . count($files) . " fișiere</td><td class='success'>✅</td></tr>";
    
    if (count($files) > 0) {
        echo "</table>";
        echo "<h3>Exemple imagini:</h3><ul>";
        foreach (array_slice($files, 0, 5) as $file) {
            $basename = basename($file);
            $webPath = "/storage/services/{$basename}";
            $size = filesize($file);
            echo "<li><code>{$basename}</code> ({$size} bytes) → <a href='{$webPath}' target='_blank'>Test</a></li>";
        }
        echo "</ul>";
        echo "<table>";
    }
}

echo "</table>";

// 6. Test direct image URL
if ($services->count() > 0) {
    $serviceWithImage = $services->where('image', '!=', null)->first();
    if ($serviceWithImage) {
        echo "<h2>6. Test Direct Image</h2>";
        $imageURL = asset('storage/' . $serviceWithImage->image);
        echo "<p>Serviciu: <strong>{$serviceWithImage->name}</strong></p>";
        echo "<p>Image path in DB: <code>{$serviceWithImage->image}</code></p>";
        echo "<p>Full URL: <a href='{$imageURL}' target='_blank'>{$imageURL}</a></p>";
        
        $fullPath = $storagePublic . '/' . $serviceWithImage->image;
        if (file_exists($fullPath)) {
            echo "<p class='success'>✅ Fișierul există pe disk!</p>";
            echo "<p>Preview:</p>";
            echo "<img src='{$imageURL}' style='max-width:300px;border:2px solid #333;' alt='Test'>";
        } else {
            echo "<p class='error'>❌ Fișierul NU există pe disk la: {$fullPath}</p>";
            echo "<p>Verifică că imaginea a fost uploadată corect.</p>";
        }
    }
}

echo "<hr>";
echo "<p><strong>⚠️ ȘTERGE acest fișier după debug:</strong> <code>rm debug-delete.php</code></p>";
echo "</body></html>";
