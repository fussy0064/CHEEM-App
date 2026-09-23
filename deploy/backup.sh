#!/bin/bash
# CHEEM App backup script
# Backs up the database and uploaded files, keeps the last 7 days, deletes older ones.
#
# DB credentials are read from ~/.cheem_db.cnf (NOT this file, NOT in git) - see setup notes.

set -euo pipefail

# ---- CONFIG: edit these to match your setup ----
APP_DIR="/var/www/cheem-app"
DB_NAME="cheem_app"
DB_CREDS_FILE="$HOME/.cheem_db.cnf"
BACKUP_DIR="/home/ec2-user/backups"
KEEP_DAYS=7
# -------------------------------------------------

TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mkdir -p "$BACKUP_DIR"

# 1. Backup database (credentials read from protected file, never hardcoded here)
mysqldump --defaults-extra-file="$DB_CREDS_FILE" "$DB_NAME" | gzip > "$BACKUP_DIR/db_${TIMESTAMP}.sql.gz"

# 2. Backup uploaded files (photos, news images)
tar -czf "$BACKUP_DIR/uploads_${TIMESTAMP}.tar.gz" -C "$APP_DIR/web" uploads

# 3. Delete backups older than KEEP_DAYS
find "$BACKUP_DIR" -name "db_*.sql.gz" -mtime +$KEEP_DAYS -delete
find "$BACKUP_DIR" -name "uploads_*.tar.gz" -mtime +$KEEP_DAYS -delete

echo "$(date): Backup completed - db_${TIMESTAMP}.sql.gz, uploads_${TIMESTAMP}.tar.gz" >> "$BACKUP_DIR/backup.log"
