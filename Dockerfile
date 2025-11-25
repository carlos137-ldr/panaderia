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
    libzip-dev

# Limpia caché para reducir el tamaño de la imagen
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensiones de PHP requeridas
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Habilita el módulo rewrite de Apache (indispensable para Laravel)
RUN a2enmod rewrite

# Configura el directorio raíz de Apache para que apunte a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Esto cambia AllowOverride None a AllowOverride All para que el .htaccess funcione
RUN sed -i '/<Directory \${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# -------------------------

# Instala Composer (el gestor de paquetes de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia todos los archivos del proyecto al contenedor
COPY . .

# Instala las dependencias de PHP (Laravel)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Da permisos a las carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto 80 (Render usa este puerto internamente para mapearlo)
EXPOSE 80

# El comando por defecto inicia Apache
CMD ["apache2-foreground"]