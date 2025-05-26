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


#////////////////////////////////////////////////////////////////////////////////////////////////////

# Dockerfile para una aplicación Laravel con PHP 8.3, Composer y Node.js
# FROM php:8.3-fpm

# # Instala dependencias del sistema y extensiones de PHP necesarias
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

# # Instala Composer
# COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# # Instala Node.js (para compilar assets)
# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
#     && apt-get install -y nodejs

# WORKDIR /var/www/html

# # Copia todo el código primero
# COPY . .

# # Instala dependencias de Composer
# RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# # Instala dependencias de NPM y compila assets (Vite o Mix)
# RUN npm install && npm run build

# RUN chown -R www-data:www-data storage bootstrap/cache

# EXPOSE 8000

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

#////////////////////////////////////////////////////////////////////////////////////////////////////

# Etapa 1: Build de dependencias Node
FROM node:20 AS node_modules

WORKDIR /app
COPY package*.json ./
RUN npm install

# Etapa 2: Build de dependencias Composer
FROM composer:2.7 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Etapa 3: PHP + dependencias + build de assets
FROM php:8.3-fpm AS app

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

WORKDIR /var/www/html

# Copia dependencias instaladas
COPY --from=composer /app/vendor ./vendor
COPY --from=node_modules /app/node_modules ./node_modules

# Copia el resto del código
COPY . .

# Compila los assets
RUN npm run build

# Optimiza Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

RUN chown -R www-data:www-data storage bootstrap/cache

# Etapa 4: Nginx + PHP-FPM para producción
FROM nginx:alpine

# Copia el código y assets ya construidos
COPY --from=app /var/www/html /var/www/html

# Copia la configuración de Nginx
COPY default.conf /etc/nginx/conf.d/default.conf

# Expone el puerto 80
EXPOSE 80

WORKDIR /var/www/html

CMD ["nginx", "-g", "daemon off;"]