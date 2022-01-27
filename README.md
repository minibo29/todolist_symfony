# Todo-list Symfony

Pet project

### Software needed

You have Linux + docker + docker-compose installed.

Make sure your used belonges to `docker` group by running `groups` in `bash`

If `docker` group is not listed, then run:

```sudo usermod -aG docker $(whoami)```

#### Make sure you project files have correct owner

If you have previously run docker containers as root (via sudo), some files can have root user owner.

So in the root of the project:

```sudo chown -R $(whoami):$(id -g -n $(whoami)) *```

### Initial project installation

Make sure you have docker installed and docker daemon running.

E.g. `sudo service docker start`.

Make sure all other docker containers are stopped.
```bash
docker stop $(docker ps -aq)
```

Please also stop DB and Web servers probably running at your computer to be sure
`docker` doesn't try to use ports used by other services.

```bash
git clone https://github.com/minibo29/todolist_symfony.git

cd todolist_symfony

git submodule init && git submodule update

# Copy ".env.example" for laradock project
cp laradock/.env.example laradock/.env

# You can configure laradock service in "./bin/helpers/common.sh" 
# or in "./laradock/.env"
```

Also make ".sh" files runnable. `sudo chmod +x -R bin/` 

For start project docker containers run `bin/up.sh`
This will take long time for the first run. Wait...

Now you should be able to open the localhost web-site.

To stop the project run `bin/down.sh`

### Preparing symfony for work

Install all libraries for project.
`bin/composer.sh install`
(For work with composer use `bin/composer.sh `)

Create a DB `bin/console.sh doctrine:database:create`.

Create tables `bin/console.sh doctrine:migrations:migrate`

Load data in DB `bin/console.sh doctrine:fixtures:load`

