# Directories and files
ENV_DIR=docker/.envs
ENV_FILES=$(shell find $(ENV_DIR) -name "*.env.example")
TARGET_ENV_FILES=$(ENV_FILES:.env.example=.env)
DOCKER_ENV_FILE=docker/.env.example
DOCKER_TARGET_ENV_FILE=docker/.env
DOCKER_DIR=docker
DOCKER_COMPOSE_DEV=$(DOCKER_DIR)/docker-compose.override.dev.yml
DOCKER_COMPOSE=$(DOCKER_DIR)/docker-compose.override.yml
DOCKER_NETWORK=covid-vaccinate-net
MYSQL_CONTAINER=covid-vaccinate-mysql
APP_CONTAINER=covid-vaccinate-app
UI_CONTAINER=covid-vaccinate-ui
MYSQL_DB_NAME=covid_vaccinate
MYSQL_TEST_DB_NAME=covid_vaccinate_test
MYSQL_ROOT_PASSWORD=covid-vaccinate

# Copy .env.example files to .env and docker/.env.example to docker/.env
.PHONY: copy-envs
copy-envs: $(TARGET_ENV_FILES) $(DOCKER_TARGET_ENV_FILE)

$(TARGET_ENV_FILES): %.env: %.env.example
	@echo "Creating $@ from $<..."
	cp $< $@
	@echo "$@ created."

# Copy docker/.env.example to docker/.env
$(DOCKER_TARGET_ENV_FILE): $(DOCKER_ENV_FILE)
	@echo "Creating $(DOCKER_TARGET_ENV_FILE) from $(DOCKER_ENV_FILE)..."
	cp $(DOCKER_ENV_FILE) $(DOCKER_TARGET_ENV_FILE)
	@echo "$(DOCKER_TARGET_ENV_FILE) created."

# Copy docker-compose.override.dev.yml to docker-compose.override.yml
.PHONY: copy-docker-compose
copy-docker-compose: $(DOCKER_COMPOSE)

$(DOCKER_COMPOSE): $(DOCKER_COMPOSE_DEV)
	@echo "Copying $(DOCKER_COMPOSE_DEV) to $(DOCKER_COMPOSE)..."
	cp $(DOCKER_COMPOSE_DEV) $(DOCKER_COMPOSE)
	@echo "$(DOCKER_COMPOSE) created."

# Create Docker network if it doesn't exist
.PHONY: create-network
create-network:
	@if [ -z "$$(docker network ls --filter name=$(DOCKER_NETWORK) --format '{{.Name}}')" ]; then \
		echo "Creating Docker network $(DOCKER_NETWORK)..."; \
		docker network create $(DOCKER_NETWORK); \
	else \
		echo "Docker network $(DOCKER_NETWORK) already exists."; \
	fi

# Build the app service
.PHONY: build-services
build-services:
	@echo "Building the 'app' and 'ui' service..."
	@cd $(DOCKER_DIR) && docker compose build app && docker compose build ui && docker compose up -d app ui
	@docker exec $(APP_CONTAINER) sh -c "composer install"
	@docker exec $(APP_CONTAINER) sh -c "npm install"
	@docker exec $(UI_CONTAINER) sh -c "npm install"
	@cd $(DOCKER_DIR) && docker compose down app ui

# Bring up core services (redis, mysql, adminer, rabbitmq)
.PHONY: up-core-services
up-core-services:
	@echo "Starting core services: redis, mysql, adminer, rabbitmq, mailhog..."
	@cd $(DOCKER_DIR) && docker compose up -d redis mysql adminer rabbitmq mailhog

# Bring down core services (redis, mysql, adminer, rabbitmq)
.PHONY: down-core-services
down-core-services:
	@echo "Stopping app, queue, cron services..."
	@cd $(DOCKER_DIR) && docker compose down redis mysql adminer rabbitmq mailhog

# Bring up remaining services (app, queue, cron)
.PHONY: up-remaining-services
up-remaining-services:
	@echo "Starting remaining services: app, queue, cron, ui..."
	@cd $(DOCKER_DIR) && docker compose up -d app queue cron ui

# Bring up app test service (app-test)
.PHONY: up-app-test-service
up-app-test-service:
	@echo "Starting remaining services: app-test..."
	@cd $(DOCKER_DIR) && docker compose up -d app-test

# Bring down app, queue, cron, ui services
.PHONY: down-app-services
down-app-services:
	@echo "Stopping app, queue, cron services..."
	@cd $(DOCKER_DIR) && docker compose down app queue cron ui

# Create the database if it doesn't exist
.PHONY: create-db
create-db:
	@echo "Creating database '$(MYSQL_DB_NAME)' if it doesn't exist..."
	@docker exec $(MYSQL_CONTAINER) sh -c "mysql -u root -p'$(MYSQL_ROOT_PASSWORD)' -e 'CREATE DATABASE IF NOT EXISTS \`$(MYSQL_DB_NAME)\`;'"
	@docker exec $(MYSQL_CONTAINER) sh -c "mysql -u root -p'$(MYSQL_ROOT_PASSWORD)' -e 'CREATE DATABASE IF NOT EXISTS \`$(MYSQL_TEST_DB_NAME)\`;'"
	@echo "Database '$(MYSQL_DB_NAME)' ensured to exist."

# Run migrations and seeds
.PHONY: migrate-seed
migrate-seed:
	@echo "Running database migrations and seeders in the 'app' container..."
	@docker exec $(APP_CONTAINER) sh -c "php artisan migrate --force"
	@docker exec $(APP_CONTAINER) sh -c "php artisan db:seed --force"
	@echo "Migrations and seeders executed successfully."

# Clean up generated .env files, docker-compose.override.yml, and Docker network
.PHONY: clean
clean:
	@echo "Cleaning up .env files, docker-compose.override.yml, and removing Docker network..."
	@find $(ENV_DIR) -name "*.env" -exec rm {} \;
	rm -f $(DOCKER_COMPOSE)
	rm -f $(DOCKER_TARGET_ENV_FILE)
	docker network rm $(DOCKER_NETWORK) || echo "Network $(DOCKER_NETWORK) does not exist."
	@echo "Clean completed."

# Separated commands

# Run the sequence: copy-envs, copy-docker-compose, create-network, up-core-services
.PHONY: up-core
up-core: copy-envs copy-docker-compose create-network up-core-services

# Create the database
.PHONY: create-db
create-db: create-db

# Run the sequence: build-services, up-remaining-services
.PHONY: up-app
up-app: build-services up-remaining-services

# Run the sequence: up-app-test-service
.PHONY: up-app-test
up-app-test: up-app-test-service

# Stop app, queue, cron services
.PHONY: down-app
down-app: down-app-services

# Stop core services
.PHONY: down-core
down-core: down-core-services

# Help message
.PHONY: help
help:
	@echo "Available commands:"
	@echo "  make copy-envs             Copy .env.example files to create .env files and docker/.env.example to docker/.env"
	@echo "  make copy-docker-compose   Copy docker-compose.override.dev.yml to docker-compose.override.yml"
	@echo "  make create-network        Create Docker network '$(DOCKER_NETWORK)'"
	@echo "  make build-services        Build the 'app' service using docker compose"
	@echo "  make up-core-services      Start core services: redis, mysql, adminer, rabbitmq"
	@echo "  make up-remaining-services Start remaining services: app, queue, cron"
	@echo "  make down-app-services     Stop services: app, queue, cron, ui"
	@echo "  make create-db             Exec into MySQL container and create the database if it doesn't exist"
	@echo "  make up-core               Run the sequence to set up core services redis, mysql, adminer, rabbitmq"
	@echo "  make migrate-seed          Run database migrations and seeders in the 'app' container"
	@echo "  make up-app                Run the sequence to set up the app, queue, cron"
	@echo "  make up-app-test           Run the sequence to set up the app test cases"
	@echo "  make down-app              Stop app, queue, cron services"
	@echo "  make down-core             Stop core services (redis, mysql, adminer, rabbitmq)"
	@echo "  make clean                 Remove all .env files, docker-compose.override.yml, and Docker network"
