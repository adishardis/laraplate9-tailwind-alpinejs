# using php8.1-fpm image
FROM php:8.1-fpm

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apt-get update && apt-get install -y \
    autoconf \
    pkg-config \
    libssl-dev \
    git \
    unzip \
    wget \
    ntp \
    gnupg \
    nodejs npm \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zlib1g-dev && \
    pecl install mongodb && docker-php-ext-enable mongodb && \
    pecl install xdebug && docker-php-ext-enable xdebug && \
    docker-php-ext-install -j$(nproc) zip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

# GD Library extension
# RUN apt-get install php8.1-gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Asia/Jakarta /etc/localtime
RUN "date"

WORKDIR /var/www/html