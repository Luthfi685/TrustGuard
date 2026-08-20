#!/bin/sh
set -e

echo "=== Starting TrustGuard on Render ==="

# Pastikan folder storage dan database siap
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs database
touch database/database.sqlite
chmod -R 777 storage bootstrap/cache database

# Buat file .env jika belum ada dari .env.example
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        touch .env
    fi
fi

# Jika APP_KEY belum ada, generate otomatis
if [ -z "$APP_KEY" ]; then
    echo "Generating new APP_KEY..."
    php artisan key:generate --force || true
fi

# Discover packages
php artisan package:discover --ansi || true

# Jalankan migrasi database
echo "Running migrations..."
php artisan migrate --force || echo "Warning: Migration failed, proceeding anyway."

# Bersihkan cache
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

PORT_NUM="${PORT:-10000}"
echo "TrustGuard is listening on 0.0.0.0:$PORT_NUM"

exec php artisan serve --host=0.0.0.0 --port="$PORT_NUM"
