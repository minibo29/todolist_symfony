#!/bin/bash

. bin/helpers/common.sh

# shellcheck disable=SC2164
cd $LARADOCK_PATH

docker-compose up -d --build workspace mysql nginx php-fpm