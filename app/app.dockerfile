FROM composer:2.0
FROM php:8.1.0-fpm
USER root

# Install dependencies
RUN apt-get update && apt-get install -y \
    openssl  \
    libssl-dev \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg* \
    libfreetype6-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install sockets
RUN docker-php-ext-install bcmath
RUN pecl install mongodb
RUN docker-php-ext-enable mongodb
RUN pecl install redis
RUN docker-php-ext-enable redis


COPY --from=composer /usr/bin/composer /usr/local/bin/composer

COPY ./project-files/composer.json /var/www/

WORKDIR /var/www

RUN groupadd -g 1000 www

RUN useradd -u 1000 -ms /bin/bash -g www www


COPY ./project-files /var/www
RUN chown -R www:www /var/www
COPY ./start.sh /usr/local/bin/start

USER www

EXPOSE 9000

CMD ["bash", "start"]
