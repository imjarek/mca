FROM php:8.0.6-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    wget \
    libssl-dev \
    zlib1g-dev \
    curl \
    git \
    unzip \
    netcat \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    librabbitmq-dev && \
    pecl install amqp xdebug && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-configure zip && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    docker-php-ext-enable amqp zip xdebug pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV LIBRDKAFKA_VERSION v1.8.2
ENV BUILD_DEPS \
        build-essential \
        git \
        libsasl2-dev \
        zlib1g-dev
RUN apt-get update \
    && apt-get install -y --no-install-recommends ${BUILD_DEPS} \
    && cd /tmp \
    && git clone \
        --branch ${LIBRDKAFKA_VERSION} \
        --depth 1 \
        https://github.com/edenhill/librdkafka.git \
    && cd librdkafka \
    && ./configure \
    && make \
    && make install \
    && pecl install rdkafka \
    && rm -rf /tmp/librdkafka \
    && apt-get purge \
        -y --auto-remove \
        -o APT::AutoRemove::RecommendsImportant=false \
        ${BUILD_DEPS}

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./zip.ini /usr/local/etc/php/conf.d/docker-php-ext-zip.ini
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini


WORKDIR /var/www

CMD composer i -o ; wait-for-it db:5432 -- bin/console doctrine:migrations:migrate ;  php-fpm 

EXPOSE 9000
