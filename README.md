# PhoneStore
Sistema para la administración de tiendas de celulares y accesorios en gral.

## Instalación
```
composer install
cp .env.example .env
php artisan migrate --seed
php artisan storage:link
php artisan key:generate
chmod -R 777 storage bootstrap/cache
```