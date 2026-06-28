#!/bin/sh
set -e

# Garante o volume persistente do SQLite (montado em /data pelo Fly)
mkdir -p /data
FRESH=0
if [ ! -f /data/database.sqlite ]; then
    touch /data/database.sqlite
    FRESH=1
fi
chown www-data:www-data /data/database.sqlite || true

# Migrações (idempotentes) e seed só na primeira subida
php artisan migrate --force --no-interaction || true
if [ "$FRESH" = "1" ]; then
    php artisan db:seed --force --no-interaction || true
fi

php artisan storage:link || true
php artisan config:clear || true

exec apache2-foreground
