FROM php:8.4-cli-bookworm

RUN apt-get update && apt-get install -y \
    bash git curl unzip zip \
    # build deps for php extensions
    pkg-config \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    # mysql client
    default-mysql-client \
    # wkhtmltopdf + fonts
    wkhtmltopdf \
    fontconfig xfonts-75dpi xfonts-base \
    # node
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo pdo_mysql mbstring exif pcntl bcmath gd intl opcache zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps first (better caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-interaction

# Copy app
COPY . .

# Clear caches (safe)
RUN php artisan config:clear || true \
    && php artisan route:clear || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
