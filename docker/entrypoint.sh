#!/bin/bash
set -e

echo "Esperando a MySQL..."
until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -P"$DB_PORT" -e "SELECT 1;" 2>/dev/null; do
  sleep 2
done

echo "MySQL está listo"

php artisan migrate --force || true

apache2-foreground
