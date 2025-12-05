<?php
/**
 * CHECK LARAVEL ERRORS
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/check-errors.php
 */

$rootDir = dirname(__DIR__);
$logFile = $rootDir . '/storage/logs/laravel.log';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Check Laravel Errors</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .error { color: #f48771; }
        pre { background: #252526; padding: 15px; overflow-x: auto; white-space: pre-wrap; max-height: 600px; }
    </style>
</head>
<body>

<h1 style='color:#f48771;'>🔍 Laravel Error Log</h1>

<?php if (file_exists($logFile)): ?>
    <p>Reading last 100 lines from log...</p>
    <pre><?php
        $lines = file($logFile);
        $lastLines = array_slice($lines, -100);
        echo htmlspecialchars(implode('', $lastLines));
    ?></pre>
<?php else: ?>
    <p class='error'>Log file not found: <?php echo $logFile; ?></p>
<?php endif; ?>

<hr>
<p><a href='/specialisti'><button style='background:#007acc; color:white; padding:10px 20px; border:none; cursor:pointer;'>Try Specialists Page</button></a></p>

</body>
</html>
