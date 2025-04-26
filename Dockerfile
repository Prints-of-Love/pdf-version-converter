FROM php:8.2.1-cli

RUN apt-get update && apt-get install -y git zip ghostscript

COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer

RUN git config --global --add safe.directory /var/www/html

WORKDIR /var/www/html