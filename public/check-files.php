<?php
/**
 * Simple File Checker - No Laravel loading
 * Upload to: /home/ooxlvzey/public_html/public/check-files.php
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head><title>File Check</title>
<style>
body{font-family:monospace;padding:20px;background:#1a1a1a;color:#0f0;font-size:14px}
.pass{color:#0f0;background:#030;padding:5px;margin:5px 0}
.fail{color:#f00;background:#300;padding:5px;margin:5px 0;font-weight:bold}
h1{color:#0ff}
</style>
</head>
<body>
<h1>📁 SMS Files Check</h1>

<?php
$base = dirname(__DIR__);
$files = [
    'SmsService' => '/app/Services/SmsService.php',
    'SmsController' => '/app/Http/Controllers/Admin/SmsController.php',
    'SmsLog Model' => '/app/Models/SmsLog.php',
    'Twilio Config' => '/config/twilio.php',
    'SMS View' => '/resources/views/admin/sms/index.blade.php',
    'SMS Migration' => '/database/migrations/2024_12_05_000001_create_sms_logs_table.php',
];

$missing = [];

foreach ($files as $name => $path) {
    $full = $base . $path;
    if (file_exists($full)) {
        $size = filesize($full);
        echo "<div class='pass'>✅ $name - " . number_format($size) . " bytes</div>";
    } else {
        echo "<div class='fail'>❌ MISSING: $name<br>Path: $full</div>";
        $missing[] = $name;
    }
}

echo "<hr><h2>Summary:</h2>";
if (empty($missing)) {
    echo "<div class='pass' style='font-size:18px'>🎉 ALL FILES EXIST!</div>";
    echo "<p>Problem might be:</p><ul>";
    echo "<li>Cache not cleared</li>";
    echo "<li>Permissions issue</li>";
    echo "<li>Routes not updated</li></ul>";
    
    echo "<h3>Try this from cPanel Terminal:</h3>";
    echo "<pre style='background:#000;padding:10px;color:#0ff'>cd /home/ooxlvzey/public_html
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear</pre>";
} else {
    echo "<div class='fail' style='font-size:18px'>❌ MISSING " . count($missing) . " FILES:</div>";
    echo "<ul>";
    foreach ($missing as $m) {
        echo "<li>$m</li>";
    }
    echo "</ul>";
    echo "<p>Upload these files from your local project!</p>";
}
?>

<hr>
<p style='color:#f00'>⚠️ DELETE: rm /home/ooxlvzey/public_html/public/check-files.php</p>
</body>
</html>
