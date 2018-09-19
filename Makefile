start:
	docker-compose up -d

stop:
	docker-compose down

ssh:
	docker-compose exec app bash

seed:
	docker-compose exec app_test composer seed:test

phpunit:
	docker-compose exec app_test composer test

coverage:
	docker-compose exec app_test vendor/bin/phpunit --coverage-html coverage

assets:
	docker run -v $(shell pwd):/app -w=/app node:8 npm run prod
