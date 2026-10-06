<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AtencionController;

// ASEGÚRATE DE QUE TENGA ->name('dashboard') AL FINAL
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('alumnos', AlumnoController::class)->only(['index', 'show', 'create', 'store']);
Route::get('atenciones/registrar', [AtencionController::class, 'create'])->name('atenciones.create');
Route::post('atenciones', [AtencionController::class, 'store'])->name('atenciones.store');