<?php
/**
 * Fix Profile Link in specialists/index.blade.php
 * Run via browser: https://dariabeauty.ro/fix-profile-link.php
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔧 Fix Profile Link - specialists/index.blade.php</h1>";

$basePath = dirname(__DIR__);
$filePath = $basePath . '/resources/views/specialists/index.blade.php';

echo "<p><strong>File:</strong> {$filePath}</p>";

if (!file_exists($filePath)) {
    die("<p style='color:red'>❌ Fișierul nu există!</p>");
}

// Backup
$backupPath = $filePath . '.backup-' . date('Y-m-d-His');
copy($filePath, $backupPath);
echo "<p>✅ Backup creat: " . basename($backupPath) . "</p>";

// Read file
$content = file_get_contents($filePath);
echo "<p>📄 Fișier citit: " . number_format(strlen($content)) . " bytes</p>";

// Check current state
if (strpos($content, "route('specialist.profile')") !== false) {
    echo "<p style='color:orange'>⚠️ Găsit link VECHI: route('specialist.profile')</p>";
    $hasOldLink = true;
} else {
    echo "<p style='color:green'>✅ Nu există link vechi route('specialist.profile')</p>";
    $hasOldLink = false;
}

if (strpos($content, "route('specialists.show'") !== false) {
    echo "<p style='color:green'>✅ Găsit link NOU: route('specialists.show')</p>";
    $hasNewLink = true;
} else {
    echo "<p style='color:orange'>⚠️ Lipsește link NOU route('specialists.show')</p>";
    $hasNewLink = false;
}

echo "<hr>";

// Apply fixes if needed
$changes = 0;

// Pattern 1: Fix old profile link
$pattern1 = "/route\(['\"]specialist\.profile['\"]\)/";
$replacement1 = "route('specialists.show', \$specialist->slug)";
if (preg_match($pattern1, $content)) {
    $content = preg_replace($pattern1, $replacement1, $content);
    $changes++;
    echo "<p>✅ Înlocuit: route('specialist.profile') → route('specialists.show', \$specialist->slug)</p>";
}

// Pattern 2: Fix href to specialist profile without slug
$pattern2 = "/<a\s+href=\"{{\s*route\(['\"]specialist\.profile['\"]\)\s*}}\"/";
$replacement2 = "<a href=\"{{ route('specialists.show', \$specialist->slug) }}\"";
if (preg_match($pattern2, $content)) {
    $content = preg_replace($pattern2, $replacement2, $content);
    $changes++;
    echo "<p>✅ Înlocuit href către profil cu slug</p>";
}

if ($changes > 0) {
    // Write back
    file_put_contents($filePath, $content);
    echo "<hr>";
    echo "<h2 style='color:green'>✅ Fișier actualizat cu succes!</h2>";
    echo "<p><strong>Total modificări:</strong> {$changes}</p>";
    
    // Clear view cache
    $viewsPath = $basePath . '/storage/framework/views';
    $viewFiles = glob($viewsPath . '/*.php');
    if ($viewFiles) {
        foreach ($viewFiles as $file) {
            unlink($file);
        }
        echo "<p>✅ Cache view-uri curățat (" . count($viewFiles) . " fișiere)</p>";
    }
    
} else {
    echo "<h2 style='color:blue'>ℹ️ Nu sunt modificări necesare</h2>";
    echo "<p>Fișierul pare să fie deja actualizat.</p>";
}

echo "<hr>";
echo "<h3>🧪 Test</h3>";
echo "<p>Acum testează: <a href='/specialisti' target='_blank'>https://dariabeauty.ro/specialisti</a></p>";
echo "<p>Dă click pe butonul <strong>Profil</strong> - ar trebui să meargă la profilul public, NU la login!</p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Șterge acest script după utilizare!</p>";
echo "<p><code>rm /home/ooxlvzey/public_html/public/fix-profile-link.php</code></p>";
