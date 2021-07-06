# Instalación

---

- [Requisitos](#section-features)
- [Instalación](#section-instalation)

Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.

<a name="section-requisites"></a>
## Requisitos

- Instalar la pila lemp en tu servidor [LEMP](https://www.digitalocean.com/community/tutorials/como-instalar-linux-nginx-mysql-php-pila-lemp-en-ubuntu-18-04-es).
- Debe tener todos los requerimientos de [Laravel 8](https://laravel.com/docs/8.x/deployment#server-requirements).
- Extensiones necesarias para Laravel: 
```bash
sudo apt-get install php7.4-mbstring php7.4-intl php7.4-dom php7.4-gd php7.4-xml php7.4-mbstring php*-mysql
```
- Crear base de datos con el nombre **phonestore**.

<a name="section-instalation"></a>
## Instalación

- Instalar las depedencias del proyecto:
```bash
composer install
```

- Copiar el archivo de configuración del proyectos:
```bash
cp .env.example .env
```

- Editar datos de la conexión en el archivo .env:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phonestore
DB_USERNAME=root
DB_PASSWORD=password
```

- Instalar **PhoneStore**:
```bash
php artisan phonestore:install
```

- Dar permisos de escritura a los directorios públicos:
```bash
chmod -R 777 storage bootstrap/cache
```