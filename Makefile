start:
	docker compose up -d

shell:
	docker exec -it innovation_software_php /bin/bash

stop:
	docker compose stop
