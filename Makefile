start:
	docker compose up -d

shell:
	docker exec -it innovation_software_php /bin/bash

stop:
	docker compose stop

units:
	docker exec -it innovation_software_php /bin/bash -c "/var/www/symfony/app/vendor/bin/phpunit --configuration=/var/www/symfony/app/phpunit.xml --testsuite Units"

phpstan:
	docker exec -it innovation_software_php /bin/bash -c "/var/www/symfony/app/vendor/bin/phpstan analyse -c /var/www/symfony/app/phpstan.neon"

phpcs:
	docker exec -it innovation_software_php /bin/bash -c "/var/www/symfony/app/vendor/bin/php-cs-fixer check --rules=@PSR12 --using-cache=no /var/www/symfony/app/src/"
