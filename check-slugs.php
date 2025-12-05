<?php
/**
 * CHECK SPECIALISTS SLUGS
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/check-slugs.php
 * ȘTERGE DUPĂ FOLOSIRE!
 */

$rootDir = dirname(__DIR__);
chdir($rootDir);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check Specialists Slugs</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; background: #252526; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #444; }
        th { background: #007acc; color: white; }
        button { background: #007acc; color: white; border: none; padding: 10px 20px; cursor: pointer; margin: 5px; }
        button:hover { background: #005a9e; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>🔍 Check Specialists Slugs</h1>

<?php
try {
    require $rootDir.'/vendor/autoload.php';
    $app = require_once $rootDir.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $db = $app->make('db');
    
    $specialists = $db->table('users')
        ->where('role', 'specialist')
        ->select('id', 'name', 'email', 'slug', 'is_active')
        ->get();
    
    echo "<p class='success'>✓ Found " . count($specialists) . " specialists</p>";
    
    $hasIssues = false;
    
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Slug</th><th>Active</th><th>Status</th></tr>";
    
    foreach ($specialists as $spec) {
        $status = '';
        $statusClass = 'success';
        
        if (empty($spec->slug)) {
            $status = '✗ SLUG LIPSEȘTE!';
            $statusClass = 'error';
            $hasIssues = true;
        } else {
            $status = '✓ OK';
        }
        
        echo "<tr>";
        echo "<td>" . $spec->id . "</td>";
        echo "<td>" . htmlspecialchars($spec->name) . "</td>";
        echo "<td>" . htmlspecialchars($spec->email) . "</td>";
        echo "<td><strong>" . htmlspecialchars($spec->slug ?: 'NULL') . "</strong></td>";
        echo "<td>" . ($spec->is_active ? '✓ Activ' : '✗ Inactiv') . "</td>";
        echo "<td class='$statusClass'>$status</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    if ($hasIssues) {
        echo "<hr>";
        echo "<h2 class='error'>⚠️ Unii specialiști nu au SLUG!</h2>";
        echo "<p>Slug-ul este necesar pentru a genera URL-ul profilului public (ex: /specialisti/daria-nyikora)</p>";
        echo "<form method='POST'>";
        echo "<button type='submit' name='generate_slugs' style='background:#f48771; font-size:16px; padding:15px 30px;'>🔧 Generează Slug-uri Lipsă</button>";
        echo "</form>";
    } else {
        echo "<hr>";
        echo "<h2 class='success'>✓ Toți specialiștii au slug-uri!</h2>";
    }
    
    // Generate slugs
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_slugs'])) {
        echo "<hr><h2>Generare Slug-uri...</h2>";
        
        $specialistsWithoutSlug = $db->table('users')
            ->where('role', 'specialist')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get();
        
        foreach ($specialistsWithoutSlug as $spec) {
            $slug = \Illuminate\Support\Str::slug($spec->name);
            
            // Check if slug exists
            $count = 1;
            $originalSlug = $slug;
            while ($db->table('users')->where('slug', $slug)->where('id', '!=', $spec->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            $db->table('users')->where('id', $spec->id)->update(['slug' => $slug]);
            
            echo "<p class='success'>✓ Generated slug for <strong>" . htmlspecialchars($spec->name) . "</strong>: <strong>$slug</strong></p>";
        }
        
        echo "<hr>";
        echo "<p class='success'><strong>✓ Slug-uri generate cu succes!</strong></p>";
        echo "<p><a href='?'><button>Refresh pentru verificare</button></a></p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<hr>
<p class='error'><strong>ȘTERGE ACEST FIȘIER DUPĂ VERIFICARE!</strong></p>

</body>
</html>
