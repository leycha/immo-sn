
FROM php:8.2-apache

# Extensions PHP nécessaires à IMMO-SN et Neon PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        gd \
        mbstring \
        opcache \
    && a2enmod rewrite alias \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de l'application
WORKDIR /var/www/html

# Copier le projet
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Apache utilise le dossier public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

# Rendre le dossier stockage accessible depuis le navigateur
RUN printf '%s\n' \
    'Alias /immo-sn/stockage/ /var/www/html/stockage/' \
    'Alias /stockage/ /var/www/html/stockage/' \
    '<Directory /var/www/html/stockage>' \
    '    Options FollowSymLinks' \
    '    AllowOverride None' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/immo-storage.conf \
    && a2enconf immo-storage

# Dossiers de stockage
RUN mkdir -p /var/www/html/stockage/photos \
    /var/www/html/stockage/logos \
    /var/www/html/stockage/qrcodes \
    /var/www/html/storage/reports \
    && chown -R www-data:www-data /var/www/html/stockage /var/www/html/storage

# Port Render
EXPOSE 10000

RUN sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/:80>/:10000>/' /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]
