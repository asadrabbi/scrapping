FROM php:8.1-fpm

# PHP_CPPFLAGS are used by the docker-php-ext-* scripts
ENV PHP_CPPFLAGS="$PHP_CPPFLAGS -std=c++11"

# Set working directory
WORKDIR /var/www

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcached calendar

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx \
    cron

RUN echo "date.timezone = Asia/Dhaka" > /usr/local/etc/php/conf.d/timezone.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN pecl install mongodb && docker-php-ext-enable mongodb
# RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini
RUN pecl install apfd && docker-php-ext-enable apfd
RUN pecl install -o -f redis && rm -rf /tmp/pear && docker-php-ext-enable redis

# Install extensions
ENV NODE_VERSION=18.16.0
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash
#RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log
COPY . /var/www
COPY entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh

EXPOSE 8080