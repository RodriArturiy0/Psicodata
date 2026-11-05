<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    /**
     * Muestra la lista de respaldos disponibles y el estado de copias de seguridad.
     */
    public function index()
    {
        $backupDir = storage_path('app/backups');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $files = File::files($backupDir);
        // Ordenar del más reciente al más antiguo
        usort($files, fn($a, $b) => $b->getMTime() <=> $a->getMTime());

        $respaldos = [];
        $totalBytes = 0;

        foreach ($files as $file) {
            $bytes = $file->getSize();
            $totalBytes += $bytes;
            $respaldos[] = [
                'nombre' => $file->getFilename(),
                'tamano_kb' => round($bytes / 1024, 2),
                'tamano_mb' => round($bytes / (1024 * 1024), 2),
                'fecha' => Carbon::createFromTimestamp($file->getMTime())->locale('es')->isoFormat('D [de] MMMM [de] YYYY, h:mm A'),
                'fecha_relativa' => Carbon::createFromTimestamp($file->getMTime())->diffForHumans(),
            ];
        }

        $totalMB = round($totalBytes / (1024 * 1024), 2);
        $ultimoRespaldo = count($respaldos) > 0 ? $respaldos[0] : null;

        return view('respaldos.index', compact('respaldos', 'totalMB', 'ultimoRespaldo'));
    }

    /**
     * Dispara la ejecución inmediata de la rutina de respaldo (mysqldump).
     */
    public function crear(Request $request)
    {
        try {
            $exitCode = Artisan::call('db:backup');

            if ($exitCode === 0) {
                return redirect()->route('respaldos.index')
                    ->with('success', '✓ Copia de seguridad generada exitosamente mediante rutina de respaldo local.');
            } else {
                return redirect()->route('respaldos.index')
                    ->with('error', 'Ocurrió una incidencia durante la ejecución del comando de respaldo.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('respaldos.index')
                ->with('error', 'Error al ejecutar el respaldo: ' . $e->getMessage());
        }
    }

    /**
     * Descarga un archivo de respaldo .sql para guardado en USB o unidad externa.
     */
    public function descargar($archivo)
    {
        // Validación de seguridad para evitar Path Traversal (evitar ../ o rutas relativas)
        $archivoLimpio = basename($archivo);
        $filePath = storage_path("app/backups/{$archivoLimpio}");

        if (!File::exists($filePath)) {
            return back()->with('error', 'El archivo de respaldo solicitado no existe en el servidor.');
        }

        return response()->download($filePath, $archivoLimpio, [
            'Content-Type' => 'application/sql',
        ]);
    }

    /**
     * Elimina un archivo de respaldo específico.
     */
    public function eliminar($archivo)
    {
        $archivoLimpio = basename($archivo);
        $filePath = storage_path("app/backups/{$archivoLimpio}");

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('respaldos.index')
                ->with('success', "Respaldo '{$archivoLimpio}' eliminado correctamente del servidor.");
        }

        return back()->with('error', 'No se encontró el archivo a eliminar.');
    }
}

