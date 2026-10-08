#!/bin/sh
set -e

mkdir -p var/smarty var/log
chown -R www-data:www-data var

composer install --no-interaction

exec docker-php-entrypoint "$@"
