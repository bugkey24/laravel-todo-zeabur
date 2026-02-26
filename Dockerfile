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
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd opcache

# Configure opcache for raw performance
RUN echo "opcache.enable=1\n\
opcache.enable_cli=1\n\
opcache.fast_shutdown=1\n\
opcache.interned_strings_buffer=8\n\
opcache.max_accelerated_files=10000\n\
opcache.memory_consumption=128\n\
opcache.save_comments=1\n\
opcache.revalidate_freq=2" > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

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
