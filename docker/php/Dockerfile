FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev netcat-openbsd \
    && docker-php-ext-install pdo pdo_pgsql mbstring

# Install Composer
COPY --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel app
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN composer install --no-interaction --optimize-autoloader \
    && php artisan key:generate \
    && php artisan cache:clear \
    && php artisan config:clear \
    && php artisan storage:link || true

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

ENTRYPOINT [ "entrypoint.sh" ]

CMD ["php-fpm"]
