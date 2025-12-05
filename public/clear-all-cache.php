<?php
/**
 * Clear All Laravel Cache on Hostico - Direct File Method
 * Run this script once via browser: https://dariabeauty.ro/clear-all-cache.php
 */

set_time_limit(300); // 5 minutes max
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🧹 Laravel Cache Cleaner pentru Hostico</h1>";
echo "<p>Curățare cache-uri Laravel prin ștergere directă fișiere...</p>";

// Change to Laravel root directory
$basePath = dirname(__DIR__);
chdir($basePath);

echo "<p><strong>Base Path:</strong> " . $basePath . "</p><hr>";

$results = [];
$deleted = 0;

// 1. Clear route cache files
echo "<h3>1. Route Cache</h3>";
$routeCacheFile = $basePath . '/bootstrap/cache/routes-v7.php';
if (file_exists($routeCacheFile)) {
    if (unlink($routeCacheFile)) {
        echo "<p>✅ Șters: routes-v7.php</p>";
        $deleted++;
    } else {
        echo "<p>❌ Nu s-a putut șterge: routes-v7.php</p>";
    }
} else {
    echo "<p>ℹ️ Nu există: routes-v7.php</p>";
}

// 2. Clear config cache
echo "<h3>2. Config Cache</h3>";
$configCacheFile = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCacheFile)) {
    if (unlink($configCacheFile)) {
        echo "<p>✅ Șters: config.php</p>";
        $deleted++;
    } else {
        echo "<p>❌ Nu s-a putut șterge: config.php</p>";
    }
} else {
    echo "<p>ℹ️ Nu există: config.php</p>";
}

// 3. Clear services cache
echo "<h3>3. Services Cache</h3>";
$servicesCacheFile = $basePath . '/bootstrap/cache/services.php';
if (file_exists($servicesCacheFile)) {
    if (unlink($servicesCacheFile)) {
        echo "<p>✅ Șters: services.php</p>";
        $deleted++;
    } else {
        echo "<p>❌ Nu s-a putut șterge: services.php</p>";
    }
} else {
    echo "<p>ℹ️ Nu există: services.php</p>";
}

// 4. Clear compiled views
echo "<h3>4. Compiled Views Cache</h3>";
$viewsPath = $basePath . '/storage/framework/views';
$viewFiles = glob($viewsPath . '/*.php');
if ($viewFiles) {
    foreach ($viewFiles as $file) {
        if (unlink($file)) {
            $deleted++;
        }
    }
    echo "<p>✅ Șterse " . count($viewFiles) . " view-uri compilate</p>";
} else {
    echo "<p>ℹ️ Nu există view-uri compilate</p>";
}

// 5. Clear application cache
echo "<h3>5. Application Cache</h3>";
$cachePath = $basePath . '/storage/framework/cache/data';
if (is_dir($cachePath)) {
    $cacheFiles = glob($cachePath . '/*/*');
    if ($cacheFiles) {
        foreach ($cacheFiles as $file) {
            if (is_file($file) && unlink($file)) {
                $deleted++;
            }
        }
        echo "<p>✅ Șterse " . count($cacheFiles) . " fișiere cache</p>";
    } else {
        echo "<p>ℹ️ Cache gol</p>";
    }
} else {
    echo "<p>ℹ️ Directorul cache nu există</p>";
}

// Summary
echo "<hr>";
echo "<h2>📊 Sumar</h2>";
echo "<p><strong>Total fișiere șterse:</strong> {$deleted}</p>";

echo "<h2 style='color: green;'>✅ Cache-urile au fost curățate!</h2>";
echo "<p>Acum încearcă să accesezi pagina: <a href='/specialisti' target='_blank'>https://dariabeauty.ro/specialisti</a></p>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Șterge acest fișier după utilizare pentru securitate!</p>";
echo "<p>Via cPanel File Manager sau: <code>rm /home/ooxlvzey/public_html/public/clear-all-cache.php</code></p>";
