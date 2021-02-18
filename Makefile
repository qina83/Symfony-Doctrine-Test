.PHONY: composer-install composer-install-dev npm-install composer-require composer-require-dev up start restart halt test psalm cs-fix test-single cmd-sync-spreadsheet-db

start: composer-install-dev up

restart: halt up

up:
	docker-compose up -d --remove-orphans php web mariadb

recreate:
	docker-compose up -d --force-recreate --build --remove-orphans

logs:
	docker-compose logs --follow web php mariadb

composer-install:
	docker-compose run --rm --no-deps composer composer install --no-dev --ignore-platform-reqs --optimize-autoloader

composer-install-dev:
	docker-compose run --rm --no-deps composer composer install --ignore-platform-reqs

halt:
	docker-compose down -v

composer-require:
	docker-compose run --rm --no-deps composer composer require $(dependency) --ignore-platform-reqs

composer-require-dev:
	docker-compose run --rm --no-deps composer composer require --dev $(dependency) --ignore-platform-reqs

generate-migration:
	docker-compose run --rm --no-deps php ./bin/console doctrine:migrations:generate

migrate:
	docker-compose run --rm --no-deps php ./bin/console doctrine:migrations:migrate --no-interaction

test:
	docker-compose run --rm --no-deps php ./vendor/bin/phpunit --configuration phpunit.xml.dist --testsuite 'Project Test Suite'

test-single:
	docker-compose run --rm --no-deps php ./vendor/bin/phpunit --configuration phpunit.xml.dist $(test)

cs-fix:
	docker-compose run --rm --no-deps cs-fixer php-cs-fixer fix

cs-check:
	docker-compose run --rm --no-deps cs-fixer php-cs-fixer fix --dry-run -v

psalm:
	docker-compose run --rm --no-deps php vendor/bin/psalm

cmd-sync-spreadsheet-db:
	docker-compose run --rm --no-deps php ./bin/console app:sync-spreadsheet-db
