# Single application image: PHP 8.4 CLI + Node (for building assets).
# The app is served with `php artisan serve`, which is plenty for a PoC.
FROM php:8.4-cli-bookworm

# System dependencies.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip zip curl ca-certificates \
        libzip-dev libicu-dev libonig-dev libsqlite3-dev \
        libpng-dev libjpeg-dev libfreetype6-dev \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions. pdo_sqlite is included so the test suite (which runs on an
# in-memory SQLite database) can be executed inside the container.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql pdo_sqlite zip bcmath intl gd exif pcntl

# Node 22 (for the Vite asset build).
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_MEMORY_LIMIT=-1
WORKDIR /var/www/html

# Application source (see .dockerignore for exclusions).
COPY . .

# Install PHP and JS dependencies, then build front-end assets. A throwaway
# APP_KEY is generated only so Wayfinder can enumerate routes during the build;
# the runtime key is handled by the entrypoint.
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate \
    && npm ci \
    && npm run build \
    && npm cache clean --force \
    && rm -f .env

RUN cp docker/entrypoint.sh /usr/local/bin/entrypoint && chmod +x /usr/local/bin/entrypoint

EXPOSE 8000
ENTRYPOINT ["entrypoint"]
