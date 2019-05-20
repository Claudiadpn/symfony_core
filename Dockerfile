FROM        php:7.2-fpm-alpine
MAINTAINER  Arnaud Ponel <arnaud@xpressive.io>

RUN         apk add --no-cache \
            --repository http://dl-cdn.alpinelinux.org/alpine/edge/main \
            --repository  http://dl-cdn.alpinelinux.org/alpine/edge/community \
            shadow

RUN         usermod -u 1000 www-data && \
            groupmod -g 1000 www-data

RUN         mkdir -p /app
COPY        --chown=www-data:www-data . /app

ENV         COMPOSER_HOME=/var/lib/composer
RUN         mkdir -p $COMPOSER_HOME
RUN         chown -R www-data:www-data $COMPOSER_HOME
RUN         cd /tmp && /app/docker/services/app/install_composer.sh

USER        www-data
RUN         composer global require hirak/prestissimo
USER        root

VOLUME      /app
WORKDIR     /app

ENV         APP_ENV=prod

ENTRYPOINT  ["/app/docker/services/app/entrypoint.sh"]
CMD         ["php-fpm"]
