<?php
/**
 * Generate Slugs for All Specialists
 * Run via browser: https://dariabeauty.ro/generate-slugs.php
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔧 Generate Slugs pentru Specialiști</h1>";

// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

echo "<p>✅ Laravel încărcat</p><hr>";

// Get all specialists without slugs
$specialists = DB::table('users')
    ->where('role', 'specialist')
    ->get();

echo "<h3>📋 Specialiști găsiți: " . $specialists->count() . "</h3>";

if ($specialists->isEmpty()) {
    die("<p style='color:orange'>⚠️ Nu există specialiști în baza de date</p>");
}

// Function to generate slug
function generateSlug($name, $id) {
    // Convert to lowercase
    $slug = strtolower($name);
    
    // Replace Romanian characters
    $slug = str_replace(
        ['ă', 'â', 'î', 'ș', 'ț', 'Ă', 'Â', 'Î', 'Ș', 'Ț'],
        ['a', 'a', 'i', 's', 't', 'a', 'a', 'i', 's', 't'],
        $slug
    );
    
    // Remove special characters
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    
    // Replace spaces with hyphens
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    
    // Trim hyphens
    $slug = trim($slug, '-');
    
    return $slug;
}

$updated = 0;
$skipped = 0;

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; margin-top: 20px;'>";
echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Nume</th><th>Slug Vechi</th><th>Slug Nou</th><th>Status</th></tr>";

foreach ($specialists as $specialist) {
    $oldSlug = $specialist->slug ?? '';
    
    // Generate new slug from name
    $name = $specialist->name ?? 'specialist-' . $specialist->id;
    $newSlug = generateSlug($name, $specialist->id);
    
    // Check if slug already exists for another user
    $existingSlug = DB::table('users')
        ->where('slug', $newSlug)
        ->where('id', '!=', $specialist->id)
        ->exists();
    
    if ($existingSlug) {
        // Add ID to make it unique
        $newSlug = $newSlug . '-' . $specialist->id;
    }
    
    echo "<tr>";
    echo "<td>{$specialist->id}</td>";
    echo "<td>{$specialist->name}</td>";
    echo "<td>" . ($oldSlug ?: '<em style="color: gray;">null</em>') . "</td>";
    echo "<td><strong>{$newSlug}</strong></td>";
    
    // Update if slug is missing or different
    if (empty($oldSlug) || $oldSlug !== $newSlug) {
        DB::table('users')
            ->where('id', $specialist->id)
            ->update(['slug' => $newSlug]);
        
        echo "<td style='color: green;'>✅ Actualizat</td>";
        $updated++;
    } else {
        echo "<td style='color: blue;'>ℹ️ OK</td>";
        $skipped++;
    }
    
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h2>📊 Sumar</h2>";
echo "<p>✅ <strong>Actualizați:</strong> {$updated} specialiști</p>";
echo "<p>ℹ️ <strong>Săriți (OK):</strong> {$skipped} specialiști</p>";

if ($updated > 0) {
    echo "<h2 style='color: green;'>✅ Slug-uri generate cu succes!</h2>";
    
    // Clear view cache
    $viewsPath = dirname(__DIR__) . '/storage/framework/views';
    $viewFiles = glob($viewsPath . '/*.php');
    if ($viewFiles) {
        foreach ($viewFiles as $file) {
            unlink($file);
        }
        echo "<p>✅ Cache view-uri curățat (" . count($viewFiles) . " fișiere)</p>";
    }
}

echo "<hr>";
echo "<h3>🧪 Test</h3>";
echo "<p>Acum testează: <a href='/specialisti' target='_blank'>https://dariabeauty.ro/specialisti</a></p>";
echo "<p>Dă click pe <strong>Profil</strong> - ar trebui să meargă la URL-ul cu slug (ex: /specialisti/daria-nyikora)</p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Șterge acest script după utilizare!</p>";
echo "<p><code>rm /home/ooxlvzey/public_html/public/generate-slugs.php</code></p>";
