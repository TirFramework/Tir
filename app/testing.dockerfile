FROM php:8.2-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    curl \
    zip \
    libzip-dev \
    icu-dev \
    autoconf \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    intl

# Install PCOV for code coverage (faster than Xdebug)
RUN pecl install pcov && docker-php-ext-enable pcov

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Create a user with the same UID/GID as host user
ARG UID=1000
ARG GID=1000
RUN addgroup -g ${GID} testuser && \
    adduser -u ${UID} -G testuser -D testuser

# Switch to testuser
USER testuser

# Set environment variables for testing
ENV PCOV_ENABLED=1
ENV PCOV_DIRECTORY=/var/www/vendor/tir/crud/src

CMD ["php", "-v"]
