# Set the base image for subsequent instructions
FROM php:8.2-fpm

# Add build arguments for user/group IDs
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip 

RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Change user/group IDs for www-data to match host
RUN usermod -u ${USER_ID} www-data && \
groupmod -g ${GROUP_ID} www-data

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files with correct ownership
COPY --chown=www-data:www-data . /var/www

# Ensure storage and bootstrap/cache directories are writable
RUN mkdir -p storage/framework/{views,sessions,cache} && \
    chmod -R 775 storage bootstrap/cache

# Change current user to www-data
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000

RUN composer install

CMD ["php-fpm"]

