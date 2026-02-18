FROM php:8.3-fpm

# system deps
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libxml2-dev \
    libzip-dev nginx supervisor \
    libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath zip intl gd


# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader -vvv

RUN cp .env.example .env || true
RUN php artisan key:generate || true

# nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

CMD php artisan migrate --force && \
    php-fpm -D && \
    nginx -g "daemon off;"
