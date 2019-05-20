DOCKER_COMPOSE = docker-compose

##
## Docker stack
## -------
##

build: 					## Build project images
	@$(DOCKER_COMPOSE) pull --parallel --quiet --ignore-pull-failures 2> /dev/null
	$(DOCKER_COMPOSE) build --pull

install: build start 	## Initialize and start project

kill: 					## Kill and removes containers and volumes
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down -v --remove-orphans

start: 					## Start project containers
	$(DOCKER_COMPOSE) up -d --force-recreate

stop: 					## Stop project containers
	$(DOCKER_COMPOSE) stop

.PHONY: build install kill start stop

.DEFAULT_GOAL := help

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
.PHONY: help
