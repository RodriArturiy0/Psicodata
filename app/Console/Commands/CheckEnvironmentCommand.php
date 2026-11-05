<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CheckEnvironmentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-environment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica la estabilidad del entorno de ejecución, detectando sincronizaciones en la nube (como OneDrive) que bloquean la base de datos.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("=== Diagnóstico de Infraestructura y Estabilidad - PsicoData ===");

        $basePath = base_path();
        $this->line("Ruta del proyecto: <fg=cyan>{$basePath}</>");

        // 1. Detección de servicios de nube sincronizada
        $nubeDetectada = null;
        $carpetasNube = [
            'onedrive' => 'Microsoft OneDrive',
            'dropbox' => 'Dropbox',
            'google drive' => 'Google Drive',
            'googledrive' => 'Google Drive',
            'icloud' => 'Apple iCloud',
            'box' => 'Box Sync',
        ];

        foreach ($carpetasNube as $keyword => $nombreNube) {
            if (stripos($basePath, $keyword) !== false) {
                $nubeDetectada = $nombreNube;
                break;
            }
        }

        if ($nubeDetectada) {
            $this->warn("\n[!] ALERTA CRÍTICA DE RENDIMIENTO Y ESTABILIDAD:");
            $this->error("El proyecto se encuentra dentro de una carpeta sincronizada con: {$nubeDetectada}");
            $this->line("<fg=yellow>¿Por qué representa un problema?</>");
            $this->line(" 1. <fg=white>Bloqueo de Base de Datos:</> {$nubeDetectada} intenta sincronizar archivos de MariaDB (.ibd) y SQLite en tiempo real mientras el motor escribe en ellos, provocando errores como 'database is locked' o 'General error: 5'.");
            $this->line(" 2. <fg=white>Colapso de I/O en Dependencias:</> La carpeta 'vendor/' contiene más de 30,000 archivos PHP pequeños. La sincronización continua eleva el uso de CPU a 100% y alenta la ejecución.");
            $this->line(" 3. <fg=white>Corrupción de Estado:</> Las migraciones y transacciones de pase de ciclo pueden corromperse si el archivo de base de datos se bloquea a mitad de una transacción ACID.");

            $this->line("\n<fg=green>Recomendación Institucional:</>");
            $this->line(" Mueva el proyecto a un directorio local directo en la raíz de su unidad de disco sin sincronización en la nube, por ejemplo:");
            $this->line("   - Entorno de desarrollo: <fg=cyan>C:\\Proyectos\\psicodata</>");
            $this->line("   - Entorno de servidor web local: <fg=cyan>C:\\laragon\\www\\psicodata</> o <fg=cyan>C:\\servidor\\psicodata</>");
            $this->line("   - Ejecute el script automatizado: <fg=cyan>powershell -ExecutionPolicy Bypass -File .\\scripts\\migrar_fuera_de_onedrive.ps1</>");
        } else {
            $this->info("\n✓ Ubicación de almacenamiento correcta: El proyecto no se encuentra en directorios de sincronización en la nube.");
        }

        // 2. Comprobación de Base de Datos
        $this->line("\nVerificando conexión con la base de datos...");
        try {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            $driver = DB::connection()->getDriverName();
            $this->info("✓ Conexión establecida con base de datos '{$dbName}' (Driver: {$driver}).");
        } catch (\Throwable $e) {
            $this->warn("! No se pudo conectar a la base de datos configurada: " . $e->getMessage());
        }

        // 3. Comprobación de Permisos de Escritura en Almacenamiento
        $storagePath = storage_path();
        if (File::isWritable($storagePath)) {
            $this->info("✓ Carpeta de almacenamiento local (storage/) con permisos de escritura correctos.");
        } else {
            $this->error("✗ La carpeta storage/ no cuenta con permisos de escritura.");
        }

        return $nubeDetectada ? Command::SUCCESS : Command::SUCCESS;
    }
}

