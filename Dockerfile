FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libcurl4-openssl-dev \
    && docker-php-ext-install curl \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --prefer-dist --no-progress --no-suggest

COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

CMD ["php", "-a"]
