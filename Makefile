.PHONY: *

run:
	-docker compose -f ./deploy/local/run/docker-compose.yml down --remove-orphans
	docker compose -f ./deploy/local/run/docker-compose.yml up --build

test:
	-docker compose -f ./deploy/local/integration-testing/docker-compose.yml down --remove-orphans
	docker compose -f ./deploy/local/integration-testing/docker-compose.yml up --build --exit-code-from integration-testing

