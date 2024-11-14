# Use the official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies and PHP extensions for PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application code to the working directory
COPY src/ .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install

# Expose port for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
