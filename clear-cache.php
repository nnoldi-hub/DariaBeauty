<?php
/**
 * CLEAR CACHE - DariaBeauty
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/clear-cache.php
 * ȘTERGE DUPĂ FOLOSIRE!
 */

// Setează calea către root
$rootDir = dirname(__DIR__);
chdir($rootDir);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Clear Cache</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .box { background: #252526; padding: 15px; margin: 10px 0; border-left: 3px solid #007acc; }
    </style>
</head>
<body>
    <h1 style='color:#4ec9b0;'>🧹 Clear Cache</h1>
    
    <div class='box'>
        <h2>Clearing cache...</h2>
        <?php
        try {
            require $rootDir.'/vendor/autoload.php';
            $app = require_once $rootDir.'/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            
            $commands = [
                'config:clear' => 'Config cache',
                'cache:clear' => 'Application cache',
                'route:clear' => 'Route cache',
                'view:clear' => 'View cache',
            ];
            
            foreach ($commands as $cmd => $desc) {
                ob_start();
                $kernel->call($cmd);
                ob_end_clean();
                echo "<p class='success'>✓ Cleared $desc</p>";
            }
            
            echo "<hr><h2 style='color:#4ec9b0;'>✓ Cache cleared successfully!</h2>";
            echo "<p class='error'><strong>ȘTERGE ACEST FIȘIER ACUM!</strong></p>";
            echo "<p><a href='/register'><button style='background:#007acc; color:white; padding:10px 20px; border:none; cursor:pointer;'>→ Go to Register</button></a></p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </div>
</body>
</html>
