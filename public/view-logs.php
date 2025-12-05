<?php
/**
 * View Laravel Logs
 * Upload to: /home/ooxlvzey/public_html/public/view-logs.php
 * Access: https://dariabeauty.ro/view-logs.php
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Laravel Logs</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#00ff00;font-size:12px}";
echo "pre{background:#000;padding:15px;border:1px solid #333;overflow-x:auto;white-space:pre-wrap}";
echo ".error{color:#ff0000}.warning{color:#ffaa00}.info{color:#00ffff}</style></head><body>";

echo "<h1 style='color:#00ffff'>📋 Laravel Error Logs</h1>";
echo "<p class='info'>Last 100 lines from laravel.log</p><hr>";

$basePath = dirname(__DIR__);
$logFile = $basePath . '/storage/logs/laravel.log';

if (file_exists($logFile)) {
    $lines = file($logFile);
    $last100 = array_slice($lines, -100);
    
    echo "<pre>";
    foreach ($last100 as $line) {
        if (strpos($line, 'ERROR') !== false || strpos($line, 'Exception') !== false) {
            echo "<span class='error'>" . htmlspecialchars($line) . "</span>";
        } elseif (strpos($line, 'WARNING') !== false) {
            echo "<span class='warning'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
    
    echo "<hr><p>Log file size: " . number_format(filesize($logFile) / 1024, 2) . " KB</p>";
    echo "<p>Last modified: " . date('Y-m-d H:i:s', filemtime($logFile)) . "</p>";
} else {
    echo "<p class='error'>❌ Log file not found at: $logFile</p>";
    echo "<p class='warning'>Checking alternative locations...</p>";
    
    $altPaths = [
        $basePath . '/storage/logs/',
        $basePath . '/storage/',
    ];
    
    foreach ($altPaths as $path) {
        if (is_dir($path)) {
            echo "<p class='info'>Found directory: $path</p>";
            $files = scandir($path);
            echo "<ul>";
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    echo "<li>$file</li>";
                }
            }
            echo "</ul>";
        }
    }
}

// Try to trigger the error and catch it
echo "<hr><h2 style='color:#00ffff'>🔍 Testing SMS Controller</h2>";
try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    echo "<p class='info'>Attempting to load SmsController...</p>";
    
    $controllerPath = $basePath . '/app/Http/Controllers/Admin/SmsController.php';
    if (file_exists($controllerPath)) {
        echo "<p style='color:#00ff00'>✅ SmsController file exists</p>";
        
        // Try to instantiate
        require_once $controllerPath;
        echo "<p style='color:#00ff00'>✅ SmsController loaded successfully</p>";
        
        // Check if SmsService exists
        $servicePath = $basePath . '/app/Services/SmsService.php';
        if (file_exists($servicePath)) {
            echo "<p style='color:#00ff00'>✅ SmsService file exists</p>";
            require_once $servicePath;
            echo "<p style='color:#00ff00'>✅ SmsService loaded successfully</p>";
        } else {
            echo "<p class='error'>❌ SmsService file NOT found at: $servicePath</p>";
        }
        
    } else {
        echo "<p class='error'>❌ SmsController file NOT found at: $controllerPath</p>";
    }
    
} catch (Exception $e) {
    echo "<pre class='error'>";
    echo "❌ ERROR CAUGHT:\n\n";
    echo "Message: " . $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . $e->getTraceAsString();
    echo "</pre>";
}

echo "<hr><form method='post'>";
echo "<button type='submit' name='clear' style='padding:10px 20px;background:#00aa00;color:#fff;border:none;cursor:pointer'>Clear Laravel Log</button>";
echo "</form>";

if (isset($_POST['clear'])) {
    if (file_exists($logFile)) {
        file_put_contents($logFile, '');
        echo "<p class='info'>✅ Log file cleared!</p>";
    }
}

echo "<hr><p class='warning'>⚠️ DELETE this file after: rm /home/ooxlvzey/public_html/public/view-logs.php</p>";
echo "</body></html>";
