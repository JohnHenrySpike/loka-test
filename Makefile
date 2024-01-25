$(shell cp -n .env.example .env)
include .env
.DEFAULT_GOAL := help

DOCKER = docker
COMPOSE = docker compose
DOCKER_RUN = $(COMPOSE) run -u $$(id -u):$$(id -g) --rm
DOCKER_EXEC = $(COMPOSE) exec -u $$(id -u):$$(id -g)

help: ## This help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

console-php: ## Run php bash
	$(DOCKER_EXEC) php-fpm sh
clear-users: ## insert 1million users
	$(DOCKER_EXEC) php-fpm php clear_users_table.php
dbload: ## insert 100k users
	$(DOCKER_EXEC) php-fpm php dbload.php
dbload-1m: ## insert 1million users
	$(DOCKER_EXEC) php-fpm php dbload-1m.php
test: ## test cronjob
	$(DOCKER_EXEC) php-fpm php job.php

up: ## Up Docker-project
	$(COMPOSE) up -d

down: ## Down Docker-project
	$(COMPOSE) down --remove-orphans

stop: ## Stop Docker-project
	$(COMPOSE) stop

build: ## Build Docker-project
	$(COMPOSE) build --no-cache

ps: ## Show list containers
	$(COMPOSE) ps

log: ## Show containers logs
	$(COMPOSE) logs -f -n10

default: help
