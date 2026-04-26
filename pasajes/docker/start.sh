#!/usr/bin/env sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist
fi

if [ ! -d node_modules ]; then
  npm install
fi

if [ ! -f public/build/manifest.json ]; then
  npm run build
fi

if ! grep -q "^APP_KEY=base64:" .env; then
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan db:seed --force
php artisan storage:link || true

php artisan serve --host=0.0.0.0 --port=8000