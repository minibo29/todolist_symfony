#!/bin/bash
# Logins to the running container.
# Usage: bin/login
. bin/helpers/common.sh

cd $LARADOCK_PATH

docker exec -u=$(id -u) -it -e APP_ENV=test $(echo  $COMPOSE_PROJECT_NAME)_workspace_1 php bin/console doctrine:fixtures:load "$@"