# Menggunakan existing image
FROM teguhyuhono/satria-eko:latest

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#remove existing entrypoint script
RUN rm -rf /entrypoint.sh

# copy entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

#remove folder /var/www/html
RUN rm -rf /var/www/html
#create folder /var/www/html
RUN mkdir -p /var/www/html

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

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]