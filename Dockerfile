FROM php:8.2-apache 
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
# Install dependencies with proper permissions
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /usr/src/php/ext \
    && chmod 777 /usr/src/php/ext

# Install PHP extensions in temporary directory
WORKDIR /tmp
RUN docker-php-ext-install pdo_mysql mbstring dom
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configure Apache
RUN a2enmod rewrite
COPY . /var/www/html/
WORKDIR /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80