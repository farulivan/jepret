.PHONY: *
RED=\033[0;31m
GREEN=\033[0;32m
NC=\033[0m # No Color

run: setup run-server dev

setup:
	cp .env.docker.example .env
	cp ./client/.env.example ./client/.env

	@echo "${GREEN}Removing any containers...${NC}"
	docker-compose down

	@echo "${GREEN}Installing Composer dependencies...${NC}"
	docker-compose run --rm composer install

	@echo "${GREEN}Installing NPM dependencies...${NC}"
	docker-compose run --rm npm install

	@echo "${GREEN}Starting services for migration...${NC}"
	docker-compose up -d --build --remove-orphans

	@echo "${GREEN}Waiting for MySQL to be ready...${NC}"
	sleep 10 # Adjust based on your system's speed

	@echo "${GREEN}Generating application key...${NC}"
	docker-compose run --rm artisan key:generate

	@echo "${GREEN}Applying migrations and seeds...${NC}"
	docker-compose run --rm artisan migrate:fresh --seed

run-server:
	@echo "${GREEN}Run the server...${NC}"
	docker-compose run --rm -d artisan serve

migrate:
	@echo "${GREEN}Applying migrations...${NC}"
	docker-compose run --rm artisan migrate

install:
	@echo "${GREEN}Installing NPM dependencies...${NC}"
	docker-compose run --rm npm install

dev:
	@echo "${GREEN}Running client in development mode...${NC}"
	docker-compose run --rm -p 8080:8080 npm run dev

build:
	@echo "${GREEN}Building the client...${NC}"
	docker-compose run --rm npm run build

preview:
	@echo "${GREEN}Running client based on built file...${NC}"
	docker-compose run --rm -p 8080:8080 npm run preview

down:
	@echo "${GREEN}Removing any containers...${NC}"
	docker-compose down
