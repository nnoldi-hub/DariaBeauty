<?php
/**
 * CHECK SPECIALISTS INDEX VIEW
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/check-specialists-view.php
 * ȘTERGE DUPĂ FOLOSIRE!
 */

$rootDir = dirname(__DIR__);
$indexFile = $rootDir . '/resources/views/specialists/index.blade.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check Specialists Index View</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        pre { background: #252526; padding: 15px; overflow-x: auto; white-space: pre-wrap; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>🔍 Check Specialists Index View</h1>

<?php if (file_exists($indexFile)): ?>
    <p class='success'>✓ File exists: <?php echo $indexFile; ?></p>
    
    <?php 
    $content = file_get_contents($indexFile);
    
    // Check for correct profile link
    $hasCorrectLink = strpos($content, "route('specialists.show'") !== false;
    $hasOldLink = strpos($content, "route('specialist.profile'") !== false;
    ?>
    
    <h2>Link Status:</h2>
    <?php if ($hasCorrectLink): ?>
        <p class='success'>✓ File HAS correct link: route('specialists.show', $specialist->slug)</p>
        <p style='color:#dcdcaa;'>The profile button should open the PUBLIC profile page.</p>
    <?php else: ?>
        <p class='error'>✗ File DOES NOT have correct link!</p>
    <?php endif; ?>
    
    <?php if ($hasOldLink): ?>
        <p class='error'>✗ File has OLD link: route('specialist.profile') - needs update!</p>
    <?php endif; ?>
    
    <h3>Profile Button Code (around line 214):</h3>
    <pre><?php 
        $lines = explode("\n", $content);
        // Find the line with "Profil" button
        foreach ($lines as $i => $line) {
            if (strpos($line, 'Profil') !== false && strpos($line, 'btn') !== false) {
                // Show 5 lines before and after
                $start = max(0, $i - 5);
                $end = min(count($lines) - 1, $i + 5);
                for ($j = $start; $j <= $end; $j++) {
                    echo ($j === $i ? ">>> " : "    ") . htmlspecialchars($lines[$j]) . "\n";
                }
                break;
            }
        }
    ?></pre>
    
    <?php if (!$hasCorrectLink || $hasOldLink): ?>
        <hr>
        <h2 class='error'>⚠️ File needs update!</h2>
        <p>The file on server is outdated and needs to be replaced.</p>
    <?php else: ?>
        <hr>
        <h2 class='success'>✓ File is up to date!</h2>
        <p>The problem might be:</p>
        <ul>
            <li>Cache not cleared</li>
            <li>Specialists missing slugs in database</li>
        </ul>
        <p><a href='check-slugs.php'><button style='background:#007acc; color:white; padding:10px 20px; border:none; cursor:pointer;'>Check Slugs</button></a></p>
        <p><a href='clear-cache.php'><button style='background:#007acc; color:white; padding:10px 20px; border:none; cursor:pointer;'>Clear Cache</button></a></p>
    <?php endif; ?>
    
<?php else: ?>
    <p class='error'>✗ File NOT found: <?php echo $indexFile; ?></p>
<?php endif; ?>

<hr>
<p class='error'><strong>ȘTERGE ACEST FIȘIER DUPĂ VERIFICARE!</strong></p>

</body>
</html>
