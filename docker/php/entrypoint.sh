#!/bin/sh
set -e

composer install --no-interaction

exec docker-php-entrypoint "$@"
