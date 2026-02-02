.PHONY: up down sh migrate

up:
	@docker compose up --build -d

down:
	@docker compose down

sh:
	@docker exec -it products-php-fpm bash

migrate:
	@docker exec -it products-php-fpm php bin/console doctrine:migrations:migrate

db:
	@docker exec -it products-postgres psql -U postgres -d products_db
