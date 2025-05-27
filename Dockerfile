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
# Copia todo el código primero
COPY . .
# Instala dependencias de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
# Instala dependencias de NPM y compila assets (Vite o Mix)
RUN npm install && npm run build
RUN chown -R www-data:www-data storage bootstrap/cache
EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

#////////////////////////////////////////////////////////////////////////////////////////////////////

# Etapa 1: Build de dependencias Node
# Etapa 1: Node modules
# Etapa 1: Node modules
# FROM node:20 AS node_modules

# WORKDIR /app
# COPY package*.json ./
# RUN npm install

# # Etapa 2: Composer dependencies
# FROM php:8.3-fpm AS composer

# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     libicu-dev \
#     libzip-dev \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     curl \
#     && docker-php-ext-install intl pdo pdo_mysql zip mbstring bcmath xml \
#     && rm -rf /var/lib/apt/lists/*

# COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# WORKDIR /app
# COPY . .
# RUN composer install --no-dev --optimize-autoloader --no-interaction

# # Etapa 3: Build de assets y optimización de Laravel
# FROM php:8.3-fpm AS app

# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     libicu-dev \
#     libzip-dev \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     curl \
#     nginx \
#     supervisor \
#     && docker-php-ext-install intl pdo pdo_mysql zip mbstring bcmath xml \
#     && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
#     && apt-get install -y nodejs \
#     && rm -rf /var/lib/apt/lists/*

# WORKDIR /var/www/html

# # Copia dependencias primero
# COPY --from=composer /app/vendor ./vendor
# COPY --from=node_modules /app/node_modules ./node_modules

# # Luego el resto del código
# COPY . .

# # Debug: verifica que public/index.php existe
# RUN ls -l /var/www/html/public && cat /var/www/html/public/index.php

# RUN npm run build

# # Limpia caches antes de cachear (opcional pero recomendado)
# RUN php artisan config:clear && \
#     php artisan route:clear && \
#     php artisan view:clear

# RUN php artisan config:cache && \
#     php artisan route:cache && \
#     php artisan view:cache

# RUN chown -R www-data:www-data storage bootstrap/cache

# # Copia la configuración de Nginx y supervisord
# COPY default.conf /etc/nginx/conf.d/default.conf
# COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# EXPOSE 80
# RUN which php-fpm
# RUN cat /etc/nginx/conf.d/default.conf
# RUN rm -rf /usr/share/nginx/html
# CMD ["/usr/bin/supervisord"]