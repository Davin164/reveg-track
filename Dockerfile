# Stage 1: PHP Dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --ignore-platform-reqs

# Stage 2: Node dependencies & build
FROM node:20-alpine AS node
WORKDIR /app
COPY package.json package-lock.json* vite.config.js ./
RUN npm install
COPY resources/ resources/
COPY public/ public/
RUN npm run build

# Stage 3: Final Production Image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy app files
COPY . .

# Copy vendor from Stage 1
COPY --from=vendor /app/vendor/ vendor/

# Copy built assets from Stage 2
# Note: vite builds to public/build usually
COPY --from=node /app/public/build/ public/build/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
