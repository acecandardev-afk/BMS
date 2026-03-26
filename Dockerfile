# Production-oriented image — tune for your host (nginx unit, Octane, etc.).
FROM php:8.3-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo_pgsql pdo_mysql zip opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

ENV APP_ENV=production

EXPOSE 8000

# For real production, use php-fpm + nginx or Laravel Octane; this entrypoint is a minimal smoke run.
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
