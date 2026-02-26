FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Setup Permissions
RUN chown -R www-data:www-data /var/www/html

USER www-data

# The actual source code copying will happen via volumes in dev, 
# or can be added here for production images. For Zeabur, Zeabur wraps this properly or you can build it.
# To make this a robust production image as well:
COPY --chown=www-data:www-data . /var/www/html

# Note: composer install is often run here for prod:
# RUN composer install --no-interaction --optimize-autoloader --no-dev
