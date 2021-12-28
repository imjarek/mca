FROM registry.gigwork.ru/docker/php-80


RUN echo 'memory_limit = 2048M' >> /etc/php8/conf.d/docker-php-memlimit.ini

# Ставим symfony
RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# PHP_CodeSniffer && PHP_CodeSniffer Standards Composer Installer Plugin && PHP_compatibility
RUN composer global require "squizlabs/php_codesniffer" \
    && composer global require "dealerdirect/phpcodesniffer-composer-installer" \
    && composer global require "phpcompatibility/php-compatibility" \
    && composer global install \
    && ln -s /root/.composer/vendor/bin/phpcs /usr/bin/phpcs \
    && ln -s /root/.composer/vendor/bin/phpcbf /usr/bin/phpcf


#ENV LIBRDKAFKA_VERSION v1.8.2
#ENV BUILD_DEPS \
#        build-essential \
#        git \
#        libsasl2-dev \
#        zlib1g-dev
#RUN apt-get update \
#    && apt-get install -y --no-install-recommends ${BUILD_DEPS} \
#    && cd /tmp \
#    && git clone \
#        --branch ${LIBRDKAFKA_VERSION} \
#        --depth 1 \
#        https://github.com/edenhill/librdkafka.git \
#    && cd librdkafka \
#    && ./configure \
#    && make \
#    && make install \
#    && pecl install rdkafka \
#    && rm -rf /tmp/librdkafka \
#    && apt-get purge \
#        -y --auto-remove \
#        -o APT::AutoRemove::RecommendsImportant=false \
#        ${BUILD_DEPS}

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini


WORKDIR /var/www

CMD composer i -o ; php

EXPOSE 9000
