<?php
/**
 * Quick Fix Script for SMS Controller 500 Error
 * Upload to: /home/ooxlvzey/public_html/public/fix-sms.php
 * Access: https://dariabeauty.ro/fix-sms.php
 */

set_time_limit(60);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Fix SMS Error</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#00ff00}";
echo "h1,h2,h3{color:#00ffff}.error{color:#ff0000}.success{color:#00ff00}.warning{color:#ffaa00}";
echo "pre{background:#000;padding:10px;border:1px solid #333;overflow-x:auto}";
echo ".file-missing{background:#330000;padding:10px;margin:5px 0;border-left:3px solid #ff0000}";
echo ".file-exists{background:#003300;padding:5px;margin:2px 0;border-left:3px solid #00ff00}</style></head><body>";

echo "<h1>🔧 SMS Controller Error Fix</h1>";
echo "<p>Checking what's missing for SMS functionality...</p><hr>";

$basePath = dirname(__DIR__);
$missing = [];
$exists = [];

// Check all required files for SMS
$requiredFiles = [
    'app/Http/Controllers/Admin/SmsController.php' => 'SMS Admin Controller',
    'app/Services/SmsService.php' => 'SMS Service',
    'app/Models/SmsLog.php' => 'SMS Log Model',
    'config/twilio.php' => 'Twilio Config',
    'resources/views/admin/sms/index.blade.php' => 'SMS Admin View',
    'app/Console/Commands/SendAppointmentReminders.php' => 'Reminder Command',
];

echo "<h2>📁 File Check</h2>";

foreach ($requiredFiles as $file => $description) {
    $fullPath = $basePath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "<div class='file-exists'>✅ $description: <code>$file</code></div>";
        $exists[] = $file;
    } else {
        echo "<div class='file-missing'>❌ MISSING: $description<br><code>$file</code></div>";
        $missing[] = [$file, $description];
    }
}

// Check database
echo "<hr><h2>🗄️ Database Check</h2>";
try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    
    $tables = ['sms_logs', 'settings'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "<p class='success'>✅ Table '$table' exists ($count rows)</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Table '$table' missing - run migrations!</p>";
            $missing[] = ["Database: $table", "Run: php artisan migrate"];
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Check routes
echo "<hr><h2>🛣️ Route Check</h2>";
try {
    $routes = Route::getRoutes();
    $smsRoutes = 0;
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'admin/sms') !== false) {
            $smsRoutes++;
        }
    }
    
    if ($smsRoutes > 0) {
        echo "<p class='success'>✅ Found $smsRoutes SMS routes registered</p>";
    } else {
        echo "<p class='error'>❌ No SMS routes found - check routes/web.php</p>";
        $missing[] = ['routes/web.php', 'SMS routes not registered'];
    }
} catch (Exception $e) {
    echo "<p class='warning'>⚠️ Could not check routes: " . $e->getMessage() . "</p>";
}

// Check Laravel logs
echo "<hr><h2>📋 Recent Laravel Errors</h2>";
$logFile = $basePath . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last20 = array_slice($lines, -20);
    echo "<pre style='max-height:300px;overflow-y:auto'>" . htmlspecialchars(implode('', $last20)) . "</pre>";
} else {
    echo "<p class='warning'>⚠️ No log file found</p>";
}

// Summary
echo "<hr><h2>📊 Summary</h2>";

if (count($missing) > 0) {
    echo "<h3 class='error'>❌ MISSING FILES/TABLES (" . count($missing) . "):</h3>";
    echo "<div style='background:#330000;padding:15px;border:2px solid #ff0000;margin:10px 0'>";
    echo "<p style='color:#ffaa00;font-size:18px'><strong>⚠️ YOU NEED TO UPLOAD THESE FILES:</strong></p>";
    echo "<ol>";
    foreach ($missing as $item) {
        echo "<li><strong>{$item[1]}</strong><br><code style='color:#00ffff'>{$item[0]}</code></li>";
    }
    echo "</ol>";
    echo "</div>";
} else {
    echo "<p class='success' style='font-size:20px'>✅ All files exist!</p>";
}

// Action buttons
echo "<hr><h2>🔧 Quick Actions</h2>";

echo "<form method='post' style='display:inline-block;margin:10px'>";
echo "<input type='hidden' name='action' value='clear_cache'>";
echo "<button type='submit' style='padding:15px 30px;background:#00aa00;color:#fff;border:none;cursor:pointer;font-size:16px'>Clear All Cache</button>";
echo "</form>";

echo "<form method='post' style='display:inline-block;margin:10px'>";
echo "<input type='hidden' name='action' value='run_migrations'>";
echo "<button type='submit' style='padding:15px 30px;background:#0066aa;color:#fff;border:none;cursor:pointer;font-size:16px'>Run Migrations</button>";
echo "</form>";

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    echo "<hr><h3>Running Action...</h3>";
    
    if ($_POST['action'] === 'clear_cache') {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            echo "<p class='success'>✅ All caches cleared! Try accessing /admin/sms again.</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
    
    if ($_POST['action'] === 'run_migrations') {
        try {
            echo "<pre>";
            Artisan::call('migrate', ['--force' => true]);
            echo Artisan::output();
            echo "</pre>";
            echo "<p class='success'>✅ Migrations completed!</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
}

// Upload instructions
if (count($missing) > 0) {
    echo "<hr><h2>📤 Upload Instructions</h2>";
    echo "<ol>";
    echo "<li>Go to cPanel → File Manager</li>";
    echo "<li>Navigate to: <code>/home/ooxlvzey/public_html/</code></li>";
    echo "<li>Upload missing files to their exact locations</li>";
    echo "<li>Click 'Clear All Cache' button above</li>";
    echo "<li>Try <a href='/admin/sms' style='color:#00ffff'>admin/sms</a> again</li>";
    echo "</ol>";
}

echo "<hr><p class='warning'>⚠️ DELETE this file after fixing: <code>rm /home/ooxlvzey/public_html/public/fix-sms.php</code></p>";
echo "<p>Generated at: " . date('Y-m-d H:i:s') . "</p>";
echo "</body></html>";
