<?php
// Debug script to check Laravel configuration
// Upload this to your Render deployment and visit it to diagnose issues

echo "<h1>Laravel Debug Information</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .error{color:red;} .success{color:green;} .warning{color:orange;}</style>";

echo "<h2>1. PHP Environment</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' . "</p>";

echo "<h2>2. File Structure Check</h2>";
$files = [
    '/var/www/html/.env' => '.env file',
    '/var/www/html/vendor/autoload.php' => 'Composer autoload',
    '/var/www/html/bootstrap/app.php' => 'Laravel bootstrap',
    '/var/www/html/public/index.php' => 'Public index',
];

foreach ($files as $path => $desc) {
    $exists = file_exists($path);
    $class = $exists ? 'success' : 'error';
    $status = $exists ? '✅ Found' : '❌ Missing';
    echo "<p class='$class'><strong>$desc:</strong> $status</p>";
}

echo "<h2>3. Environment Variables</h2>";
$env_vars = ['APP_KEY', 'APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
foreach ($env_vars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'Not Set';
    $masked = in_array($var, ['APP_KEY', 'DB_PASSWORD']) ? str_repeat('*', 10) : $value;
    echo "<p><strong>$var:</strong> $masked</p>";
}

echo "<h2>4. Route Test</h2>";
try {
    if (file_exists('/var/www/html/vendor/autoload.php')) {
        require_once '/var/www/html/vendor/autoload.php';

        if (file_exists('/var/www/html/bootstrap/app.php')) {
            $app = require_once '/var/www/html/bootstrap/app.php';
            echo "<p class='success'>✅ Laravel application loaded successfully</p>";

            // Test if we can access Laravel
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            echo "<p class='success'>✅ Laravel kernel accessible</p>";

        } else {
            echo "<p class='error'>❌ Cannot find Laravel bootstrap file</p>";
        }
    } else {
        echo "<p class='error'>❌ Composer autoload not found</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Laravel Error: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Database Test</h2>";
try {
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
    $db = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE');
    $user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
    $pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

    if ($host && $db && $user) {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        echo "<p class='success'>✅ Database connection successful</p>";

        // Check for tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><strong>Tables found:</strong> " . count($tables) . "</p>";

    } else {
        echo "<p class='warning'>⚠️ Database credentials not set</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Database Error: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Permissions Check</h2>";
$dirs = ['/var/www/html/storage', '/var/www/html/bootstrap/cache'];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir) ? '✅ Writable' : '❌ Not writable';
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "<p><strong>$dir:</strong> $writable (Permissions: $perms)</p>";
    } else {
        echo "<p class='error'><strong>$dir:</strong> ❌ Directory not found</p>";
    }
}

echo "<h2>7. Web Server Test</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown' . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] ?? 'Unknown' . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] ?? 'Unknown' . "</p>";

echo "<hr>";
echo "<p><strong style='color:red;'>IMPORTANT: Delete this file after debugging!</strong></p>";
?>
