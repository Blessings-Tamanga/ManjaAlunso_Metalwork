FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    gd \
    opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN { \
    echo 'upload_max_filesize = 50M'; \
    echo 'post_max_size = 50M'; \
    echo 'memory_limit = 512M'; \
    echo 'max_execution_time = 120'; \
    echo 'date.timezone = UTC'; \
    echo 'opcache.enable = 1'; \
    echo 'opcache.memory_consumption = 128'; \
    echo 'opcache.interned_strings_buffer = 8'; \
    echo 'opcache.max_accelerated_files = 10000'; \
    echo 'opcache.validate_timestamps = 0'; \
} > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
