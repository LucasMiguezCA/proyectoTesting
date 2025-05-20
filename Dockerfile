# FROM php:8.3-fpm

# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     libicu-dev \
#     libzip-dev \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     curl \
#     && docker-php-ext-install intl pdo pdo_mysql zip mbstring bcmath xml

# COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
#     && apt-get install -y nodejs

# WORKDIR /var/www/html

# COPY . .

# RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# RUN npm install && npm run build

# RUN chown -R www-data:www-data storage bootstrap/cache

# EXPOSE 8000

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

FROM php:8.3-fpm

# Instala dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install intl pdo pdo_mysql zip mbstring bcmath xml

# Instala Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Instala Node.js (para compilar assets)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www/html

# Copia solo composer.json y composer.lock primero para aprovechar el cache de Docker
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copia el resto del código
COPY . .

# Instala dependencias de NPM y compila assets (Vite o Mix)
RUN npm install && npm run build

# Da permisos a las carpetas necesarias
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

# Comando por defecto para producción
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]