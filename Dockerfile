FROM php:8.2-fpm

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Create SQLite database
RUN mkdir -p database
RUN touch database/database.sqlite

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Laravel setup
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan migrate --force

EXPOSE 10000

CMD php -S 0.0.0.0:10000 -t public