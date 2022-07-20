FROM php:7.4-cli as queue

WORKDIR /var/src/app

RUN docker-php-ext-install pdo_mysql \
 && pecl install redis-5.3.2 \
 && docker-php-ext-enable redis

FROM php:7.4-apache as web

RUN docker-php-ext-install pdo_mysql \
 && pecl install redis-5.3.2 \
 && docker-php-ext-enable redis

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite headers

FROM web as build

RUN apt-get update && apt-get install -y git libzip-dev zip
RUN docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY composer.lock composer.json ./
# workaround to generate composer classmap
COPY database/ ./database
RUN composer install --optimize-autoloader --prefer-dist --no-cache
RUN ls -la

FROM web as runtime

COPY --from=build /var/www/html/vendor/ ./
COPY . ./
