<?php
/**
 * UPLOAD FULL SPECIALISTS INDEX FILE
 * Uploadează în /home/ooxlvzey/public_html/public/
 * Accesează: http://dariabeauty.ro/upload-specialists-index.php
 * ȘTERGE DUPĂ FOLOSIRE!
 */

$rootDir = dirname(__DIR__);
$targetFile = $rootDir . '/resources/views/specialists/index.blade.php';

// Full correct file content
$newContent = file_get_contents(__DIR__ . '/../resources/views/specialists/index.blade.php.new');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Upload Specialists Index</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        button { background: #007acc; color: white; border: none; padding: 15px 30px; cursor: pointer; font-size: 16px; }
        button:hover { background: #005a9e; }
        .danger { background: #f48771; }
    </style>
</head>
<body>

<h1 style='color:#4ec9b0;'>📤 Upload Specialists Index</h1>

<p class='error'>Scriptul nu poate încărca fișierul complet în acest mod.</p>
<p class='success'><strong>Soluția simplă: Încarcă fișierul MANUAL prin cPanel!</strong></p>

<hr>

<h2>📋 Pași finali:</h2>
<ol style='line-height:2;'>
    <li><strong>Deschide cPanel</strong> → File Manager</li>
    <li>Navighează la: <code>/home/ooxlvzey/public_html/resources/views/specialists/</code></li>
    <li><strong>Șterge</strong> sau redenumește fișierul vechi <code>index.blade.php</code></li>
    <li><strong>Încarcă</strong> fișierul nou de pe PC:</li>
    <li style='margin-left:20px;'>De la: <code>c:\wamp64\www\Daria-Beauty\dariabeauty\resources\views\specialists\index.blade.php</code></li>
    <li style='margin-left:20px;'>La: <code>/home/ooxlvzey/public_html/resources/views/specialists/index.blade.php</code></li>
    <li><strong>Clear cache</strong>: <a href='clear-cache.php'><button>Clear Cache</button></a></li>
    <li><strong>Testează</strong>: <a href='/specialisti'><button style='background:#4ec9b0;'>Test Page</button></a></li>
</ol>

<hr>

<h2>SAU folosește GitHub:</h2>
<ol style='line-height:2;'>
    <li>Commit și push fișierele actualizate (deja făcut!)</li>
    <li>În cPanel → Git Version Control</li>
    <li>Click <strong>Manage</strong> pe repo DariaBeauty</li>
    <li>Click <strong>Update from Remote</strong> sau <strong>Pull</strong></li>
    <li>Clear cache și testează</li>
</ol>

<hr>
<p class='error'><strong>ȘTERGE toate scripturile de test după finalizare!</strong></p>
<ul>
    <li>check-register.php</li>
    <li>check-specialists-view.php</li>
    <li>fix-specialists-link.php</li>
    <li>upload-specialists-index.php</li>
    <li>clear-cache.php</li>
    <li>check-slugs.php</li>
</ul>

</body>
</html>
