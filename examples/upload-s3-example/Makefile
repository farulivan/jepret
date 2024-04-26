.PHONY: *

build:
	docker build -t upload-s3-example .

run:
	-docker compose -f ./deploy/local/docker-compose.yml down --remove-orphans
	docker compose -f ./deploy/local/docker-compose.yml up --build