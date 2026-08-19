FROM php:8.3-cli

# Use official extension installer for bulletproof PHP 8.3 extension installation
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_sqlite sockets zip pcntl bcmath curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

# Install PHP dependencies for Laravel 11/12
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

CMD touch database/database.sqlite \
    && chmod -R 777 database \
    && php artisan package:discover --ansi \
    && php artisan migrate --force \
    && php artisan config:clear \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
