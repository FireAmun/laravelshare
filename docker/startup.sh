#!/bin/bash

echo "Starting Laravel application..."

# Force HTTPS for Laravel when behind proxy
export HTTPS=on
export SERVER_PORT=443

# Create required directories
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/bootstrap/cache

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Create storage link if it doesn't exist
if [ ! -e /var/www/html/public/storage ]; then
    ln -s /var/www/html/storage/app/public /var/www/html/public/storage
fi

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:YOUR_APP_KEY_HERE" ] || [ "$APP_KEY" = "base64:7cKsxNhWv6iZDF08RhttrlyWK7qc1otlqEwvfrtnoHs=" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache Laravel configuration
echo "Optimizing Laravel..."
php artisan config:clear 2>&1 || echo "Config clear failed"
php artisan cache:clear 2>&1 || echo "Cache clear failed"
php artisan view:clear 2>&1 || echo "View clear failed"
php artisan route:clear 2>&1 || echo "Route clear failed"

# Cache configurations for production
php artisan config:cache 2>&1 || echo "Config cache failed"
php artisan view:cache 2>&1 || echo "View cache failed"
php artisan route:cache 2>&1 || echo "Route cache failed"

# Enable error logging
echo "Enabling error logging..."
echo "log_errors = On" >> /usr/local/etc/php/php.ini
echo "error_log = /var/log/apache2/php_errors.log" >> /usr/local/etc/php/php.ini
echo "display_errors = Off" >> /usr/local/etc/php/php.ini

echo "Starting Apache..."

# Start Apache in foreground
exec apache2-foreground
