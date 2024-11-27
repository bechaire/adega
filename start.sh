#!/bin/bash

# export COMPOSER_ALLOW_SUPERUSER=1
# composer install --no-dev --optimize-autoloader

docker compose up -d
symfony server:start -d
docker ps
