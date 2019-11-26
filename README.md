# ivy-website-p2

## Setup
	docker-compose up
	docker-compose exec web composer install
	
Website will run under http://localhost:80
	
## Testing
 	docker-compose exec web ./vendor/bin/phpunit
	
## After changing DockerFile
	docker-compose build

## Ressources
* SlimFramework <http://www.slimframework.com>
