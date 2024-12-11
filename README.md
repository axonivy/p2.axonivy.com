# ivy-website-p2

## Setup

	docker compose up
	docker compose exec web composer install
	
Website will run under http://localhost:8080
	
## Testing

	docker compose exec web ./vendor/bin/phpunit

## After changing Dockerfile

	docker compose build

## Ressources

* SlimFramework <http://www.slimframework.com>
