FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

# copy project files
COPY --chown=$user:www-data . /var/www
#COPY --chown=$user:www-data ./.env.example /var/www/.env
# RUN composer install
# RUN php artisan key:generate
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER $user
# updating view
# RUN service start
#CMD bash -c "php artisan optimize && service supervisord start && supervisorctl start all && php-fpm"
CMD ["/usr/bin/supervisord"]

