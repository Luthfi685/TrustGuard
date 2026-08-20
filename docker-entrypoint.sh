#!/bin/sh
set -e

echo "=== Starting TrustGuard on Render ==="

# Pastikan folder storage dan database siap
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs database
touch database/database.sqlite
chmod -R 777 storage bootstrap/cache database

# Pastikan variabel environment penting terdefinisi
export APP_KEY="${APP_KEY:-base64:r3KtlzPeqEk4joYj2WidNqXGHySbBNLrV85QZe74ORs=}"
export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export DB_CONNECTION="${DB_CONNECTION:-sqlite}"

# Buat file .env jika belum ada dari .env.example
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        touch .env
    fi
fi

# Tuliskan APP_KEY ke dalam file .env jika belum ada
if ! grep -q "APP_KEY=base64:" .env; then
    echo "APP_KEY=$APP_KEY" >> .env
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
