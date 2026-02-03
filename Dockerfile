FROM php:8.4-cli-alpine

# System dependencies
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip \
    libpng \
    oniguruma \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    mysql-client \
    nodejs \
    npm

# Configure GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# PHP extensions (MySQL instead of Postgres)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction

# Copy the rest of the application
COPY . .

# Clear caches (safe if commands fail)
RUN php artisan config:clear || true \
    && php artisan route:clear || true

# Expose PHP development server
EXPOSE 8000

# Default command (can be overridden by docker-compose)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]