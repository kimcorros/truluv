#!/usr/bin/env bash
set -e

cd /var/www/html

# Ensure an environment file and application key exist.
if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# Wait for the database to accept connections.
echo "Waiting for MySQL at ${DB_HOST:-mysql}:${DB_PORT:-3306}..."
until php -r '$h=getenv("DB_HOST")?:"mysql"; $p=(int)(getenv("DB_PORT")?:3306); exit(@fsockopen($h,$p) ? 0 : 1);'; do
    sleep 2
done
echo "MySQL is up."

# Migrate and seed demo data on first boot (idempotent).
php artisan app:setup

# Serve the application.
exec php artisan serve --host=0.0.0.0 --port=8000
