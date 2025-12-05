<?php
/**
 * DARIABEAUTY DATABASE UPDATE - Direct MySQL
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/update-db-direct.php
 * 
 * ȘTERGE ACEST FIȘIER DUPĂ FOLOSIRE!
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load .env file
$rootDir = basename(__DIR__) === 'public' ? dirname(__DIR__) : __DIR__;
$envFile = $rootDir . '/.env';

if (!file_exists($envFile)) {
    die("Error: .env file not found at: $envFile");
}

// Parse .env (Laravel format)
function parseEnvFile($filePath) {
    $env = [];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) continue;
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes
            $value = trim($value, '"\'');
            
            $env[$key] = $value;
        }
    }
    
    return $env;
}

$env = parseEnvFile($envFile);
$dbHost = $env['DB_HOST'] ?? 'localhost';
$dbName = $env['DB_DATABASE'] ?? '';
$dbUser = $env['DB_USERNAME'] ?? '';
$dbPass = $env['DB_PASSWORD'] ?? '';

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
<p class='info'>Root Directory: <?php echo $rootDir; ?></p>

<?php
$action = $_GET['action'] ?? 'menu';

// Connect to database
function getDB() {
    global $dbHost, $dbName, $dbUser, $dbPass;
    
    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("<p class='error'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>");
    }
}

if ($action === 'menu') {
    ?>
    <div class='box'>
        <h2>📋 Database Update Menu</h2>
        <p class='info'>Database: <strong><?php echo htmlspecialchars($dbName); ?></strong></p>
        <p class='warning'>⚠️ Acest script va actualiza baza de date cu funcționalitățile noi:</p>
        <ul>
            <li>Adaugă rolul 'client' pentru utilizatori</li>
            <li>Adaugă opțiuni de locație (salon/domiciliu) pentru specialiști</li>
            <li>Adaugă opțiuni de locație pentru servicii</li>
        </ul>
        <p><a href="?action=check"><button>1. Verifică Status Bază de Date</button></a></p>
        <p><a href="?action=update"><button class='danger'>2. Actualizează Baza de Date</button></a></p>
        <p><a href="?action=verify"><button>3. Verifică Rezultatul</button></a></p>
    </div>
    <?php
}

// 1. Check Status
if ($action === 'check') {
    echo "<div class='box'><h2 style='color:#4ec9b0;'>🔍 Verificare Status</h2>";
    
    try {
        $pdo = getDB();
        echo "<p class='success'>✓ Conexiune reușită la baza de date: <strong>$dbName</strong></p>";
        
        // Check users table
        echo "<h3>Structura tabelului 'users':</h3>";
        $stmt = $pdo->query("SHOW COLUMNS FROM users");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table><tr><th>Câmp</th><th>Tip</th><th>Null</th><th>Default</th></tr>";
        
        $hasClientRole = false;
        $hasOffersAtSalon = false;
        $hasOffersAtHome = false;
        $hasSalonAddress = false;
        
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
            
            if ($col['Field'] === 'role' && strpos($col['Type'], 'client') !== false) {
                $hasClientRole = true;
            }
            if ($col['Field'] === 'offers_at_salon') $hasOffersAtSalon = true;
            if ($col['Field'] === 'offers_at_home') $hasOffersAtHome = true;
            if ($col['Field'] === 'salon_address') $hasSalonAddress = true;
        }
        echo "</table>";
        
        echo "<h3>Status funcționalități Users:</h3>";
        echo $hasClientRole ? "<p class='success'>✓ Rolul 'client' este disponibil</p>" : "<p class='error'>✗ Rolul 'client' LIPSEȘTE</p>";
        echo $hasOffersAtSalon ? "<p class='success'>✓ Câmpul 'offers_at_salon' există</p>" : "<p class='error'>✗ Câmpul 'offers_at_salon' LIPSEȘTE</p>";
        echo $hasOffersAtHome ? "<p class='success'>✓ Câmpul 'offers_at_home' există</p>" : "<p class='error'>✗ Câmpul 'offers_at_home' LIPSEȘTE</p>";
        echo $hasSalonAddress ? "<p class='success'>✓ Câmpul 'salon_address' există</p>" : "<p class='error'>✗ Câmpul 'salon_address' LIPSEȘTE</p>";
        
        // Check services table
        echo "<h3>Structura tabelului 'services':</h3>";
        $stmt = $pdo->query("SHOW COLUMNS FROM services");
        $servicesColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table><tr><th>Câmp</th><th>Tip</th><th>Null</th><th>Default</th></tr>";
        
        $hasAvailableAtSalon = false;
        $hasAvailableAtHome = false;
        $hasHomeServiceFee = false;
        
        foreach ($servicesColumns as $col) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($col['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
            
            if ($col['Field'] === 'available_at_salon') $hasAvailableAtSalon = true;
            if ($col['Field'] === 'available_at_home') $hasAvailableAtHome = true;
            if ($col['Field'] === 'home_service_fee') $hasHomeServiceFee = true;
        }
        echo "</table>";
        
        echo "<h3>Status funcționalități Services:</h3>";
        echo $hasAvailableAtSalon ? "<p class='success'>✓ Câmpul 'available_at_salon' există</p>" : "<p class='error'>✗ Câmpul 'available_at_salon' LIPSEȘTE</p>";
        echo $hasAvailableAtHome ? "<p class='success'>✓ Câmpul 'available_at_home' există</p>" : "<p class='error'>✗ Câmpul 'available_at_home' LIPSEȘTE</p>";
        echo $hasHomeServiceFee ? "<p class='success'>✓ Câmpul 'home_service_fee' există</p>" : "<p class='error'>✗ Câmpul 'home_service_fee' LIPSEȘTE</p>";
        
        if (!$hasClientRole || !$hasOffersAtSalon || !$hasOffersAtHome || !$hasSalonAddress || !$hasAvailableAtSalon || !$hasAvailableAtHome || !$hasHomeServiceFee) {
            echo "<hr><p class='warning'><strong>⚠️ Baza de date NU este la zi! Click pe butonul 2 pentru actualizare.</strong></p>";
        } else {
            echo "<hr><p class='success'><strong>✓ Baza de date este complet actualizată!</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "</div>";
}

// 2. Update Database
if ($action === 'update') {
    echo "<div class='box'><h2 style='color:#f48771;'>⚡ Actualizare Bază de Date</h2>";
    echo "<p class='warning'>⚠️ Rulează actualizările în baza de date...</p>";
    
    try {
        $pdo = getDB();
        $pdo->beginTransaction();
        
        $updates = [];
        $errors = [];
        
        // 1. Update role enum to include 'client'
        try {
            $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'specialist', 'superadmin') DEFAULT 'client'");
            $updates[] = "✓ Rolul 'client' adăugat în users.role";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') === false) {
                $errors[] = "Role update: " . $e->getMessage();
            } else {
                $updates[] = "✓ Rolul 'client' există deja";
            }
        }
        
        // 2. Add columns to users table
        $userColumns = [
            "offers_at_salon TINYINT(1) DEFAULT 1 AFTER is_active" => "offers_at_salon",
            "offers_at_home TINYINT(1) DEFAULT 0 AFTER offers_at_salon" => "offers_at_home",
            "salon_address VARCHAR(255) NULL AFTER offers_at_home" => "salon_address",
            "salon_lat DECIMAL(10,8) NULL AFTER salon_address" => "salon_lat",
            "salon_lng DECIMAL(11,8) NULL AFTER salon_lat" => "salon_lng"
        ];
        
        foreach ($userColumns as $definition => $columnName) {
            try {
                $pdo->exec("ALTER TABLE users ADD COLUMN $definition");
                $updates[] = "✓ Câmpul '$columnName' adăugat în users";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate') !== false) {
                    $updates[] = "✓ Câmpul '$columnName' există deja în users";
                } else {
                    $errors[] = "users.$columnName: " . $e->getMessage();
                }
            }
        }
        
        // 3. Add columns to services table
        $serviceColumns = [
            "available_at_salon TINYINT(1) DEFAULT 1 AFTER category" => "available_at_salon",
            "available_at_home TINYINT(1) DEFAULT 0 AFTER available_at_salon" => "available_at_home",
            "home_service_fee DECIMAL(8,2) DEFAULT 0 AFTER available_at_home COMMENT 'Taxa suplimentara pentru serviciu la domiciliu'" => "home_service_fee"
        ];
        
        foreach ($serviceColumns as $definition => $columnName) {
            try {
                $pdo->exec("ALTER TABLE services ADD COLUMN $definition");
                $updates[] = "✓ Câmpul '$columnName' adăugat în services";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate') !== false) {
                    $updates[] = "✓ Câmpul '$columnName' există deja în services";
                } else {
                    $errors[] = "services.$columnName: " . $e->getMessage();
                }
            }
        }
        
        // 4. Record migration in migrations table
        try {
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            
            $migrations = [
                '2025_12_04_154135_add_client_role_to_users_table',
                '2025_12_04_175042_add_service_location_options_to_users_and_services'
            ];
            
            foreach ($migrations as $migration) {
                // Check if already exists
                $check = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
                $check->execute([$migration]);
                if ($check->fetchColumn() == 0) {
                    $stmt->execute([$migration, 999]);
                    $updates[] = "✓ Migrare înregistrată: $migration";
                } else {
                    $updates[] = "✓ Migrare deja înregistrată: $migration";
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Migrations table: " . $e->getMessage();
        }
        
        if (empty($errors)) {
            $pdo->commit();
            echo "<p class='success'><strong>✓ Actualizare completă cu succes!</strong></p>";
        } else {
            $pdo->rollBack();
            echo "<p class='error'><strong>✗ Erori la actualizare:</strong></p>";
            foreach ($errors as $error) {
                echo "<p class='error'>✗ $error</p>";
            }
        }
        
        echo "<h3>Detalii actualizare:</h3>";
        foreach ($updates as $update) {
            echo "<p class='info'>$update</p>";
        }
        
        echo "<hr><p><a href='?action=verify'><button>→ Verifică Rezultatul</button></a></p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
        if (isset($pdo)) $pdo->rollBack();
    }
    
    echo "</div>";
}

// 3. Verify
if ($action === 'verify') {
    echo "<div class='box'><h2 style='color:#4ec9b0;'>✅ Verificare Finală</h2>";
    
    try {
        $pdo = getDB();
        
        // Check all fields exist
        $stmt = $pdo->query("SHOW COLUMNS FROM users");
        $userColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt = $pdo->query("SHOW COLUMNS FROM services");
        $serviceColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $requiredUserFields = ['offers_at_salon', 'offers_at_home', 'salon_address', 'salon_lat', 'salon_lng'];
        $requiredServiceFields = ['available_at_salon', 'available_at_home', 'home_service_fee'];
        
        echo "<h3>Verificare users:</h3>";
        $allUserOk = true;
        foreach ($requiredUserFields as $field) {
            if (in_array($field, $userColumns)) {
                echo "<p class='success'>✓ $field</p>";
            } else {
                echo "<p class='error'>✗ $field LIPSEȘTE</p>";
                $allUserOk = false;
            }
        }
        
        echo "<h3>Verificare services:</h3>";
        $allServiceOk = true;
        foreach ($requiredServiceFields as $field) {
            if (in_array($field, $serviceColumns)) {
                echo "<p class='success'>✓ $field</p>";
            } else {
                echo "<p class='error'>✗ $field LIPSEȘTE</p>";
                $allServiceOk = false;
            }
        }
        
        if ($allUserOk && $allServiceOk) {
            echo "<hr><h2 style='color:#4ec9b0;'>🎉 Baza de date este complet actualizată!</h2>";
            echo "<p class='warning'><strong>⚠️ ȘTERGE ACEST FIȘIER ACUM:</strong> update-db-direct.php</p>";
            echo "<p><a href='/'><button style='background:#4ec9b0;'>→ Înapoi la Site</button></a></p>";
        } else {
            echo "<hr><p class='error'><strong>⚠️ Unele câmpuri lipsesc încă. Încearcă din nou actualizarea.</strong></p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "</div>";
}

if ($action !== 'menu') {
    echo "<hr><p><a href='?action=menu'><button>← Înapoi la Meniu</button></a></p>";
}
?>

</body>
</html>
