<?php
/**
 * Fresh Error Logger - Shows only NEW errors from server
 * Upload to: /home/ooxlvzey/public_html/public/fresh-errors.php
 * Access: https://dariabeauty.ro/fresh-errors.php
 */

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>Fresh Errors</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#1a1a1a;color:#00ff00;font-size:14px}";
echo ".error{color:#ff0000;font-weight:bold}.success{color:#00ff00}.info{color:#00ffff}";
echo "pre{background:#000;padding:15px;border:2px solid #ff0000;white-space:pre-wrap;margin:20px 0}";
echo "button{padding:15px 30px;font-size:16px;margin:10px;cursor:pointer;border:none}";
echo ".btn-clear{background:#ff0000;color:#fff}.btn-test{background:#00aa00;color:#fff}</style></head><body>";

echo "<h1 style='color:#00ffff'>🔍 Fresh Error Logger</h1>";
echo "<p class='info'>This will show you ONLY the new errors from the server</p><hr>";

$basePath = dirname(__DIR__);
$logFile = $basePath . '/storage/logs/laravel.log';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_log'])) {
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            echo "<p class='success'>✅ Log file CLEARED! Now try accessing /admin/sms</p>";
            echo "<p class='info'><a href='/admin/sms' target='_blank' style='color:#00ffff;font-size:18px'>👉 Click here to test /admin/sms</a></p>";
            echo "<p class='info'>After clicking the link above, refresh THIS page to see the new error.</p>";
        }
    }
    
    if (isset($_POST['show_errors'])) {
        echo "<h2 style='color:#ff0000'>Latest Errors:</h2>";
        
        if (file_exists($logFile) && filesize($logFile) > 0) {
            $content = file_get_contents($logFile);
            
            if (empty(trim($content))) {
                echo "<p class='info'>No errors yet! Try accessing /admin/sms first.</p>";
            } else {
                // Find the main error message
                if (preg_match('/local\.ERROR: (.+?) \{/', $content, $matches)) {
                    echo "<div style='background:#330000;padding:20px;border:3px solid #ff0000;margin:20px 0'>";
                    echo "<h3 style='color:#ff0000'>🔴 MAIN ERROR:</h3>";
                    echo "<p style='color:#ffaa00;font-size:18px'>" . htmlspecialchars($matches[1]) . "</p>";
                    echo "</div>";
                }
                
                // Show full log
                echo "<h3>Full Error Log:</h3>";
                echo "<pre class='error'>" . htmlspecialchars($content) . "</pre>";
                
                echo "<p class='success'>👆 Copy this error and send it to me!</p>";
            }
        } else {
            echo "<p class='info'>Log file is empty or doesn't exist. Clear the log first, then test /admin/sms</p>";
        }
    }
}

// Show current status
echo "<h2>Current Status:</h2>";
if (file_exists($logFile)) {
    $size = filesize($logFile);
    echo "<p class='info'>Log file size: " . number_format($size) . " bytes</p>";
    
    if ($size == 0) {
        echo "<p class='success'>✅ Log is empty - ready for fresh test!</p>";
        echo "<p class='info'>Now go to: <a href='/admin/sms' target='_blank' style='color:#00ffff'>/admin/sms</a></p>";
        echo "<p class='info'>Then come back and click 'Show Errors' below</p>";
    } else {
        echo "<p class='error'>⚠️ Log contains " . substr_count(file_get_contents($logFile), 'ERROR') . " errors</p>";
    }
} else {
    echo "<p class='error'>❌ Log file not found</p>";
}

echo "<hr>";

// Action buttons
echo "<form method='post' style='margin:20px 0'>";
echo "<button type='submit' name='clear_log' class='btn-clear'>1️⃣ Clear Old Logs</button>";
echo "</form>";

echo "<p class='info'>After clearing, open <a href='/admin/sms' target='_blank' style='color:#00ffff;font-size:16px'>/admin/sms</a> in a new tab, then:</p>";

echo "<form method='post' style='margin:20px 0'>";
echo "<button type='submit' name='show_errors' class='btn-test'>2️⃣ Show Fresh Errors</button>";
echo "</form>";

echo "<hr>";
echo "<h2>📋 Step-by-Step:</h2>";
echo "<ol style='font-size:16px;line-height:30px'>";
echo "<li>Click <strong>'1️⃣ Clear Old Logs'</strong> button above</li>";
echo "<li>Click the link to open <strong>/admin/sms</strong> (it will error - that's OK!)</li>";
echo "<li>Come back to this page</li>";
echo "<li>Click <strong>'2️⃣ Show Fresh Errors'</strong></li>";
echo "<li>Copy the error message and send it</li>";
echo "</ol>";

echo "<hr><p class='error'>⚠️ DELETE after: rm /home/ooxlvzey/public_html/public/fresh-errors.php</p>";
echo "</body></html>";
