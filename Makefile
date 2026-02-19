.PHONY: help up down ps logs shell db mysql mysql-root rebuild npm-install sass build dev reset

help:
	@echo "make up          -> docker compose up -d --build"
	@echo "make down        -> docker compose down"
	@echo "make ps          -> docker compose ps"
	@echo "make logs        -> docker compose logs -f web"
	@echo "make shell       -> shell dans le container web"
	@echo "make db          -> shell mysql (dans le container db)"
	@echo "make mysql       -> alias de db"
	@echo "make mysql-root  -> mysql root dans le container db"
	@echo "make rebuild     -> down + up --build"
	@echo "make npm-install -> npm install"
	@echo "make sass        -> npm run dev (watch SCSS)"
	@echo "make build       -> npm run build (SCSS minifié)"
	@echo "make dev         -> up + npm-install + sass"
	@echo "make reset       -> down -v --remove-orphans (reset DB)"

up:
	docker compose up -d --build

down:
	docker compose down

ps:
	docker compose ps

logs:
	docker compose logs -f web --tail=200

shell:
	docker compose exec web bash

mysql:
	@DB_USER=$$(grep -E '^DB_USER=' .env | cut -d= -f2-); \
	DB_PASSWORD=$$(grep -E '^DB_PASSWORD=' .env | cut -d= -f2-); \
	DB_NAME=$$(grep -E '^DB_NAME=' .env | cut -d= -f2-); \
	docker compose exec db mysql -u"$$DB_USER" -p"$$DB_PASSWORD" "$$DB_NAME"

mysql-root:
	@ROOT_PASS=$$(grep -E '^MYSQL_ROOT_PASSWORD=' .env | cut -d= -f2-); \
	docker compose exec db mysql -uroot -p"$$ROOT_PASS"

db: mysql

rebuild:
	docker compose down
	docker compose up -d --build

npm-install:
	npm install

sass:
	npm run dev

build:
	npm run build

dev: up npm-install
	npm run dev

reset:
	docker compose down -v --remove-orphans
