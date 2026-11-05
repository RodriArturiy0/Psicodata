<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AtencionController;
use App\Http\Controllers\CicloController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\AuthController;

// Autenticación Pública
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('cambiar-rol/{rol}', [AuthController::class, 'switchRole'])->name('switch.role');

// Rutas Protegidas: Al ingresar al servidor, los usuarios no autenticados van directo al Login
Route::middleware(['auth'])->group(function () {
    // Dashboard y Cierres Mensuales
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Módulos Protegidos: Restringidos a Psicopedagogía y Dirección (Bloqueo estricto a Docentes)
    Route::middleware(['can.expedientes'])->group(function () {
        // Reporte mensual de Dirección
        Route::get('/dashboard/imprimir', [DashboardController::class, 'imprimirReporteMensual'])->name('dashboard.imprimir');

        // Alumnos y Expedientes (CRUD Completo)
        Route::resource('alumnos', AlumnoController::class);
        Route::patch('alumnos/{id}/baja', [AlumnoController::class, 'darDeBaja'])->name('alumnos.baja');
        Route::patch('alumnos/{id}/reactivar', [AlumnoController::class, 'reactivar'])->name('alumnos.reactivar');
        Route::get('alumnos/{id}/imprimir', [AlumnoController::class, 'imprimirExpediente'])->name('alumnos.imprimir');

        // Intervenciones Generales
        Route::get('atenciones/registrar', [AtencionController::class, 'create'])->name('atenciones.create');
        Route::post('atenciones', [AtencionController::class, 'store'])->name('atenciones.store');

        // Captura Móvil de Observaciones en Aula
        Route::get('aula/observacion', [AtencionController::class, 'createMovilAula'])->name('atenciones.movil.aula');
        Route::post('aula/observacion', [AtencionController::class, 'storeMovilAula'])->name('atenciones.movil.store');

        // Transición y Cierre de Ciclo Escolar ("Brincado")
        Route::get('ciclos', [CicloController::class, 'index'])->name('ciclos.index');
        Route::get('ciclos/transicion', [CicloController::class, 'transicionForm'])->name('ciclos.transicion');
        Route::post('ciclos/transicion', [CicloController::class, 'ejecutarTransicion'])->name('ciclos.transicion.store');

        // Rutinas de Respaldo Local (Copias de Seguridad)
        Route::get('respaldos', [BackupController::class, 'index'])->name('respaldos.index');
        Route::post('respaldos', [BackupController::class, 'crear'])->name('respaldos.crear');
        Route::get('respaldos/descargar/{archivo}', [BackupController::class, 'descargar'])->name('respaldos.descargar');
        Route::delete('respaldos/{archivo}', [BackupController::class, 'eliminar'])->name('respaldos.eliminar');
    });
});
