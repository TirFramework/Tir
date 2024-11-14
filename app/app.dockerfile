FROM composer:2.0
FROM php:8.2.0-fpm
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
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-install zip
RUN docker-php-ext-install sockets
RUN docker-php-ext-install bcmath



COPY --from=composer /usr/bin/composer /usr/local/bin/composer

COPY ./www/composer.json /var/www/

WORKDIR /var/www

# Use ARG to allow passing of UID and GID at build time
ARG UID
ARG GID

# Create group and user dynamically based on passed UID and GID
RUN groupadd -g ${GID} www \
    && useradd -u ${UID} -ms /bin/bash -g www www


COPY ./www /var/www
RUN chown -R www:www /var/www
COPY ./start.sh /usr/local/bin/start

USER www

EXPOSE 9000

CMD ["bash", "start"]