<?php
/**
 * CHECK REGISTER VIEW
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/check-register.php
 */

$rootDir = dirname(__DIR__);
$registerFile = $rootDir . '/resources/views/auth/register.blade.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check Register View</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        pre { background: #252526; padding: 15px; overflow-x: auto; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1 style='color:#4ec9b0;'>🔍 Check Register View</h1>
    
    <?php if (file_exists($registerFile)): ?>
        <p class='success'>✓ File exists: <?php echo $registerFile; ?></p>
        
        <?php 
        $content = file_get_contents($registerFile);
        $hasRoleSelect = strpos($content, 'role') !== false && strpos($content, 'Te înregistrezi ca') !== false;
        ?>
        
        <?php if ($hasRoleSelect): ?>
            <p class='success'>✓ File HAS role selection code!</p>
            <p style='color:#dcdcaa;'>The file contains the role selection dropdown.</p>
            <p><strong>Problem: Cache needs to be cleared!</strong></p>
        <?php else: ?>
            <p class='error'>✗ File DOES NOT have role selection code!</p>
            <p style='color:#dcdcaa;'>The file needs to be updated on the server.</p>
        <?php endif; ?>
        
        <h3>File Preview (first 50 lines):</h3>
        <pre><?php 
            $lines = explode("\n", $content);
            echo htmlspecialchars(implode("\n", array_slice($lines, 0, 50)));
        ?></pre>
        
    <?php else: ?>
        <p class='error'>✗ File NOT found: <?php echo $registerFile; ?></p>
    <?php endif; ?>
    
    <hr>
    <p class='error'><strong>ȘTERGE ACEST FIȘIER DUPĂ VERIFICARE!</strong></p>
</body>
</html>
