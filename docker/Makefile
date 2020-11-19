web_container=web

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

jumpin:
	docker-compose exec $(web_container) bash

tail-logs:
	docker-compose logs -f $(web_container)
