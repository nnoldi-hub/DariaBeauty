<?php
/**
 * UPDATE SPECIALISTS INDEX - FIX PROFILE LINK
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/fix-specialists-link.php
 * ȘTERGE DUPĂ FOLOSIRE!
 */

$rootDir = dirname(__DIR__);
$indexFile = $rootDir . '/resources/views/specialists/index.blade.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fix Specialists Profile Link</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        button { background: #007acc; color: white; border: none; padding: 15px 30px; cursor: pointer; font-size: 16px; margin: 10px; }
        button:hover { background: #005a9e; }
        .danger { background: #f48771; }
        .danger:hover { background: #d16956; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>🔧 Fix Specialists Profile Link</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fix'])) {
    try {
        if (!file_exists($indexFile)) {
            throw new Exception("File not found: $indexFile");
        }
        
        // Backup
        $backupFile = $indexFile . '.backup.' . date('Y-m-d-His');
        copy($indexFile, $backupFile);
        echo "<p class='success'>✓ Backup created: " . basename($backupFile) . "</p>";
        
        // Read content
        $content = file_get_contents($indexFile);
        
        // Replace OLD link with NEW link
        $oldPattern = "route('specialist.profile', \$specialist->slug)";
        $newPattern = "route('specialists.show', \$specialist->slug)";
        
        $count = 0;
        $content = str_replace($oldPattern, $newPattern, $content, $count);
        
        if ($count === 0) {
            echo "<p class='warning'>⚠️ No matches found for old pattern. Trying alternative patterns...</p>";
            
            // Try without spaces
            $oldPattern2 = "route('specialist.profile',\$specialist->slug)";
            $content = str_replace($oldPattern2, $newPattern, $content, $count);
        }
        
        if ($count > 0) {
            // Save file
            file_put_contents($indexFile, $content);
            
            echo "<p class='success'><strong>✓ File updated successfully!</strong></p>";
            echo "<p class='success'>Replaced $count occurrence(s)</p>";
            echo "<hr>";
            echo "<h2>Next Steps:</h2>";
            echo "<ol>";
            echo "<li><a href='clear-cache.php'><button>Clear Cache</button></a></li>";
            echo "<li><a href='/specialisti'><button style='background:#4ec9b0;'>Test Page</button></a></li>";
            echo "</ol>";
            echo "<hr>";
            echo "<p class='error'><strong>⚠️ ȘTERGE ACEST FIȘIER ACUM!</strong></p>";
        } else {
            echo "<p class='error'>✗ No occurrences of old link found in file!</p>";
            echo "<p class='warning'>The file might already be updated or use a different pattern.</p>";
            echo "<p><a href='check-specialists-view.php'><button>Check File Again</button></a></p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    ?>
    <div style='background:#252526; padding:20px; border-left:3px solid #007acc; margin:20px 0;'>
        <h2>Confirm Fix</h2>
        <p class='warning'>⚠️ This will update the profile link in specialists index page.</p>
        <p><strong>Change:</strong></p>
        <p style='background:#1e1e1e; padding:10px;'>
            <span class='error'>- route('specialist.profile', $specialist->slug)</span><br>
            <span class='success'>+ route('specialists.show', $specialist->slug)</span>
        </p>
        <p>A backup will be created automatically.</p>
        <form method="POST">
            <input type="hidden" name="fix" value="1">
            <button type="submit" class="danger">🔧 Fix Profile Link</button>
        </form>
    </div>
    <?php
}
?>

</body>
</html>
