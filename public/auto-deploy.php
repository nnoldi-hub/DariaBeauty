<?php
/**
 * Auto Deploy Script for DariaBeauty
 * Upload this file to /home/ooxlvzey/public_html/public/
 * Access: https://dariabeauty.ro/auto-deploy.php
 * 
 * This script will:
 * 1. Update .env with Twilio config
 * 2. Run database migrations
 * 3. Clear all caches
 * 4. Report success
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Auto Deploy - DariaBeauty</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5}";
echo ".success{color:green}.error{color:red}.info{color:blue}</style></head><body>";

echo "<h1>🚀 Auto Deploy Script</h1>";
echo "<p class='info'>Starting deployment process...</p><hr>";

$basePath = dirname(__DIR__);
chdir($basePath);

$errors = [];
$success = [];

// Step 1: Check if .env needs Twilio config
echo "<h3>Step 1: Checking .env Configuration</h3>";
$envPath = $basePath . '/.env';
$envContent = file_get_contents($envPath);

if (strpos($envContent, 'TWILIO_SID') === false) {
    echo "<p class='info'>Adding Twilio configuration to .env...</p>";
    
    $twilioConfig = "\n# Twilio SMS Configuration\n";
    $twilioConfig .= "TWILIO_SID=\n";
    $twilioConfig .= "TWILIO_AUTH_TOKEN=\n";
    $twilioConfig .= "TWILIO_PHONE_NUMBER=\n";
    $twilioConfig .= "TWILIO_ENABLED=false\n";
    
    if (file_put_contents($envPath, $envContent . $twilioConfig)) {
        echo "<p class='success'>✅ Twilio config added to .env</p>";
        $success[] = "Twilio config added";
    } else {
        echo "<p class='error'>❌ Failed to update .env</p>";
        $errors[] = ".env update failed";
    }
} else {
    echo "<p class='success'>✅ Twilio config already exists in .env</p>";
    $success[] = ".env already configured";
}

// Step 2: Run Migrations
echo "<h3>Step 2: Running Database Migrations</h3>";
try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    
    echo "<pre>";
    Artisan::call('migrate', ['--force' => true]);
    echo Artisan::output();
    echo "</pre>";
    
    echo "<p class='success'>✅ Migrations completed</p>";
    $success[] = "Database migrations";
} catch (Exception $e) {
    echo "<p class='error'>❌ Migration error: " . $e->getMessage() . "</p>";
    $errors[] = "Migration: " . $e->getMessage();
}

// Step 3: Clear Config Cache
echo "<h3>Step 3: Clearing Configuration Cache</h3>";
try {
    Artisan::call('config:clear');
    echo "<pre>" . Artisan::output() . "</pre>";
    echo "<p class='success'>✅ Config cache cleared</p>";
    $success[] = "Config cache cleared";
} catch (Exception $e) {
    echo "<p class='error'>❌ Config clear error: " . $e->getMessage() . "</p>";
    $errors[] = "Config cache: " . $e->getMessage();
}

// Step 4: Clear View Cache
echo "<h3>Step 4: Clearing View Cache</h3>";
try {
    Artisan::call('view:clear');
    echo "<pre>" . Artisan::output() . "</pre>";
    echo "<p class='success'>✅ View cache cleared</p>";
    $success[] = "View cache cleared";
} catch (Exception $e) {
    echo "<p class='error'>❌ View clear error: " . $e->getMessage() . "</p>";
    $errors[] = "View cache: " . $e->getMessage();
}

// Step 5: Clear Route Cache
echo "<h3>Step 5: Clearing Route Cache</h3>";
try {
    Artisan::call('route:clear');
    echo "<pre>" . Artisan::output() . "</pre>";
    echo "<p class='success'>✅ Route cache cleared</p>";
    $success[] = "Route cache cleared";
} catch (Exception $e) {
    echo "<p class='error'>❌ Route clear error: " . $e->getMessage() . "</p>";
    $errors[] = "Route cache: " . $e->getMessage();
}

// Step 6: Rebuild Route Cache
echo "<h3>Step 6: Rebuilding Route Cache</h3>";
try {
    Artisan::call('route:cache');
    echo "<pre>" . Artisan::output() . "</pre>";
    echo "<p class='success'>✅ Route cache rebuilt</p>";
    $success[] = "Route cache rebuilt";
} catch (Exception $e) {
    echo "<p class='error'>❌ Route cache error: " . $e->getMessage() . "</p>";
    $errors[] = "Route cache rebuild: " . $e->getMessage();
}

// Summary
echo "<hr><h2>📊 Deployment Summary</h2>";

echo "<h3 class='success'>✅ Successful Operations (" . count($success) . "):</h3>";
echo "<ul>";
foreach ($success as $item) {
    echo "<li>$item</li>";
}
echo "</ul>";

if (count($errors) > 0) {
    echo "<h3 class='error'>❌ Errors (" . count($errors) . "):</h3>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='success' style='font-size:20px;font-weight:bold'>🎉 ALL OPERATIONS COMPLETED SUCCESSFULLY!</p>";
}

// Next Steps
echo "<hr><h2>📋 Next Steps:</h2>";
echo "<ol>";
echo "<li>Test the site: <a href='/' target='_blank'>https://dariabeauty.ro</a></li>";
echo "<li>Test specialist registration: <a href='/inregistrare-specialist' target='_blank'>Formular Specialist</a></li>";
echo "<li>Test gallery: <a href='/galerie' target='_blank'>Galerie</a></li>";
echo "<li>Access admin SMS panel: <a href='/admin/sms' target='_blank'>Admin SMS</a></li>";
echo "<li><strong class='error'>DELETE this script for security!</strong> <code>rm /home/ooxlvzey/public_html/public/auto-deploy.php</code></li>";
echo "</ol>";

echo "<hr><p class='info'>Deployment completed at: " . date('Y-m-d H:i:s') . "</p>";
echo "</body></html>";
