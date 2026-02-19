FROM php:8.3-apache

# Dépendances système + extensions PHP utiles
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-install zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Apache DocumentRoot -> /public
COPY docker/php/apache-vhost.conf /etc/apache2/sites-available/000-default.conf



WORKDIR /var/www/html
