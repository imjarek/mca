ARG PHP_VERSION=8.0.3
FROM php:${PHP_VERSION}-cli
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    libpq-dev \
    wget \
    zlib1g-dev \
    libmcrypt-dev \
    libzip-dev \
    librdkafka-dev \
    libpng-dev \
    unzip \
    librabbitmq-dev \
    && pecl install rdkafka ast xdebug amqp-1.11.0beta \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-enable rdkafka ast xdebug amqp \
    && docker-php-ext-install pdo pdo_pgsql zip sysvsem pcntl gd \
    && wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet \
#    && wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && apt autoremove -y