FROM php:8.4-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install core system dependencies
RUN apk add --no-cache \
    libpq-dev \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    linux-headers \
    zlib-dev \
    nodejs \
    npm

# Install PHP extensions for Laravel & Postgres
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl bcmath gd

# Install Redis extension via PECL
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del pcre-dev $PHPIZE_DEPS

# Clean cache
RUN rm -rf /var/cache/apk/*

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Switch to standard www-data user to avoid root permissions issues
RUN chown -R www-data:www-data /var/www

EXPOSE 9000

CMD ["php-fpm"]
