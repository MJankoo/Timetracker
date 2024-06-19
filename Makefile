start:
	docker compose up -d

shell:
	docker exec -it time_tracker_php /bin/bash

stop:
	docker compose stop

units:
	docker exec -it time_tracker_php /bin/bash -c "/var/www/symfony/app/vendor/bin/phpunit --configuration=/var/www/symfony/app/phpunit.xml --testsuite Units"

phpstan:
	docker exec -it time_tracker_php /bin/bash -c "/var/www/symfony/app/vendor/bin/phpstan analyse -c /var/www/symfony/app/phpstan.neon"

phpcs:
	docker exec -it time_tracker_php /bin/bash -c "/var/www/symfony/app/vendor/bin/php-cs-fixer check --rules=@PSR12 --using-cache=no /var/www/symfony/app/src/"

install:
	cp ./app/.env.example ./app/.env
	docker exec -it time_tracker_mysql /usr/bin/mysql -u root --password=root -e "CREATE DATABASE IF NOT EXISTS symfony CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
	docker exec -it time_tracker_php /bin/bash -c "composer install --working-dir=/var/www/symfony/app/"
	docker exec -it time_tracker_php /bin/bash -c "/var/www/symfony/app/bin/console doctrine:migrations:migrate"
