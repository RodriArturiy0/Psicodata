# ==============================================================================
# Script de Respaldo Automatizado de MariaDB / MySQL para PsicoData
# Colegio Paidos Campeche — Servidor Físico Local (Windows Server)
# ==============================================================================
# Uso: powershell -ExecutionPolicy Bypass -File .\backup_database.ps1
# Programable en el Programador de Tareas de Windows (schtasks)
# ==============================================================================

$ErrorActionPreference = "Stop"

# Obtener ruta base del proyecto
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$ProjectDir = Split-Path -Parent $ScriptDir
Set-Location $ProjectDir

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "Iniciando respaldo automatizado de PsicoData..." -ForegroundColor Green
Write-Host "Ubicación del proyecto: $ProjectDir" -ForegroundColor Gray
Write-Host "Fecha: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" -ForegroundColor Gray
Write-Host "==========================================================" -ForegroundColor Cyan

# Ejecutar el comando Artisan db:backup
try {
    php artisan db:backup
    Write-Host "`n✓ Respaldo generado satisfactoriamente." -ForegroundColor Green
} catch {
    Write-Host "`n✗ Error al ejecutar el respaldo Artisan: $_" -ForegroundColor Red
    exit 1
}

exit 0

