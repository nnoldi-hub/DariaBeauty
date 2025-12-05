<?php
/**
 * DARIABEAUTY SIMPLE SETUP - No shell_exec required
 * Uploadează în /home/ooxlvzey/public_html/
 * Accesează: http://dariabeauty.ro/setup-simple.php
 * 
 * ȘTERGE ACEST FIȘIER DUPĂ FOLOSIRE!
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

chdir(__DIR__);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DariaBeauty Setup</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .info { color: #dcdcaa; }
        .box { background: #252526; padding: 15px; margin: 10px 0; border-left: 3px solid #007acc; }
        button { background: #007acc; color: white; border: none; padding: 10px 20px; cursor: pointer; font-size: 16px; }
        button:hover { background: #005a9e; }
        pre { background: #1e1e1e; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>🚀 DariaBeauty Simple Setup</h1>
<p class='info'>Current Directory: <?php echo getcwd(); ?></p>

<?php
$action = $_GET['action'] ?? 'menu';

if ($action === 'menu') {
    ?>
    <div class='box'>
        <h2>Setup Menu</h2>
        <p>Choose setup actions:</p>
        <p><a href="?action=check"><button>1. Check Environment</button></a></p>
        <p><a href="?action=migrate"><button>2. Run Migrations</button></a></p>
        <p><a href="?action=storage"><button>3. Create Storage Link</button></a></p>
        <p><a href="?action=cache"><button>4. Clear & Rebuild Cache</button></a></p>
        <p><a href="?action=test"><button>5. Test Database Connection</button></a></p>
        <p><a href="?action=all"><button style='background:#f48771;'>⚡ RUN ALL STEPS</button></a></p>
    </div>
    <?php
}

// Helper function
function stepHeader($title) {
    echo "<div class='box'><h2 style='color:#4ec9b0;'>$title</h2>";
}

function stepFooter() {
    echo "</div>";
}

// 1. Check Environment
if ($action === 'check' || $action === 'all') {
    stepHeader('📋 Environment Check');
    
    echo "<p class='info'>PHP Version: " . phpversion() . "</p>";
    echo "<p class='info'>Current Dir: " . getcwd() . "</p>";
    
    // Check Laravel files
    $files = ['artisan', 'composer.json', '.env', 'app', 'public', 'storage'];
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "<p class='success'>✓ $file exists</p>";
        } else {
            echo "<p class='error'>✗ $file NOT FOUND</p>";
        }
    }
    
    // Check .env content
    if (file_exists('.env')) {
        $env = file_get_contents('.env');
        $prod = strpos($env, 'APP_ENV=production') !== false;
        $debug = strpos($env, 'APP_DEBUG=false') !== false;
        
        echo $prod ? "<p class='success'>✓ APP_ENV=production</p>" : "<p class='error'>✗ APP_ENV not production</p>";
        echo $debug ? "<p class='success'>✓ APP_DEBUG=false</p>" : "<p class='error'>✗ APP_DEBUG not false</p>";
    }
    
    stepFooter();
}

// 2. Run Migrations
if ($action === 'migrate' || $action === 'all') {
    stepHeader('🗄️ Database Migrations');
    
    try {
        // Load Laravel
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        // Run migrations
        ob_start();
        $status = $kernel->call('migrate', ['--force' => true]);
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "<p class='success'>✓ Migrations completed successfully</p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        } else {
            echo "<p class='error'>✗ Migrations failed</p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

// 3. Storage Link
if ($action === 'storage' || $action === 'all') {
    stepHeader('🔗 Storage Symlink');
    
    try {
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        ob_start();
        $status = $kernel->call('storage:link');
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "<p class='success'>✓ Storage link created</p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        } else {
            echo "<p class='error'>✗ Storage link failed</p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

// 4. Cache
if ($action === 'cache' || $action === 'all') {
    stepHeader('⚡ Cache Management');
    
    try {
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        $commands = [
            'config:clear' => 'Clear config cache',
            'cache:clear' => 'Clear application cache',
            'route:clear' => 'Clear route cache',
            'view:clear' => 'Clear view cache',
            'config:cache' => 'Cache configuration',
            'route:cache' => 'Cache routes',
            'view:cache' => 'Cache views',
        ];
        
        foreach ($commands as $cmd => $desc) {
            ob_start();
            $status = $kernel->call($cmd);
            $output = ob_get_clean();
            
            if ($status === 0) {
                echo "<p class='success'>✓ $desc</p>";
            } else {
                echo "<p class='error'>✗ $desc failed</p>";
            }
        }
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

// 5. Test Database
if ($action === 'test' || $action === 'all') {
    stepHeader('🧪 Database Connection Test');
    
    try {
        require __DIR__.'/vendor/autoload.php';
        $app = require_once __DIR__.'/bootstrap/app.php';
        
        $db = $app->make('db');
        $pdo = $db->connection()->getPdo();
        
        echo "<p class='success'>✓ Database connected successfully</p>";
        echo "<p class='info'>Database: " . $db->connection()->getDatabaseName() . "</p>";
        
        // Count users
        $users = $db->table('users')->count();
        echo "<p class='info'>Users in database: $users</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Database connection failed</p>";
        echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

if ($action !== 'menu') {
    echo "<hr><p><a href='?action=menu'><button>← Back to Menu</button></a></p>";
    
    if ($action === 'all') {
        echo "<hr>";
        echo "<h2 style='color:#4ec9b0;'>🎉 ALL STEPS COMPLETE!</h2>";
        echo "<p class='error'><strong>⚠️ DELETE THIS FILE NOW:</strong> setup-simple.php</p>";
        echo "<p><a href='/'><button style='background:#4ec9b0;'>→ Go to Homepage</button></a></p>";
    }
}
?>

</body>
</html>
