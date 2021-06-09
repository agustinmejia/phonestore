# PhoneStore
Sistema para la administración de tiendas de celulares y accesorios en gral.

## Instalación
```
sudo apt-get install php7.4-mbstring php7.4-intl php7.4-dom php7.4-gd php7.4-xml php7.4-mbstring php*-mysql
composer install
cp .env.example .env
php artisan migrate --seed
php artisan storage:link
php artisan key:generate
chmod -R 777 storage bootstrap/cache
```