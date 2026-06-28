FROM php:8.2-apache

# Dependências do sistema + extensões PHP
RUN apt-get update && apt-get install -y \
        libzip-dev libpng-dev libonig-dev libsqlite3-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring zip gd \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Aponta o Apache para /public, escuta na 8080 e habilita rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's!Listen 80!Listen 8080!g' /etc/apache2/ports.conf \
    && sed -ri -e 's!:80>!:8080>!g' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

WORKDIR /var/www/html

# Instala dependências PHP (sem dev)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copia o restante da aplicação
COPY . .
RUN composer dump-autoload --optimize --no-interaction \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8080
CMD ["/usr/local/bin/entrypoint.sh"]
