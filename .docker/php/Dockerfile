FROM php:7.4-fpm-alpine

WORKDIR /var/www

RUN apk add --update \
    wget \
    curl \
    bash \
    git \
    composer
    
RUN apk add --no-cache \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
    && rm -rf /tmp/*

RUN apk add libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip bcmath

# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug

COPY php7.ini /usr/local/etc/php/conf.d/php7.ini

RUN apk add --update shadow \
    && usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

EXPOSE 9000

CMD ["php-fpm"]