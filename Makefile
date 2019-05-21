DOCKER_COMPOSE 	= docker-compose
PHP 			= $(DOCKER_COMPOSE) exec -u www-data app php

##
## Docker stack
## -------
##

build: 												## Build project images
	@$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null
	$(DOCKER_COMPOSE) build --pull

clean: kill 										## Kill project and remove untracked files
	git clean -ffdX --exclude="!.idea/"

docker-sync.yml:
	cp docker/docker-sync.example.yml docker-sync.yml

docker-compose.override.yml:
	@if [ -f "docker-sync.yml" ]; then \
		cp docker/docker-compose.sync.example.yml docker-compose.override.yml; \
	else \
		cp docker/docker-compose.override.example.yml docker-compose.override.yml; \
	fi

install: docker-compose.override.yml build start 	## Initialize and start project

kill: 												## Kill and removes containers and volumes
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down -v --remove-orphans
	@if [ -f "docker-sync.yml" ]; then \
		docker-sync clean; \
	fi

start: docker-compose.override.yml					## Start project containers
	@if [ -f "docker-sync.yml" ]; then \
		docker-sync start; \
	fi
	$(DOCKER_COMPOSE) up -d --force-recreate
	@$(PHP) -r 'echo "Waiting for initial installation ..."; for(;;) { if (false === file_exists("/tmp/DOING_COMPOSER_INSTALL")) { echo " Ready !\n"; break; }}'

stop: 												## Stop project containers
	$(DOCKER_COMPOSE) stop
	@if [ -f "docker-sync.yml" ]; then \
		docker-sync stop; \
	fi

.PHONY: build clean install kill start stop

.DEFAULT_GOAL := help

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
