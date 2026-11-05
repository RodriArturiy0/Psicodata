#!/bin/bash
# ==============================================================================
# Script de Respaldo Automatizado de MariaDB / MySQL para PsicoData
# Colegio Paidos Campeche — Servidor Físico Local (Linux / Ubuntu Server)
# ==============================================================================
# Uso en cron: 0 23 * * * /var/www/psicodata/scripts/backup_database.sh >> /var/log/psicodata_backup.log 2>&1
# ==============================================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

cd "$PROJECT_DIR"

echo "=========================================================="
echo "Iniciando respaldo automatizado de PsicoData en Linux..."
echo "Fecha: $(date '+%Y-%m-%d %H:%M:%S')"
echo "Directorio: $PROJECT_DIR"
echo "=========================================================="

php artisan db:backup

echo "✓ Respaldo completado con éxito."
exit 0

