<?php
/**
 * CREATE SUPER ADMIN - DariaBeauty
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/create-superadmin.php
 * 
 * ȘTERGE ACEST FIȘIER IMEDIAT DUPĂ FOLOSIRE! (FOARTE IMPORTANT PENTRU SECURITATE!)
 */

$rootDir = dirname(__DIR__);
chdir($rootDir);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Create Super Admin</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .box { background: #252526; padding: 15px; margin: 10px 0; border-left: 3px solid #007acc; }
        input, button { padding: 10px; margin: 5px 0; font-size: 14px; }
        input { width: 100%; max-width: 400px; }
        button { background: #007acc; color: white; border: none; cursor: pointer; }
        button:hover { background: #005a9e; }
        .danger { background: #f48771; }
        .danger:hover { background: #d16956; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>👑 Create Super Admin</h1>

<div class='box'>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
        try {
            require $rootDir.'/vendor/autoload.php';
            $app = require_once $rootDir.'/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $phone = trim($_POST['phone'] ?? '');
            
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("Toate câmpurile sunt obligatorii!");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email invalid!");
            }
            
            if (strlen($password) < 8) {
                throw new Exception("Parola trebuie să aibă minim 8 caractere!");
            }
            
            // Check if email exists
            $db = $app->make('db');
            $existingUser = $db->table('users')->where('email', $email)->first();
            
            if ($existingUser) {
                throw new Exception("Un utilizator cu acest email există deja!");
            }
            
            // Create super admin
            $userId = $db->table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => 'superadmin',
                'phone' => $phone ?: null,
                'is_active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            echo "<h2 class='success'>✓ Super Admin creat cu succes!</h2>";
            echo "<p class='success'>ID: $userId</p>";
            echo "<p class='success'>Nume: " . htmlspecialchars($name) . "</p>";
            echo "<p class='success'>Email: " . htmlspecialchars($email) . "</p>";
            echo "<p class='success'>Rol: superadmin</p>";
            
            echo "<hr>";
            echo "<h3>🔐 Date de autentificare:</h3>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
            echo "<p><strong>Password:</strong> (parola pe care ai introdus-o)</p>";
            
            echo "<hr>";
            echo "<p><a href='/login'><button style='background:#4ec9b0;'>→ Mergi la Login</button></a></p>";
            
            echo "<hr>";
            echo "<p class='error' style='font-size:18px;'><strong>⚠️ ȘTERGE ACEST FIȘIER IMEDIAT!</strong></p>";
            echo "<p class='error'>create-superadmin.php trebuie șters pentru securitate!</p>";
            
        } catch (Exception $e) {
            echo "<p class='error'>✗ Eroare: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><a href='?'><button>← Încearcă din nou</button></a></p>";
        }
        
    } else {
        ?>
        <h2>Creează Super Admin</h2>
        <p class='warning'>⚠️ Acest cont va avea acces complet la sistem!</p>
        
        <form method="POST">
            <div>
                <label><strong>Nume complet:</strong></label><br>
                <input type="text" name="name" required placeholder="Ex: Daria Administrator" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div>
                <label><strong>Email:</strong></label><br>
                <input type="email" name="email" required placeholder="admin@dariabeauty.ro" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div>
                <label><strong>Telefon (opțional):</strong></label><br>
                <input type="text" name="phone" placeholder="0700000000" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            </div>
            
            <div>
                <label><strong>Parolă:</strong></label><br>
                <input type="password" name="password" required placeholder="Minim 8 caractere">
                <p style='font-size:12px; color:#888;'>Notează parola - vei avea nevoie de ea pentru login!</p>
            </div>
            
            <input type="hidden" name="create" value="1">
            <button type="submit" class="danger" style='font-size:16px; padding:15px 30px;'>
                👑 Creează Super Admin
            </button>
        </form>
        <?php
    }
    ?>
</div>

<div class='box'>
    <h3>ℹ️ Informații</h3>
    <p><strong>Super Admin</strong> are acces complet la:</p>
    <ul>
        <li>Gestionare utilizatori (clienți și specialiști)</li>
        <li>Gestionare servicii</li>
        <li>Gestionare programări</li>
        <li>Gestionare review-uri</li>
        <li>Setări sistem</li>
    </ul>
</div>

</body>
</html>
