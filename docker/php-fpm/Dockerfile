FROM registry.gigwork.ru/docker/php-80

RUN apk add --update php8-fpm

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


COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www

COPY www.conf /etc/php8/php-fpm.d/

ENTRYPOINT ["/usr/sbin/php-fpm8"]
CMD ["--nodaemonize", "--fpm-config", "/etc/php8/php-fpm.conf"]

EXPOSE 9000
