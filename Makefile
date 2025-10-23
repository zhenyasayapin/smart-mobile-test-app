PHP_CONTAINER = start-mobile-php
ARGS = $(filter-out $@,$(MAKECMDGOALS))

exec:
	docker exec -ti $(PHP_CONTAINER) $(ARGS)

console:
	docker exec -ti $(PHP_CONTAINER) symfony console $(ARGS)

symfony:
	docker exec -ti $(PHP_CONTAINER) symfony $(ARGS)

composer:
	docker exec -ti $(PHP_CONTAINER) composer $(ARGS)

up:
	docker compose up -d

restart:
	docker compose down
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

generate-test-data:
	docker exec -ti $(PHP_CONTAINER) php -d memory_limit=1G bin/console app:generate-test-data