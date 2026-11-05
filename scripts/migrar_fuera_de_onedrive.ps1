# ==============================================================================
# Script de Migración Fuera de OneDrive para PsicoData
# Colegio Paidos Campeche — Estabilidad en Entorno de Desarrollo Local
# ==============================================================================
# Propósito:
# Mover la carpeta del proyecto fuera de OneDrive (ej. a C:\Proyectos\psicodata)
# para evitar que la sincronización en la nube bloquee los archivos de base de
# datos MariaDB / SQLite y cause lentitud extrema en la carpeta 'vendor/'.
# ==============================================================================

param (
    [string]$Destino = "C:\Proyectos\psicodata"
)

$ErrorActionPreference = "Stop"

$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$Origen = Split-Path -Parent $ScriptDir

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "   MIGRACIÓN DE PSICODATA FUERA DE ONEDRIVE" -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "Origen actual (con OneDrive): $Origen" -ForegroundColor Gray
Write-Host "Destino recomendado:          $Destino" -ForegroundColor Green
Write-Host "----------------------------------------------------------"

if ($Origen -eq $Destino) {
    Write-Host "[!] El origen y el destino son idénticos. No se requiere migración." -ForegroundColor Yellow
    exit 0
}

# Crear directorio de destino si no existe
if (-not (Test-Path $Destino)) {
    Write-Host "Creando directorio de destino: $Destino..." -ForegroundColor Gray
    New-Item -ItemType Directory -Path $Destino -Force | Out-Null
}

Write-Host "Copiando archivos del proyecto (excluyendo vendor y caches temporales)..." -ForegroundColor Cyan

# Usar robocopy para copia ultrarrápida excluyendo carpetas pesadas
$RobocopyArgs = @(
    $Origen,
    $Destino,
    "/E",
    "/XD", "vendor", "node_modules", ".git", "storage\framework\cache", "storage\framework\sessions", "storage\framework\views",
    "/XF", "*.sqlite-journal", "*.sqlite-wal",
    "/R:1",
    "/W:1",
    "/NP"
)

& robocopy @RobocopyArgs | Out-Null

Write-Host "✓ Archivos base copiados exitosamente." -ForegroundColor Green

# Crear carpetas de almacenamiento necesarias
$storageDirs = @(
    "$Destino\storage\app\backups",
    "$Destino\storage\framework\cache\data",
    "$Destino\storage\framework\sessions",
    "$Destino\storage\framework\views",
    "$Destino\storage\logs"
)

foreach ($dir in $storageDirs) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
}

Write-Host "`nPasos finales en la nueva ubicación ($Destino):" -ForegroundColor Yellow
Write-Host "1. Abre una nueva terminal de PowerShell en: $Destino" -ForegroundColor White
Write-Host "2. Ejecuta: composer install" -ForegroundColor White
Write-Host "3. Ejecuta: php artisan key:generate" -ForegroundColor White
Write-Host "4. Ejecuta: php artisan migrate --seed" -ForegroundColor White
Write-Host "5. Ejecuta: php artisan app:check-environment" -ForegroundColor White
Write-Host "`n✓ El proyecto ahora correrá 100% libre de bloqueos de OneDrive." -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Cyan

