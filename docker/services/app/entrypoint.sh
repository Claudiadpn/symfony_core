#!/usr/bin/env sh

COMPOSER_FLAGS="--no-dev --optimize-autoloader --no-progress --prefer-dist --no-suggest"
if [[ ${APP_ENV:="prod"} != "prod" ]]; then
    COMPOSER_FLAGS="--no-progress --prefer-dist --profile --no-suggest"
fi

composer install ${COMPOSER_FLAGS}

chown -R www-data:www-data /app
chown -R www-data:www-data ${COMPOSER_HOME:="/var/lib/composer"}

/usr/local/bin/docker-php-entrypoint "$@"
