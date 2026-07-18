.PHONY: deploy

ifneq (,$(wildcard .env.deploy))
include .env.deploy
export
endif

ifneq (,$(wildcard .env.deploy))
include .env.deploy
export
endif

RSYNC_EXCLUDES = \
	--exclude node_modules/ \
	--exclude vendor/ \
	--exclude .git/ \
	--exclude .env \
	--exclude .env.local \
	--exclude var/ \
	--exclude Makefile \
	--exclude Dockerfile \
	--exclude compose.yml \
	--exclude tests/ \
	--exclude public/bundles/

deploy:
	rsync -av --itemize-changes --dry-run ./ $(SERVER_USER)@$(SERVER_HOST):~/$(SERVER_PATH) \
		$(RSYNC_EXCLUDES)

	ssh $(SERVER_USER)@$(SERVER_HOST) "\
		cd $(SERVER_PATH) && \
		composer install --no-dev --optimize-autoloader && \
		php bin/console cache:clear --env=prod \
	"

up:
	docker compose up -d

down:
	docker compose down

bash:
	docker compose exec php bash

cc:
	docker compose exec php php bin/console cache:clear

composer:
	docker compose exec php composer install

logs:
	docker compose logs -f
