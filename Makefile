.PHONY: deploy deploy-test build-front check-deploy backup

ifneq (,$(wildcard .env.deploy))
include .env.deploy
export
endif

REMOTE = $(SERVER_USER)@$(SERVER_HOST)
# échoue en 10 s au lieu d'attendre indéfiniment si le serveur ne répond pas ; une seule
# connexion partagée par toutes les commandes (un seul mot de passe par déploiement)
SSH = ssh -o ConnectTimeout=10 -o ControlMaster=auto -o ControlPath=~/.ssh/cm-%C -o ControlPersist=120
RSYNC = rsync -az --itemize-changes -e "$(SSH)"

# Jamais envoyé : fichiers locaux, et dossiers propres au serveur (uploads de l'admin, vendor,
# cache…). Un dossier exclu n'est ni écrasé ni supprimé sur le serveur, même avec --delete.
# Le « / » initial ancre à la racine du projet : sans lui, « vendor/ » exclurait aussi public/vendor/.
RSYNC_EXCLUDES = \
	--exclude '/.git/' \
	--exclude '/node_modules/' \
	--exclude '/vendor/' \
	--exclude '/var/' \
	--exclude '/tests/' \
	--exclude '/deploy/' \
	--exclude '/.sass-cache/' \
	--exclude '/public/bundles/' \
	--exclude '/public/uploads/' \
	--exclude '/public/assets/' \
	--exclude '/public/css/style.css.map' \
	--exclude '/.env' \
	--exclude '/.env.local' \
	--exclude '/.env.local.php' \
	--exclude '/.env.*.local' \
	--exclude '/.env.deploy' \
	--exclude '/.ftpquota' \
	--exclude '/.phpactor.json' \
	--exclude '/info.md' \
	--exclude '/Makefile' \
	--exclude '/Dockerfile' \
	--exclude '/compose.yaml' \
	--exclude '/Procfile'

# Dossiers de code envoyés en miroir exact : un fichier supprimé en local (ex. config d'un
# bundle retiré) l'est aussi sur le serveur, sinon cache:clear plante
MIRROR_DIRS = config src templates translations migrations

# Le backup passe avant tout : s'il échoue, le déploiement s'arrête.
deploy: check-deploy backup build-front
	$(RSYNC) $(RSYNC_EXCLUDES) ./ $(REMOTE):~/$(SERVER_PATH)
	$(RSYNC) --delete --relative $(RSYNC_EXCLUDES) $(MIRROR_DIRS) $(REMOTE):~/$(SERVER_PATH)/
	$(SSH) $(REMOTE) "set -e; cd $(SERVER_PATH); \
		composer install --no-dev --optimize-autoloader --no-interaction; \
		php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration --env=prod; \
		php bin/console cache:clear --env=prod; \
		php bin/console asset-map:compile --env=prod"
	@echo "✅ Déploiement terminé"

# Dump de la base de prod sur le serveur (~/backups/directicimes, 10 derniers gardés),
# puis copie locale dans var/backups/ (au cas où le serveur lui-même serait perdu)
backup: check-deploy
	$(SSH) $(REMOTE) 'bash -s' -- $(SERVER_PATH) < bin/backup-db.sh
	@mkdir -p var/backups
	$(RSYNC) $(REMOTE):~/backups/directicimes/ var/backups/
	@echo "✅ Base sauvegardée"

# Simulation : liste ce qui serait envoyé (et supprimé, lignes « *deleting »), sans rien toucher
deploy-test: check-deploy
	$(RSYNC) --dry-run $(RSYNC_EXCLUDES) ./ $(REMOTE):~/$(SERVER_PATH)
	$(RSYNC) --dry-run --delete --relative $(RSYNC_EXCLUDES) $(MIRROR_DIRS) $(REMOTE):~/$(SERVER_PATH)/

check-deploy:
	@test -n "$(SERVER_HOST)" -a -n "$(SERVER_USER)" -a -n "$(SERVER_PATH)" || \
		(echo "❌ .env.deploy manquant ou incomplet (SERVER_USER, SERVER_HOST, SERVER_PATH)" && exit 1)
	@git diff --quiet HEAD || echo "⚠️  Modifications non commitées : elles partent aussi en prod"
	@$(SSH) $(REMOTE) true 2>/dev/null || (echo "❌ Connexion SSH impossible à $(SERVER_HOST). Chez o2switch : cPanel > Autorisation SSH, ajoute ton IP actuelle ($$(curl -s --max-time 5 https://api.ipify.org))" && exit 1)

# CSS compilé + éditeur CKEditor 5 copié dans public/vendor (postinstall de npm)
# + paquets JS de l'importmap (Turbo, Stimulus) téléchargés dans assets/vendor, envoyés par rsync
build-front:
	php bin/console importmap:install
	npm install --no-audit --no-fund
	npm run sass

up:
	docker compose up -d

down:
	docker compose down

bash:
	docker compose exec php bash

cc:
	docker compose exec php php bin/console cache:clear

composer i:
	docker compose exec php composer install

migrations:
	docker compose exec php php bin/console doctrine:migrations:migrate

logs:
	docker compose logs -f
