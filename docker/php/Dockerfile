FROM php:8.4-fpm
ARG TIMEZONE

RUN apt-get update && apt-get install -y \
    openssl \
    libicu-dev \
    git \
    unzip \
    wget \
    libpng-dev \
    libzip-dev \
    librabbitmq-dev \
    bash-completion

RUN echo "memory_limit = 1024M" >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN docker-php-ext-install pdo pdo_mysql zip intl opcache pcntl gd \
    && docker-php-ext-configure intl \
    && pecl install amqp \
    && docker-php-ext-enable amqp

WORKDIR /var/www
