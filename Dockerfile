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
