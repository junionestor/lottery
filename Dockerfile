FROM php:8.4-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --no-dev --prefer-dist

RUN chown -R www-data:www-data /var/www/html

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html/routes/"]