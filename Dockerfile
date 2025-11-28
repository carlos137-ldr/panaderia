# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala dependencias del sistema necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    mariadb-client

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Copiar archivos del proyecto
COPY . /var/www/html

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias Laravel
RUN composer install --optimize-autoloader --no-dev

# Optimizar Laravel
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan optimize

# 🔥 Ejecutar migraciones ANTES de arrancar Apache
CMD php artisan migrate --force && apache2-foreground
