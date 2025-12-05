<?php
/**
 * DARIABEAUTY SETUP SCRIPT
 * Uploadează în /home/ooxlvzey/public_html/
 * Accesează: http://dariabeauty.ro/setup-deploy.php
 * 
 * ȘTERGE ACEST FIȘIER DUPĂ FOLOSIRE!
 */

// Setează timeout
set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Working directory
chdir(__DIR__);

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>DariaBeauty Deploy</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4;}";
echo ".success{color:#4ec9b0;}.error{color:#f48771;}.info{color:#dcdcaa;}.cmd{background:#252526;padding:10px;margin:10px 0;border-left:3px solid #007acc;}</style>";
echo "</head><body>";

echo "<h1 style='color:#4ec9b0;'>🚀 DariaBeauty Deploy Script</h1>";
echo "<p class='info'>Current Directory: " . getcwd() . "</p>";
echo "<hr>";

// Funcție pentru rulare comenzi
function runCommand($cmd, $description) {
    echo "<div class='cmd'>";
    echo "<strong class='info'>➤ $description</strong><br>";
    echo "<code>$ $cmd</code><br><br>";
    
    $output = shell_exec("$cmd 2>&1");
    
    if ($output) {
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    } else {
        echo "<span class='error'>No output or command failed</span>";
    }
    
    echo "</div>";
}

// 1. Verificare mediu
echo "<h2>📋 Step 1: Environment Check</h2>";
runCommand("php -v", "PHP Version");
runCommand("pwd", "Current Directory");
runCommand("ls -la", "Files in Directory");

// 2. Verificare .env
echo "<h2>📝 Step 2: Environment File</h2>";
if (!file_exists('.env')) {
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "<p class='success'>✓ .env created from .env.example</p>";
    } else {
        echo "<p class='error'>✗ .env.example not found!</p>";
    }
} else {
    echo "<p class='success'>✓ .env already exists</p>";
}

// Verifică conținut .env
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    $checks = [
        'APP_ENV=production' => strpos($env, 'APP_ENV=production') !== false,
        'APP_DEBUG=false' => strpos($env, 'APP_DEBUG=false') !== false,
        'DB_DATABASE' => strpos($env, 'DB_DATABASE=') !== false,
    ];
    
    foreach ($checks as $key => $exists) {
        if ($exists) {
            echo "<p class='success'>✓ $key configured</p>";
        } else {
            echo "<p class='error'>✗ $key missing or incorrect</p>";
        }
    }
}

// 3. Composer install
echo "<h2>📦 Step 3: Composer Dependencies</h2>";
if (file_exists('vendor')) {
    echo "<p class='info'>Vendor directory exists, checking composer...</p>";
}
runCommand("composer --version", "Composer Version");
runCommand("composer install --optimize-autoloader --no-dev 2>&1", "Install Dependencies");

// 4. Generate APP_KEY
echo "<h2>🔑 Step 4: Application Key</h2>";
runCommand("php artisan key:generate --force", "Generate APP_KEY");

// 5. Database migrations
echo "<h2>🗄️ Step 5: Database Migrations</h2>";
runCommand("php artisan migrate --force", "Run Migrations");

// 6. Storage link
echo "<h2>🔗 Step 6: Storage Symlink</h2>";
runCommand("php artisan storage:link", "Create Storage Link");

// 7. Permissions
echo "<h2>🔐 Step 7: Permissions</h2>";
runCommand("chmod -R 755 storage bootstrap/cache", "Set Permissions");
runCommand("ls -ld storage/", "Verify Storage");

// 8. Cache optimizations
echo "<h2>⚡ Step 8: Cache Optimization</h2>";
runCommand("php artisan config:clear", "Clear Config Cache");
runCommand("php artisan cache:clear", "Clear Application Cache");
runCommand("php artisan route:clear", "Clear Route Cache");
runCommand("php artisan view:clear", "Clear View Cache");
echo "<hr>";
runCommand("php artisan config:cache", "Cache Config");
runCommand("php artisan route:cache", "Cache Routes");
runCommand("php artisan view:cache", "Cache Views");

// 9. Verificare finală
echo "<h2>✅ Step 9: Final Verification</h2>";
runCommand("php artisan --version", "Laravel Version");
runCommand("php artisan tinker --execute='echo DB::connection()->getDatabaseName();'", "Database Connection Test");

// Finalizare
echo "<hr>";
echo "<h2 style='color:#4ec9b0;'>🎉 DEPLOYMENT COMPLETE!</h2>";
echo "<p class='success'>✓ All steps executed</p>";
echo "<p class='error'><strong>⚠️ IMPORTANT: DELETE THIS FILE NOW!</strong></p>";
echo "<p>Delete file: <code>rm setup-deploy.php</code> or via File Manager</p>";
echo "<hr>";
echo "<p><a href='/' style='color:#4ec9b0;'>→ Go to Homepage</a></p>";

echo "</body></html>";
?>
