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
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:7cKsxNhWv6iZDF08RhttrlyWK7qc1otlqEwvfrtnoHs=" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache Laravel configuration
echo "Optimizing Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache configurations for production
php artisan config:cache
php artisan view:cache
php artisan route:cache

echo "Starting Apache..."

# Start Apache in foreground
exec apache2-foreground
