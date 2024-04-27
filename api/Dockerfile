FROM php:8.2-fpm-alpine
RUN  apk --update add wget \
    gcc \
    g++ \
    make \
    autoconf \
    unzip \
    zip \
    libmcrypt-dev \
    libpq-dev \
    jpegoptim \
    pngquant \
    optipng \
    gifsicle \
    libzip-dev \
    libwebp-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    coreutils \
    libltdl \
    libpng-dev \
    gmp-dev

RUN pecl install redis \
    && docker-php-ext-enable redis



RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN docker-php-ext-install opcache pdo pdo_pgsql pgsql gd zip pcntl gmp

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

#COPY docker/php/php.ini /usr/local/etc/php/php.ini
#COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

