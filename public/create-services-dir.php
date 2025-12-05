<?php
/**
 * Create Services Directory and Check Structure
 * Upload to: /home/ooxlvzey/public_html/public/create-services-dir.php
 * Access: https://dariabeauty.ro/create-services-dir.php
 */

set_time_limit(60);
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Create Services Directory</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#00ff00}";
echo ".error{color:#ff0000}.success{color:#00ff00}.info{color:#00ffff}</style></head><body>";

echo "<h1 style='color:#00ffff'>📁 Directory Structure Fix</h1><hr>";

$basePath = dirname(__DIR__);
$servicesDir = $basePath . '/app/Services';

echo "<h2>Current Status:</h2>";

// Check if app directory exists
if (is_dir($basePath . '/app')) {
    echo "<p class='success'>✅ app/ directory exists</p>";
    
    // List what's in app/
    echo "<p class='info'>Contents of app/:</p><ul>";
    $appContents = scandir($basePath . '/app');
    foreach ($appContents as $item) {
        if ($item != '.' && $item != '..') {
            $type = is_dir($basePath . '/app/' . $item) ? '[DIR]' : '[FILE]';
            echo "<li>$type $item</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p class='error'>❌ app/ directory NOT found!</p>";
}

// Check if Services directory exists
echo "<hr><h2>Services Directory Check:</h2>";
if (is_dir($servicesDir)) {
    echo "<p class='success'>✅ Services directory EXISTS at: $servicesDir</p>";
    
    // List files in Services
    echo "<p class='info'>Files in Services/:</p><ul>";
    $servicesFiles = scandir($servicesDir);
    foreach ($servicesFiles as $file) {
        if ($file != '.' && $file != '..') {
            $size = filesize($servicesDir . '/' . $file);
            echo "<li>$file (" . number_format($size) . " bytes)</li>";
        }
    }
    echo "</ul>";
    
    // Check specifically for SmsService.php
    if (file_exists($servicesDir . '/SmsService.php')) {
        echo "<p class='success'>✅ SmsService.php EXISTS!</p>";
        echo "<p class='info'>File size: " . filesize($servicesDir . '/SmsService.php') . " bytes</p>";
    } else {
        echo "<p class='error'>❌ SmsService.php NOT FOUND in Services directory!</p>";
        echo "<p class='info'>You need to upload: app/Services/SmsService.php</p>";
    }
} else {
    echo "<p class='error'>❌ Services directory DOES NOT EXIST</p>";
    echo "<p class='info'>Attempting to create it...</p>";
    
    if (mkdir($servicesDir, 0755, true)) {
        echo "<p class='success'>✅ Services directory created successfully!</p>";
        echo "<p class='info'>Now upload SmsService.php to: $servicesDir</p>";
    } else {
        echo "<p class='error'>❌ Failed to create Services directory</p>";
        echo "<p class='info'>Create it manually via cPanel File Manager</p>";
    }
}

// Check permissions
echo "<hr><h2>Permissions Check:</h2>";
if (is_dir($servicesDir)) {
    $perms = substr(sprintf('%o', fileperms($servicesDir)), -4);
    echo "<p class='info'>Services directory permissions: $perms</p>";
    
    if (is_writable($servicesDir)) {
        echo "<p class='success'>✅ Services directory is writable</p>";
    } else {
        echo "<p class='error'>❌ Services directory is NOT writable</p>";
    }
}

// Instructions
echo "<hr><h2>📋 Next Steps:</h2>";
echo "<ol>";
echo "<li>Download <code>SmsService.php</code> from your local project at:<br><code>C:\\wamp64\\www\\Daria-Beauty\\dariabeauty\\app\\Services\\SmsService.php</code></li>";
echo "<li>Upload it to:<br><code>/home/ooxlvzey/public_html/app/Services/SmsService.php</code></li>";
echo "<li>Make sure the file has 644 permissions</li>";
echo "<li>Clear cache from Terminal:<br><code>cd /home/ooxlvzey/public_html && php artisan config:clear && php artisan route:clear</code></li>";
echo "<li>Try accessing <a href='/admin/sms' style='color:#00ffff'>/admin/sms</a> again</li>";
echo "</ol>";

echo "<hr><p class='error'>⚠️ DELETE this file after: rm /home/ooxlvzey/public_html/public/create-services-dir.php</p>";
echo "</body></html>";
