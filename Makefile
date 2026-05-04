builddev:
	docker compose -f docker-compose.dev.yml build

optimize:
	docker compose -f docker-compose.dev.yml exec notification-service php artisan optimize:clear
	docker compose -f docker-compose.dev.yml exec notification-service php artisan config:clear
	docker compose -f docker-compose.dev.yml exec notification-service php artisan config:cache
migrate:
	docker compose -f docker-compose.dev.yml exec notification-service php artisan migrate
rollbackone:
	docker compose -f docker-compose.dev.yml exec notification-service php artisan migrate:rollback --step=1

up:
	docker compose -f docker-compose.dev.yml up -d --remove-orphans
down:
	docker compose -f docker-compose.dev.yml down
restart:
	make down
	make up

shell:
	docker compose -f docker-compose.dev.yml exec -u laravel notification-service /bin/sh
shell-db:
	docker compose -f docker-compose.dev.yml exec postgres psql -U postgres -d postgres
seed:
	docker compose -f docker-compose.dev.yml exec notification-service php artisan db:seed
phpstan:
	docker compose -f docker-compose.dev.yml exec notification-service vendor/bin/phpstan
pint:
	docker compose -f docker-compose.dev.yml exec notification-service vendor/bin/pint
run-tests:
	docker compose -f docker-compose.dev.yml exec notification-service php artisan test
