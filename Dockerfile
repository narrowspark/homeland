FROM php:7.2.3-cli

RUN apt-get update && \
    apt-get install -y apt-utils libxml2-dev curl zlib1g-dev libicu-dev git g++ unzip libtool make build-essential automake && \
    apt-get clean

RUN pecl install xdebug-2.6.0

RUN docker-php-ext-configure intl

RUN docker-php-ext-install opcache intl bcmath zip

RUN { \
        echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)"; \
        echo 'xdebug.remote_enable=on'; \
        echo 'xdebug.remote_autostart=off'; \
        echo 'xdebug.remote_port=9000'; \
    } > /usr/local/etc/php/conf.d/xdebug.ini

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /var/www/homeland
