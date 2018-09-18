FROM php:7.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    libmcrypt-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    libxrender1 \
    libfontconfig \
    libxext6 \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

RUN docker-php-ext-install \
    pdo_mysql \
    pcntl \
    bcmath \
    zip \
    opcache

RUN pecl install apcu \
    && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer

RUN export COMPOSER_ALLOW_SUPERUSER=1

COPY app-php.ini /etc/php/7.2/fpm/conf.d/app-php.ini

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY docker-php-entrypoint /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

ENTRYPOINT ["docker-php-entrypoint"]
