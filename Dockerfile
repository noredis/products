FROM php:8.5-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www/app

COPY --chown=www:www composer.json composer.lock symfony.lock ./
RUN composer install --no-autoloader --no-scripts

COPY --chown=www:www . .

RUN composer dump-autoload --optimize && \
    composer run-script post-install-cmd

USER www

CMD ["php-fpm"]
