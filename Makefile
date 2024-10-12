# Directories and files
ENV_DIR=docker/.envs
ENV_FILES=$(shell find $(ENV_DIR) -name "*.env.example")
TARGET_ENV_FILES=$(ENV_FILES:.env.example=.env)
DOCKER_DIR=docker
DOCKER_COMPOSE_DEV=$(DOCKER_DIR)/docker-compose.override.dev.yml
DOCKER_COMPOSE=$(DOCKER_DIR)/docker-compose.override.yml
DOCKER_NETWORK=covid-vaccinate-net

# Default target: copy all env files, docker-compose.override.dev.yml, create the Docker network, and run docker-compose
.PHONY: all
all: copy-envs copy-docker-compose create-network up

# Copy .env.example files to .env
.PHONY: copy-envs
copy-envs: $(TARGET_ENV_FILES)

$(TARGET_ENV_FILES): %.env: %.env.example
	@echo "Creating $@ from $<..."
	cp $< $@
	@echo "$@ created."

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

# Run docker-compose up -d in the docker/ directory
.PHONY: up
up:
	@echo "Starting Docker containers with docker-compose in $(DOCKER_DIR)..."
	@cd $(DOCKER_DIR) && docker compose up -d

# Clean up generated .env files, docker-compose.override.yml, and Docker network
.PHONY: clean
clean:
	@echo "Cleaning up .env files, docker-compose.override.yml, and removing Docker network..."
	@find $(ENV_DIR) -name "*.env" -exec rm {} \;
	rm -f $(DOCKER_COMPOSE)
	docker network rm $(DOCKER_NETWORK) || echo "Network $(DOCKER_NETWORK) does not exist."
	@echo "Clean completed."

# Help message
.PHONY: help
help:
	@echo "Available commands:"
	@echo "  make copy-envs             Copy .env.example files to create .env files"
	@echo "  make copy-docker-compose   Copy docker-compose.override.dev.yml to docker-compose.override.yml"
	@echo "  make create-network        Create Docker network '$(DOCKER_NETWORK)'"
	@echo "  make up                   Start Docker containers with docker-compose"
	@echo "  make clean                 Remove all .env files, docker-compose.override.yml, and Docker network"
