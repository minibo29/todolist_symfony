#!/bin/bash
# Logins to the running container.
# Usage: bin/login
. bin/helpers/common.sh

cd $LARADOCK_PATH

docker exec -u=$(id -u) -it $(echo $COMPOSE_PROJECT_NAME)_workspace_1  php ./vendor/bin/phpstan "$@"