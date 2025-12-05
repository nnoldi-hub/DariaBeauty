<?php
/**
 * Direct Error Test - Test SMS Controller directly
 * Upload to: /home/ooxlvzey/public_html/public/test-sms-direct.php
 * Access: https://dariabeauty.ro/test-sms-direct.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Direct SMS Test</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#00ff00;font-size:14px}";
echo ".error{color:#ff0000;font-weight:bold;background:#330000;padding:15px;margin:10px 0;border:2px solid #ff0000}";
echo ".success{color:#00ff00;background:#003300;padding:10px;margin:10px 0}";
echo ".info{color:#00ffff}pre{background:#000;padding:10px;white-space:pre-wrap}</style></head><body>";

echo "<h1 style='color:#00ffff'>🔍 Direct SMS Controller Test</h1><hr>";

$basePath = dirname(__DIR__);

try {
    echo "<p class='info'>Step 1: Loading Laravel...</p>";
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    echo "<p class='success'>✅ Laravel loaded</p>";
    
    echo "<p class='info'>Step 2: Checking SmsService.php exists...</p>";
    $smsServicePath = $basePath . '/app/Services/SmsService.php';
    if (file_exists($smsServicePath)) {
        echo "<p class='success'>✅ SmsService.php exists (" . filesize($smsServicePath) . " bytes)</p>";
    } else {
        echo "<p class='error'>❌ SmsService.php NOT FOUND at: $smsServicePath</p>";
    }
    
    echo "<p class='info'>Step 3: Trying to load SmsService class...</p>";
    try {
        $reflector = new ReflectionClass('App\Services\SmsService');
        echo "<p class='success'>✅ SmsService class found at: " . $reflector->getFileName() . "</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Cannot load SmsService class: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    echo "<p class='info'>Step 4: Checking SmsLog model...</p>";
    $smsLogPath = $basePath . '/app/Models/SmsLog.php';
    if (file_exists($smsLogPath)) {
        echo "<p class='success'>✅ SmsLog.php exists</p>";
        try {
            $reflector = new ReflectionClass('App\Models\SmsLog');
            echo "<p class='success'>✅ SmsLog class loaded</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Cannot load SmsLog: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>❌ SmsLog.php NOT FOUND at: $smsLogPath</p>";
    }
    
    echo "<p class='info'>Step 5: Checking SmsController...</p>";
    $controllerPath = $basePath . '/app/Http/Controllers/Admin/SmsController.php';
    if (file_exists($controllerPath)) {
        echo "<p class='success'>✅ SmsController.php exists</p>";
        try {
            require_once $controllerPath;
            echo "<p class='success'>✅ SmsController loaded</p>";
            
            // Try to instantiate (will fail without SmsService, but will show the real error)
            echo "<p class='info'>Step 6: Trying to create SmsController instance...</p>";
            
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            $kernel->bootstrap();
            
            $smsService = app('App\Services\SmsService');
            echo "<p class='success'>✅ SmsService instantiated via container</p>";
            
            $controller = new \App\Http\Controllers\Admin\SmsController($smsService);
            echo "<p class='success'>✅ SmsController instantiated successfully!</p>";
            echo "<p class='success' style='font-size:18px'>🎉 ALL CHECKS PASSED! The controller should work now.</p>";
            echo "<p class='info'>Try accessing <a href='/admin/sms' style='color:#00ffff'>/admin/sms</a> again</p>";
            
        } catch (Exception $e) {
            echo "<div class='error'>";
            echo "<h3>❌ ERROR CREATING CONTROLLER:</h3>";
            echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
            echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
            echo "</div>";
        }
    } else {
        echo "<p class='error'>❌ SmsController.php NOT FOUND at: $controllerPath</p>";
    }
    
    echo "<hr><p class='info'>Step 7: Checking config/twilio.php...</p>";
    $twilioConfig = $basePath . '/config/twilio.php';
    if (file_exists($twilioConfig)) {
        echo "<p class='success'>✅ twilio.php config exists</p>";
    } else {
        echo "<p class='error'>❌ twilio.php NOT FOUND - need to upload it!</p>";
    }
    
    echo "<hr><p class='info'>Step 8: Checking view file...</p>";
    $viewPath = $basePath . '/resources/views/admin/sms/index.blade.php';
    if (file_exists($viewPath)) {
        echo "<p class='success'>✅ admin/sms/index.blade.php view exists</p>";
    } else {
        echo "<p class='error'>❌ View NOT FOUND at: $viewPath</p>";
        echo "<p class='info'>Need to create directory and upload view!</p>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h2>❌ FATAL ERROR:</h2>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<hr><p class='error'>⚠️ DELETE after: rm /home/ooxlvzey/public_html/public/test-sms-direct.php</p>";
echo "</body></html>";
