FROM php:8.2-fpm

# Instala dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    curl \
    npm \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Instala Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Instala Node.js (para compilar assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instala dependencias de NPM y compila assets (ajusta el comando si usas Mix en vez de Vite)
RUN npm install && npm run build

# Da permisos a las carpetas necesarias
RUN chown -R www-data:www-data storage bootstrap/cache

# Expone el puerto 8000 (usado por Laravel artisan serve)
EXPOSE 8000

# Comando por defecto para producción (puedes cambiarlo por nginx si lo usas)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]