<?php
/**
 * DARIABEAUTY DATABASE UPDATE SCRIPT
 * Uploadează în /home/ooxlvzey/public_html/
 * Accesează: http://dariabeauty.ro/update-database.php
 * 
 * Acest script va rula DOAR migrările noi care lipsesc din baza de date
 * ȘTERGE ACEST FIȘIER DUPĂ FOLOSIRE!
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Dacă scriptul e în public/, urcă la root
if (basename(__DIR__) === 'public') {
    chdir(dirname(__DIR__));
    $rootDir = dirname(__DIR__);
} else {
    chdir(__DIR__);
    $rootDir = __DIR__;
}

// Global variable for root directory
define('ROOT_DIR', $rootDir);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DariaBeauty Database Update</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #569cd6; }
        .box { background: #252526; padding: 15px; margin: 10px 0; border-left: 3px solid #007acc; }
        button { background: #007acc; color: white; border: none; padding: 10px 20px; cursor: pointer; font-size: 16px; margin: 5px; }
        button:hover { background: #005a9e; }
        button.danger { background: #f48771; }
        button.danger:hover { background: #d16956; }
        pre { background: #1e1e1e; padding: 10px; overflow-x: auto; white-space: pre-wrap; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #444; }
        th { background: #007acc; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>🔄 DariaBeauty Database Update</h1>
<p class='info'>Current Directory: <?php echo getcwd(); ?></p>

<?php
$action = $_GET['action'] ?? 'menu';

if ($action === 'menu') {
    ?>
    <div class='box'>
        <h2>📋 Database Update Menu</h2>
        <p class='warning'>⚠️ Acest script va actualiza baza de date cu funcționalitățile noi:</p>
        <ul>
            <li>Adaugă rolul 'client' pentru utilizatori</li>
            <li>Adaugă opțiuni de locație (salon/domiciliu) pentru specialiști</li>
            <li>Adaugă opțiuni de locație pentru servicii</li>
        </ul>
        <p><a href="?action=check"><button>1. Verifică Status Bază de Date</button></a></p>
        <p><a href="?action=show-migrations"><button>2. Arată Migrările Rulate</button></a></p>
        <p><a href="?action=run-migrations"><button class='danger'>3. Rulează Migrările Noi</button></a></p>
        <p><a href="?action=verify"><button>4. Verifică Structura Actualizată</button></a></p>
    </div>
    <?php
}

// Helper function
function stepHeader($title, $icon = '📋') {
    echo "<div class='box'><h2 style='color:#4ec9b0;'>$icon $title</h2>";
}

function stepFooter() {
    echo "</div>";
}

// 1. Check Database Status
if ($action === 'check') {
    stepHeader('Verificare Status Bază de Date', '🔍');
    
    try {
        require ROOT_DIR.'/vendor/autoload.php';
        $app = require_once ROOT_DIR.'/bootstrap/app.php';
        
        // Boot Laravel
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        $db = $app->make('db');
        
        echo "<p class='success'>✓ Conexiune reușită la baza de date</p>";
        echo "<p class='info'>Database: " . $db->connection()->getDatabaseName() . "</p>";
        
        // Check users table structure
        echo "<h3>Structura tabelului 'users':</h3>";
        $columns = $db->select("SHOW COLUMNS FROM users");
        
        echo "<table><tr><th>Câmp</th><th>Tip</th><th>Null</th><th>Default</th></tr>";
        $hasClientRole = false;
        $hasOffersAtSalon = false;
        $hasOffersAtHome = false;
        
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column->Field) . "</td>";
            echo "<td>" . htmlspecialchars($column->Type) . "</td>";
            echo "<td>" . htmlspecialchars($column->Null) . "</td>";
            echo "<td>" . htmlspecialchars($column->Default ?? 'NULL') . "</td>";
            echo "</tr>";
            
            if ($column->Field === 'role' && strpos($column->Type, 'client') !== false) {
                $hasClientRole = true;
            }
            if ($column->Field === 'offers_at_salon') {
                $hasOffersAtSalon = true;
            }
            if ($column->Field === 'offers_at_home') {
                $hasOffersAtHome = true;
            }
        }
        echo "</table>";
        
        echo "<h3>Status funcționalități:</h3>";
        echo $hasClientRole ? "<p class='success'>✓ Rolul 'client' este disponibil</p>" : "<p class='error'>✗ Rolul 'client' LIPSEȘTE</p>";
        echo $hasOffersAtSalon ? "<p class='success'>✓ Câmpul 'offers_at_salon' există</p>" : "<p class='error'>✗ Câmpul 'offers_at_salon' LIPSEȘTE</p>";
        echo $hasOffersAtHome ? "<p class='success'>✓ Câmpul 'offers_at_home' există</p>" : "<p class='error'>✗ Câmpul 'offers_at_home' LIPSEȘTE</p>";
        
        // Check services table
        echo "<h3>Structura tabelului 'services':</h3>";
        $servicesColumns = $db->select("SHOW COLUMNS FROM services");
        
        echo "<table><tr><th>Câmp</th><th>Tip</th><th>Null</th><th>Default</th></tr>";
        $hasAvailableAtSalon = false;
        $hasAvailableAtHome = false;
        
        foreach ($servicesColumns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column->Field) . "</td>";
            echo "<td>" . htmlspecialchars($column->Type) . "</td>";
            echo "<td>" . htmlspecialchars($column->Null) . "</td>";
            echo "<td>" . htmlspecialchars($column->Default ?? 'NULL') . "</td>";
            echo "</tr>";
            
            if ($column->Field === 'available_at_salon') {
                $hasAvailableAtSalon = true;
            }
            if ($column->Field === 'available_at_home') {
                $hasAvailableAtHome = true;
            }
        }
        echo "</table>";
        
        echo $hasAvailableAtSalon ? "<p class='success'>✓ Câmpul 'available_at_salon' există</p>" : "<p class='error'>✗ Câmpul 'available_at_salon' LIPSEȘTE</p>";
        echo $hasAvailableAtHome ? "<p class='success'>✓ Câmpul 'available_at_home' există</p>" : "<p class='error'>✗ Câmpul 'available_at_home' LIPSEȘTE</p>";
        
        if (!$hasClientRole || !$hasOffersAtSalon || !$hasOffersAtHome || !$hasAvailableAtSalon || !$hasAvailableAtHome) {
            echo "<p class='warning'><strong>⚠️ Baza de date NU este la zi! Rulează migrările noi.</strong></p>";
        } else {
            echo "<p class='success'><strong>✓ Baza de date este complet actualizată!</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

// 2. Show Migrations
if ($action === 'show-migrations') {
    stepHeader('Migrări Rulate', '📜');
    
    try {
        require ROOT_DIR.'/vendor/autoload.php';
        $app = require_once ROOT_DIR.'/bootstrap/app.php';
        
        // Boot Laravel
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        $db = $app->make('db');
        
        $migrations = $db->table('migrations')->orderBy('id')->get();
        
        echo "<p class='info'>Total migrări rulate: " . count($migrations) . "</p>";
        echo "<table><tr><th>ID</th><th>Migrare</th><th>Batch</th></tr>";
        
        foreach ($migrations as $migration) {
            echo "<tr>";
            echo "<td>" . $migration->id . "</td>";
            echo "<td>" . htmlspecialchars($migration->migration) . "</td>";
            echo "<td>" . $migration->batch . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check for missing migrations
        $expectedMigrations = [
            '2025_12_04_154135_add_client_role_to_users_table',
            '2025_12_04_175042_add_service_location_options_to_users_and_services'
        ];
        
        $ranMigrations = array_map(function($m) { return $m->migration; }, $migrations->toArray());
        
        echo "<h3>Status migrări noi:</h3>";
        foreach ($expectedMigrations as $expected) {
            $isRun = in_array($expected, $ranMigrations);
            if ($isRun) {
                echo "<p class='success'>✓ $expected - RULATĂ</p>";
            } else {
                echo "<p class='error'>✗ $expected - NU A FOST RULATĂ</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

// 3. Run Migrations
if ($action === 'run-migrations') {
    stepHeader('Rulare Migrări Noi', '⚡');
    
    echo "<p class='warning'>⚠️ Rulează migrările noi în baza de date...</p>";
    
    try {
        require ROOT_DIR.'/vendor/autoload.php';
        $app = require_once ROOT_DIR.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        // Run migrations
        ob_start();
        $status = $kernel->call('migrate', ['--force' => true]);
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "<p class='success'>✓ Migrările au fost rulate cu succes!</p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
            
            echo "<p class='info'>➡️ <a href='?action=verify'><button>Verifică Rezultatul</button></a></p>";
        } else {
            echo "<p class='error'>✗ Eroare la rularea migrărilor</p>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    
    stepFooter();
}

// 4. Verify Updated Structure
if ($action === 'verify') {
    stepHeader('Verificare Finală', '✅');
    
    try {
        require ROOT_DIR.'/vendor/autoload.php';
        $app = require_once ROOT_DIR.'/bootstrap/app.php';
        
        // Boot Laravel
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        $db = $app->make('db');
        
        echo "<h3>Verificare câmpuri noi în 'users':</h3>";
        
        $userFields = ['offers_at_salon', 'offers_at_home', 'salon_address', 'salon_lat', 'salon_lng'];
        $columns = $db->select("SHOW COLUMNS FROM users");
        $columnNames = array_map(function($c) { return $c->Field; }, $columns);
        
        foreach ($userFields as $field) {
            if (in_array($field, $columnNames)) {
                echo "<p class='success'>✓ Câmpul '$field' există în tabelul users</p>";
            } else {
                echo "<p class='error'>✗ Câmpul '$field' LIPSEȘTE din tabelul users</p>";
            }
        }
        
        // Check role enum
        $roleColumn = array_filter($columns, function($c) { return $c->Field === 'role'; });
        $roleColumn = reset($roleColumn);
        if ($roleColumn && strpos($roleColumn->Type, 'client') !== false) {
            echo "<p class='success'>✓ Rolul 'client' este disponibil</p>";
        } else {
            echo "<p class='error'>✗ Rolul 'client' LIPSEȘTE</p>";
        }
        
        echo "<h3>Verificare câmpuri noi în 'services':</h3>";
        
        $serviceFields = ['available_at_salon', 'available_at_home', 'home_service_fee'];
        $servicesColumns = $db->select("SHOW COLUMNS FROM services");
        $servicesColumnNames = array_map(function($c) { return $c->Field; }, $servicesColumns);
        
        foreach ($serviceFields as $field) {
            if (in_array($field, $servicesColumnNames)) {
                echo "<p class='success'>✓ Câmpul '$field' există în tabelul services</p>";
            } else {
                echo "<p class='error'>✗ Câmpul '$field' LIPSEȘTE din tabelul services</p>";
            }
        }
        
        echo "<hr>";
        echo "<h2 style='color:#4ec9b0;'>🎉 Verificare Completă!</h2>";
        echo "<p class='warning'><strong>⚠️ ȘTERGE ACEST FIȘIER ACUM:</strong> update-database.php</p>";
        echo "<p><a href='/'><button style='background:#4ec9b0;'>→ Înapoi la Site</button></a></p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    stepFooter();
}

if ($action !== 'menu') {
    echo "<hr><p><a href='?action=menu'><button>← Înapoi la Meniu</button></a></p>";
}
?>

</body>
</html>
