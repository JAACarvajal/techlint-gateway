#!/bin/sh

# Fix ownership at runtime
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

exec "$@"
