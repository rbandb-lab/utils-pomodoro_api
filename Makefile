#!Make
OS=$(shell uname)

ifeq ($(OS),Darwin)
	export UID = 1000
	export GID = 1000
else
	export UID = $(shell id -u)
	export GID = $(shell id -g)
endif

APP_IMAGE_NAME=pomodoro_fpm
DOCKER_COMPOSE_FILE?=docker-compose.yml
DOCKER_COMPOSE_OVERIDE_FILE?=docker-compose.override.yml
DOCKER_COMPOSE=docker-compose --file ${DOCKER_COMPOSE_FILE} -f ${DOCKER_COMPOSE_OVERIDE_FILE}
RUN_IN_CONTAINER := docker exec -i ${APP_IMAGE_NAME}
DOCKER_ENV := cat /etc/*-release | grep -q "https://alpinelinux.org" && echo true

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | cut -d: -f2- | sort -t: -k 2,2 | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: up
up:
	${DOCKER_COMPOSE} up -d

.PHONY: docker-env
docker-env:
	cat /etc/*-release | grep -q "https://alpinelinux.org" && echo true

.PHONY: behat
behat:
	@if $(DOCKER_ENV); then \
		./vendor/bin/behat --colors; \
	else \
		$(RUN_IN_CONTAINER) sh -c "APP_ENV=test ./vendor/bin/behat --colors"; \
	fi

.PHONY: build
build:
	${DOCKER_COMPOSE} pull --include-deps
	${DOCKER_COMPOSE} build --force-rm

.PHONY: down
down:
	${DOCKER_COMPOSE} down --remove-orphans

.PHONY: app
app:
	docker exec -ti ${APP_IMAGE_NAME} sh

.PHONY: stan
stan:
	@if $(DOCKER_ENV); then \
		php -d memory_limit=-1 ./vendor/bin/phpstan analyse -l 5 public src tests translations; \
	else \
		$(RUN_IN_CONTAINER) $(MAKE) $@ ; \
	fi


.PHONY: cs-fixer
cs-fixer:
	@if $(DOCKER_ENV); then \
		./vendor/bin/php-cs-fixer fix src -v --using-cache=no; \
		./vendor/bin/php-cs-fixer fix tests -v --using-cache=no; \
	else \
		$(RUN_IN_CONTAINER) $(MAKE) $@ ; \
	fi


.PHONY: unit
unit:
	@if $(DOCKER_ENV); then \
		./vendor/bin/phpunit; \
	else \
		$(RUN_IN_CONTAINER) $(MAKE) $@ ; \
	fi

.PHONY: unit-watcher
unit-watcher:
	phpunit-watcher watch

.PHONY: purge-db
purge-db:
	$(RUN_IN_CONTAINER) sh -c cat "php bin/console d:d:d --force;"

.PHONY: create-db
create-db:
	$(RUN_IN_CONTAINER) sh -c cat "php bin/console d:d:c;"

.PHONY: migrations
migrations:
	$(RUN_IN_CONTAINER) sh -c cat "php bin/console d:m:m -n;"

.PHONY: db
db:
	- make purge-db
	- make create-db
	- make migrations