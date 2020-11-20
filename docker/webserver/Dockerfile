ARG PHP_VERSION=7.4
FROM devilster/dev-php-apache2:${PHP_VERSION}

MAINTAINER Tilikov Vyacheslav

ARG PHP_VERSION

RUN apt-get update && apt-get install -y --no-install-recommends \
    --allow-downgrades --allow-remove-essential --allow-change-held-packages \
        \
    && docker-php-ext-install -j$(nproc) intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www

ENV PATH ~/.composer/vendor/bin:$PATH