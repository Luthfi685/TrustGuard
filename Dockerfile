FROM php:8.2-cli

# Install system dependencies & essential PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_sqlite sockets zip mbstring bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

# Install PHP dependencies safely (without artisan scripts during build)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 10000

# Entrypoint runtime commands
CMD touch database/database.sqlite \
    && chmod -R 777 database \
    && php artisan package:discover --ansi \
    && php artisan migrate --force \
    && php artisan config:clear \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
