#!/usr/bin/env bash
# Sauvegarde de la base de prod. Exécuté SUR LE SERVEUR par `make backup` (envoyé via ssh, bash -s).
# Usage : backup-db.sh <chemin du projet sur le serveur>
# Affiche en dernière ligne le chemin du dump créé.
set -euo pipefail

BACKUP_DIR=~/backups/directicimes
KEEP=10

cd "$1"

# Identifiants lus comme Symfony les lit (.env, .env.local, .env.local.php…) : une seule source de vérité
db_info=$(php -r '
    require "vendor/autoload.php";
    (new Symfony\Component\Dotenv\Dotenv())->bootEnv(".env");
    $u = parse_url($_SERVER["DATABASE_URL"]);
    echo implode("\n", [
        urldecode($u["user"] ?? ""),
        urldecode($u["pass"] ?? ""),
        $u["host"] ?? "localhost",
        $u["port"] ?? 3306,
        ltrim($u["path"] ?? "", "/"),
    ]), "\n";
')
mapfile -t db <<< "$db_info"
db_user=${db[0]} db_pass=${db[1]} db_host=${db[2]} db_port=${db[3]} db_name=${db[4]}

if [ -z "$db_name" ]; then
    echo "❌ Nom de base introuvable dans DATABASE_URL" >&2
    exit 1
fi

mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"
file="$BACKUP_DIR/db-$(date +%Y%m%d-%H%M%S).sql.gz"

dump=$(command -v mariadb-dump || command -v mysqldump)
# pipefail : si le dump échoue, le script échoue (et le déploiement s'arrête)
if ! MYSQL_PWD="$db_pass" "$dump" --single-transaction --quick --no-tablespaces --routines \
    -h "$db_host" -P "$db_port" -u "$db_user" "$db_name" | gzip > "$file"; then
    rm -f "$file"
    echo "❌ Échec du dump de la base $db_name" >&2
    exit 1
fi

# Garde-fou : un dump valide se termine par « -- Dump completed »
if ! gzip -dc "$file" | tail -n 1 | grep -q 'Dump completed'; then
    rm -f "$file"
    echo "❌ Dump incomplet, supprimé" >&2
    exit 1
fi

# Rotation : on ne garde que les $KEEP derniers dumps sur le serveur
ls -1t "$BACKUP_DIR"/db-*.sql.gz | tail -n +$((KEEP + 1)) | xargs -r rm --

echo "$file"
