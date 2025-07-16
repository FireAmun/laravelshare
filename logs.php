<?php
// Simple log viewer for debugging Laravel deployment issues
// Visit this file to see recent error logs

echo "<h1>Laravel Error Logs</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .error{color:red;background:#ffe6e6;padding:10px;margin:5px 0;} .warning{color:orange;} pre{background:#f5f5f5;padding:10px;border-radius:5px;overflow-x:auto;}</style>";

echo "<h2>1. Laravel Application Log</h2>";
$laravel_log = '/var/www/html/storage/logs/laravel.log';
if (file_exists($laravel_log) && is_readable($laravel_log)) {
    $lines = file($laravel_log);
    $recent_lines = array_slice($lines, -50); // Last 50 lines
    echo "<pre>" . htmlspecialchars(implode('', $recent_lines)) . "</pre>";
} else {
    echo "<p class='error'>❌ Laravel log file not found or not readable: $laravel_log</p>";
}

echo "<h2>2. Apache Error Log</h2>";
$apache_log = '/var/log/apache2/error.log';
if (file_exists($apache_log) && is_readable($apache_log)) {
    $lines = file($apache_log);
    $recent_lines = array_slice($lines, -30); // Last 30 lines
    echo "<pre>" . htmlspecialchars(implode('', $recent_lines)) . "</pre>";
} else {
    echo "<p class='error'>❌ Apache error log not found or not readable: $apache_log</p>";
}

echo "<h2>3. PHP Error Log</h2>";
$php_log = '/var/log/apache2/php_errors.log';
if (file_exists($php_log) && is_readable($php_log)) {
    $lines = file($php_log);
    $recent_lines = array_slice($lines, -20); // Last 20 lines
    echo "<pre>" . htmlspecialchars(implode('', $recent_lines)) . "</pre>";
} else {
    echo "<p class='warning'>⚠️ PHP error log not found: $php_log</p>";
}

echo "<h2>4. Quick Environment Check</h2>";
echo "<p><strong>APP_KEY:</strong> " . (env('APP_KEY') ? '✅ Set' : '❌ Not Set') . "</p>";
echo "<p><strong>APP_ENV:</strong> " . env('APP_ENV', 'Not Set') . "</p>";
echo "<p><strong>APP_DEBUG:</strong> " . (env('APP_DEBUG') ? 'true' : 'false') . "</p>";

echo "<h2>5. File Permissions</h2>";
$dirs = [
    '/var/www/html/storage/logs',
    '/var/www/html/storage/framework',
    '/var/www/html/bootstrap/cache'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir) ? '✅' : '❌';
        echo "<p><strong>$dir:</strong> $writable Permissions: $perms</p>";
    } else {
        echo "<p class='error'><strong>$dir:</strong> ❌ Directory not found</p>";
    }
}

echo "<h2>6. Recent PHP Errors</h2>";
if (function_exists('error_get_last')) {
    $last_error = error_get_last();
    if ($last_error) {
        echo "<div class='error'>";
        echo "<strong>Last PHP Error:</strong><br>";
        echo "Message: " . htmlspecialchars($last_error['message']) . "<br>";
        echo "File: " . htmlspecialchars($last_error['file']) . "<br>";
        echo "Line: " . $last_error['line'] . "<br>";
        echo "</div>";
    } else {
        echo "<p>✅ No recent PHP errors</p>";
    }
}

echo "<p><em>Log check completed at: " . date('Y-m-d H:i:s') . "</em></p>";
echo "<hr>";
echo "<p><strong style='color:red;'>IMPORTANT: Delete this file after debugging!</strong></p>";
?>
