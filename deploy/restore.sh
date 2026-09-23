#!/bin/bash
# CHEEM App disaster recovery script.
# Run this on a FRESH server after the app setup steps (php, httpd, mariadb, composer)
# have already been done, but BEFORE running migrations.
#
# Usage: ./restore.sh db_20260101_020000.sql.gz uploads_20260101_020000.tar.gz

set -euo pipefail

DB_BACKUP="$1"
UPLOADS_BACKUP="$2"
APP_DIR="/var/www/cheem-app"
DB_NAME="cheem_app"
DB_CREDS_FILE="$HOME/.cheem_db.cnf"

if [ -z "$DB_BACKUP" ] || [ -z "$UPLOADS_BACKUP" ]; then
    echo "Usage: $0 <db_backup.sql.gz> <uploads_backup.tar.gz>"
    exit 1
fi

echo "1. Restoring database from $DB_BACKUP ..."
gunzip -c "$DB_BACKUP" | mysql --defaults-extra-file="$DB_CREDS_FILE" "$DB_NAME"

echo "2. Restoring uploaded files from $UPLOADS_BACKUP ..."
tar -xzf "$UPLOADS_BACKUP" -C "$APP_DIR/web"

echo "3. Fixing permissions ..."
chown -R apache:apache "$APP_DIR/web/uploads"
chmod -R 775 "$APP_DIR/web/uploads"

echo "Done. Your data has been restored. Now just:"
echo "  - Set config/db.php and web.php as before"
echo "  - Restart httpd"
echo "  - Test the site"
