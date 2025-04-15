FROM php:8.2-cli

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev libpq-dev libmcrypt-dev mariadb-client \
    && docker-php-ext-install pdo_mysql mbstring zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
