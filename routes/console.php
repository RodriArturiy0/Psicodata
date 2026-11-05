<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Rutina programada: Respaldo diario automático de la base de datos a las 23:00 horas
Schedule::command('db:backup')
    ->dailyAt('23:00')
    ->appendOutputTo(storage_path('logs/backups.log'));
