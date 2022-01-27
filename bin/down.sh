#!/bin/bash
#export DOCKER_HOST_IP=$(dig +short host.docker.internal)
. bin/helpers/common.sh

# shellcheck disable=SC2164
cd $LARADOCK_PATH

docker-compose down --remove-orphans