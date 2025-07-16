<?php
// Enhanced Debug script to check Laravel configuration
// Upload this to your Render deployment and visit it to diagnose issues

echo "<h1>Laravel Enhanced Debug Information</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .error{color:red;} .success{color:green;} .warning{color:orange;} pre{background:#f5f5f5;padding:10px;border-radius:5px;}</style>";

echo "<h2>1. PHP Environment</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
echo "<p><strong>Script Path:</strong> " . __FILE__ . "</p>";

echo "<h2>2. File Structure Check</h2>";
$files = [
    '/var/www/html/.env' => '.env file',
    '/var/www/html/vendor/autoload.php' => 'Composer autoload',
    '/var/www/html/bootstrap/app.php' => 'Laravel bootstrap',
    '/var/www/html/public/index.php' => 'Public index',
    '/var/www/html/storage/logs' => 'Storage logs directory',
    '/var/www/html/bootstrap/cache' => 'Bootstrap cache directory',
];

foreach ($files as $path => $desc) {
    $exists = file_exists($path);
    $class = $exists ? 'success' : 'error';
    $status = $exists ? '✅ Found' : '❌ Missing';
    $permissions = $exists ? ' (Perms: ' . substr(sprintf('%o', fileperms($path)), -4) . ')' : '';
    echo "<p class='$class'><strong>$desc:</strong> $status$permissions</p>";
}

echo "<h2>3. Environment Variables</h2>";
$env_vars = ['APP_KEY', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'RENDER'];
foreach ($env_vars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'Not Set';
    $masked = in_array($var, ['APP_KEY', 'DB_PASSWORD']) ? (strlen($value) > 10 ? str_repeat('*', 20) : $value) : $value;
    echo "<p><strong>$var:</strong> $masked</p>";
}

echo "<h2>4. Laravel Bootstrap Test</h2>";
try {
    if (file_exists('/var/www/html/vendor/autoload.php')) {
        require_once '/var/www/html/vendor/autoload.php';
        echo "<p class='success'>✅ Composer autoload loaded</p>";

        if (file_exists('/var/www/html/bootstrap/app.php')) {
            $app = require_once '/var/www/html/bootstrap/app.php';
            echo "<p class='success'>✅ Laravel application loaded</p>";

            // Test if we can access Laravel
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            echo "<p class='success'>✅ Laravel kernel accessible</p>";

            // Test environment loading
            if (method_exists($app, 'environment')) {
                echo "<p class='success'>✅ Environment: " . $app->environment() . "</p>";
            }

            // Test config access
            if ($app->bound('config')) {
                $config = $app->make('config');
                echo "<p class='success'>✅ Config service accessible</p>";
                echo "<p><strong>App Name:</strong> " . $config->get('app.name', 'Not Set') . "</p>";
                echo "<p><strong>App Debug:</strong> " . ($config->get('app.debug') ? 'true' : 'false') . "</p>";
                echo "<p><strong>App Key Set:</strong> " . ($config->get('app.key') ? '✅ Yes' : '❌ No') . "</p>";
            }

        } else {
            echo "<p class='error'>❌ Cannot find Laravel bootstrap file</p>";
        }
    } else {
        echo "<p class='error'>❌ Cannot find Composer autoload</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Laravel Bootstrap Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>5. Error Log Check</h2>";
$error_logs = [
    '/var/www/html/storage/logs/laravel.log',
    '/var/log/apache2/error.log',
];

foreach ($error_logs as $log_path) {
    if (file_exists($log_path) && is_readable($log_path)) {
        echo "<h3>$log_path</h3>";
        $lines = file($log_path);
        $recent_lines = array_slice($lines, -20); // Last 20 lines
        echo "<pre>" . htmlspecialchars(implode('', $recent_lines)) . "</pre>";
    } else {
        echo "<p class='warning'>⚠️ Cannot read log file: $log_path</p>";
    }
}

echo "<h2>6. Route Test</h2>";
try {
    if (isset($app)) {
        // Simulate a request to see if routing works
        $request = Illuminate\Http\Request::create('/register', 'GET');

        echo "<p class='success'>✅ Request object created</p>";

        // Try to get the route
        $router = $app->make('router');
        echo "<p class='success'>✅ Router accessible</p>";

    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Route Test Error: " . $e->getMessage() . "</p>";
}

echo "<h2>7. Database Test</h2>";
try {
    if (isset($app)) {
        $db = $app->make('db');
        $connection = $db->connection();
        echo "<p class='success'>✅ Database connection accessible</p>";

        // Try a simple query
        $result = $connection->select('SELECT 1 as test');
        echo "<p class='success'>✅ Database query successful</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
}

echo "<h2>8. Session Test</h2>";
try {
    if (isset($app)) {
        $session = $app->make('session');
        echo "<p class='success'>✅ Session service accessible</p>";

        // Test session configuration
        $config = $app->make('config');
        echo "<p><strong>Session Driver:</strong> " . $config->get('session.driver', 'Not Set') . "</p>";
        echo "<p><strong>Session Path:</strong> " . $config->get('session.files', 'Not Set') . "</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Session Error: " . $e->getMessage() . "</p>";
}

echo "<p><em>Debug completed at: " . date('Y-m-d H:i:s') . "</em></p>";
echo "<hr>";
echo "<p><strong style='color:red;'>IMPORTANT: Delete this file after debugging!</strong></p>";
?>
