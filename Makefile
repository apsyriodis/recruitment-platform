sh:
	sudo docker-compose exec app sh

start:
	sudo docker-compose up -d

stop:
	sudo docker-compose down

restart:
	sudo docker-compose restart

migrate\:install:
	sudo docker-compose exec app php artisan migrate:install

migrate:
	sudo docker-compose exec app php artisan migrate

migrate\:rollback:
	sudo docker-compose exec app php artisan migrate:rollback

test:
	sudo docker-compose exec app vendor/bin/phpunit tests

composer-install:
	sudo docker-compose exec app composer install

db\:seed:
	sudo docker-compose exec app php artisan db:seed

demo:
	sudo docker-compose exec app php artisan migrate:fresh --seed
