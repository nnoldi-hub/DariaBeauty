<?php
/**
 * Diagnostic Script for Admin Panel 500 Error
 * Upload to: /home/ooxlvzey/public_html/public/check-admin-errors.php
 * Access: https://dariabeauty.ro/check-admin-errors.php
 */

set_time_limit(60);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Admin Error Diagnostic</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#00ff00}";
echo "h1,h2,h3{color:#00ffff}.error{color:#ff0000}.success{color:#00ff00}.warning{color:#ffaa00}";
echo "pre{background:#000;padding:10px;border:1px solid #333;overflow-x:auto}</style></head><body>";

echo "<h1>🔍 Admin Panel Error Diagnostic</h1>";
echo "<p>Checking for issues causing 500 error...</p><hr>";

$basePath = dirname(__DIR__);
$errors = [];
$warnings = [];

// 1. Check Laravel Error Log
echo "<h2>1. Laravel Error Log (Last 50 lines)</h2>";
$logFile = $basePath . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last50 = array_slice($lines, -50);
    echo "<pre>" . htmlspecialchars(implode('', $last50)) . "</pre>";
} else {
    echo "<p class='warning'>⚠️ No log file found at: $logFile</p>";
}

// 2. Check Required Files
echo "<h2>2. Checking Required Files</h2>";
$requiredFiles = [
    'app/Http/Controllers/Admin/SmsController.php',
    'app/Services/SmsService.php',
    'app/Models/SmsLog.php',
    'config/twilio.php',
    'resources/views/admin/sms/index.blade.php',
    'resources/views/admin/partials/sidebar.blade.php',
    'app/Console/Commands/SendAppointmentReminders.php',
];

foreach ($requiredFiles as $file) {
    $fullPath = $basePath . '/' . $file;
    if (file_exists($fullPath)) {
        echo "<p class='success'>✅ $file</p>";
    } else {
        echo "<p class='error'>❌ MISSING: $file</p>";
        $errors[] = "Missing file: $file";
    }
}

// 3. Check Database Tables
echo "<h2>3. Checking Database Tables</h2>";
try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    
    $tables = ['users', 'sms_logs', 'appointments', 'services'];
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "<p class='success'>✅ Table '$table' exists ($count rows)</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Table '$table' error: " . $e->getMessage() . "</p>";
            $errors[] = "Table $table: " . $e->getMessage();
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection error: " . $e->getMessage() . "</p>";
    $errors[] = "Database: " . $e->getMessage();
}

// 4. Check Routes
echo "<h2>4. Checking Admin Routes</h2>";
try {
    $routes = Artisan::call('route:list', ['--path' => 'admin']);
    echo "<pre>" . Artisan::output() . "</pre>";
    echo "<p class='success'>✅ Routes loaded successfully</p>";
} catch (Exception $e) {
    echo "<p class='error'>❌ Route error: " . $e->getMessage() . "</p>";
    $errors[] = "Routes: " . $e->getMessage();
}

// 5. Check Permissions
echo "<h2>5. Checking Directory Permissions</h2>";
$dirs = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache',
];

foreach ($dirs as $dir) {
    $fullPath = $basePath . '/' . $dir;
    if (is_writable($fullPath)) {
        echo "<p class='success'>✅ $dir is writable</p>";
    } else {
        echo "<p class='warning'>⚠️ $dir is NOT writable - permissions issue!</p>";
        $warnings[] = "Not writable: $dir";
    }
}

// 6. Check .env Configuration
echo "<h2>6. Checking .env Configuration</h2>";
$envVars = ['APP_DEBUG', 'APP_ENV', 'DB_CONNECTION', 'TWILIO_ENABLED'];
foreach ($envVars as $var) {
    $value = env($var);
    if ($value !== null) {
        $display = ($var === 'DB_PASSWORD' || strpos($var, 'KEY') !== false) ? '***' : $value;
        echo "<p class='success'>✅ $var = " . ($display === true ? 'true' : ($display === false ? 'false' : $display)) . "</p>";
    } else {
        echo "<p class='warning'>⚠️ $var not set</p>";
    }
}

// 7. Test AdminController Exists
echo "<h2>7. Testing AdminController</h2>";
$adminController = $basePath . '/app/Http/Controllers/AdminController.php';
if (file_exists($adminController)) {
    echo "<p class='success'>✅ AdminController exists</p>";
    try {
        require_once $adminController;
        echo "<p class='success'>✅ AdminController can be loaded</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ AdminController error: " . $e->getMessage() . "</p>";
        $errors[] = "AdminController: " . $e->getMessage();
    }
} else {
    echo "<p class='error'>❌ AdminController not found!</p>";
    $errors[] = "AdminController missing";
}

// 8. Summary
echo "<hr><h2>📊 Summary</h2>";

if (count($errors) > 0) {
    echo "<h3 class='error'>❌ ERRORS FOUND (" . count($errors) . "):</h3><ul>";
    foreach ($errors as $error) {
        echo "<li class='error'>$error</li>";
    }
    echo "</ul>";
}

if (count($warnings) > 0) {
    echo "<h3 class='warning'>⚠️ WARNINGS (" . count($warnings) . "):</h3><ul>";
    foreach ($warnings as $warning) {
        echo "<li class='warning'>$warning</li>";
    }
    echo "</ul>";
}

if (count($errors) === 0 && count($warnings) === 0) {
    echo "<p class='success' style='font-size:20px'>✅ No obvious errors found!</p>";
    echo "<p>The 500 error might be a routing or controller issue. Try:</p>";
    echo "<ol>";
    echo "<li>Access <a href='/admin/dashboard' style='color:#00ffff'>/admin/dashboard</a> directly</li>";
    echo "<li>Clear browser cache and cookies</li>";
    echo "<li>Check if you're logged in as admin</li>";
    echo "</ol>";
}

// Quick Fix Buttons
echo "<hr><h2>🔧 Quick Fixes</h2>";
echo "<form method='post' style='display:inline-block;margin-right:10px'>";
echo "<input type='hidden' name='action' value='clear_cache'>";
echo "<button type='submit' style='padding:10px;background:#00aa00;color:#fff;border:none;cursor:pointer'>Clear All Cache</button>";
echo "</form>";

echo "<form method='post' style='display:inline-block;margin-right:10px'>";
echo "<input type='hidden' name='action' value='fix_permissions'>";
echo "<button type='submit' style='padding:10px;background:#aa6600;color:#fff;border:none;cursor:pointer'>Fix Permissions</button>";
echo "</form>";

// Handle Quick Fixes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    echo "<hr><h3>Running Fix...</h3>";
    
    if ($_POST['action'] === 'clear_cache') {
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            echo "<p class='success'>✅ All caches cleared!</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
    
    if ($_POST['action'] === 'fix_permissions') {
        foreach ($dirs as $dir) {
            $fullPath = $basePath . '/' . $dir;
            chmod($fullPath, 0755);
        }
        echo "<p class='success'>✅ Permissions updated!</p>";
    }
}

echo "<hr><p>Diagnostic completed at: " . date('Y-m-d H:i:s') . "</p>";
echo "<p class='warning'>⚠️ Remember to DELETE this file after fixing the issue!</p>";
echo "</body></html>";
