# Menggunakan image PHP 8.1 FPM dengan Alpine
FROM php:8.1-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    mysql-client \
    && apk add --no-cache --virtual .build-deps \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql mysqli \
    && apk del --no-cache .build-deps

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


WORKDIR /var/www/html

# Copy aplikasi
COPY . .

# Install dependencies (tanpa dev dependencies untuk production)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Optimize Laravel untuk production
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan storage:link \
    && sed -i 's/APP_ENV=local/APP_ENV=production/' .env \
    && sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env \
    && sed -i 's/LOG_CHANNEL=stack/LOG_CHANNEL=stderr/' .env \
    && sed -i 's/SESSION_DRIVER=database/SESSION_DRIVER=file/' .env

# Set permissions
RUN mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html

# Set PHP production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "memory_limit = 256M" >> "$PHP_INI_DIR/conf.d/memory-limit.ini" \
    && echo "upload_max_filesize = 50M" >> "$PHP_INI_DIR/conf.d/upload.ini" \
    && echo "post_max_size = 50M" >> "$PHP_INI_DIR/conf.d/upload.ini" \
    && echo "max_execution_time = 300" >> "$PHP_INI_DIR/conf.d/execution-time.ini" \
    && echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini"

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]