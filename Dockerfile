FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    unzip \
    zip \
    && apt-get clean

RUN docker-php-ext-install zip \
    && docker-php-ext-enable zip


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && git config --global --add safe.directory /var/www/html

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html/routes/"]