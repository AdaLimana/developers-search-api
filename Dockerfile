FROM php:7.4-apache

ARG DEBIAN_FRONTEND=noninteractive

RUN apt update
RUN apt install bash unzip libpq-dev libxml2-dev -y
        
RUN docker-php-ext-install intl pdo pdo_pgsql pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

RUN mkdir -p /var/www/developers-search/developers-search-api

RUN mkdir -p /var/www/developers-search/developers-search-ui

RUN mkdir /var/log/apache2/developers-search/

RUN a2enmod rewrite

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;