FROM php:8.4-fpm AS builder

# Install system dependencies (build stage)
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    git \
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

# Configure PHP for production
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
    echo 'opcache.revalidate_freq = 0'; \
} > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

# Copy composer files and install dependencies (before copying app code for caching)
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-ansi --no-progress --prefer-dist

# Production image (minimal, runtime-only)
FROM php:8.4-fpm

# Install runtime dependencies only (no build tools)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng6t64 \
    libjpeg62-turbo \
    libfreetype6 \
    libzip4 \
    libonig5 \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Copy PHP extensions from builder (faster than reinstalling)
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions

# Enable extensions in production stage
RUN docker-php-ext-enable pdo_mysql mbstring zip gd opcache redis || true

WORKDIR /var/www/html

# Copy vendor from builder first (cached dependency layer)
COPY --from=builder /var/www/html/vendor ./vendor

# Copy application code
COPY --chown=www-data:www-data . .

# Create PHP config with optimized settings
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
    echo 'opcache.revalidate_freq = 0'; \
    echo 'opcache.preload_user = www-data'; \
    echo 'error_log = /dev/stderr'; \
} > /usr/local/etc/php/conf.d/custom.ini

# Set storage and cache permissions
RUN chmod -R 755 storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Precompile Laravel caches
RUN php artisan optimize || true

EXPOSE 9000
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD php-fpm -v || exit 1

CMD ["php-fpm"]
