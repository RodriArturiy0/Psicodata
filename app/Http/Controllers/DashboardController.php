<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtencionEvento;
use App\Models\ExpedienteCiclo;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', Carbon::now()->month);
        $anio = (int) $request->get('anio', Carbon::now()->year);

        // Conteo automatizado por tipo de atención para reporte de Dirección
        $conteos = AtencionEvento::whereYear('fecha_hora', $anio)
            ->whereMonth('fecha_hora', $mes)
            ->selectRaw('tipo_atencion, COUNT(*) as total')
            ->groupBy('tipo_atencion')
            ->pluck('total', 'tipo_atencion')
            ->toArray();

        $totales = [
            'AULA'    => $conteos['AULA'] ?? 0,
            'PADRES'  => $conteos['PADRES'] ?? 0,
            'ALUMNO'  => $conteos['ALUMNO'] ?? 0,
            'DOCENTE' => $conteos['DOCENTE'] ?? 0,
        ];
        $totalMes = array_sum($totales);

        $casosAltaPrioridad = ExpedienteCiclo::with(['alumno', 'atenciones' => function($q) use ($mes, $anio) {
                $q->whereYear('fecha_hora', $anio)->whereMonth('fecha_hora', $mes);
            }])
            ->where('prioridad', 'ALTA')
            ->where('estado_ciclo', 'ACTIVO')
            ->get();

        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Cálculo de navegación rápida para mes anterior y posterior
        $fechaActual = Carbon::createFromDate($anio, $mes, 1);
        $prev = $fechaActual->copy()->subMonth();
        $next = $fechaActual->copy()->addMonth();

        $mesPrevio = $prev->month;
        $anioPrevio = $prev->year;
        $mesSiguiente = $next->month;
        $anioSiguiente = $next->year;

        return view('dashboard', compact(
            'totales', 'totalMes', 'mes', 'anio', 'nombresMeses', 
            'casosAltaPrioridad', 'mesPrevio', 'anioPrevio', 'mesSiguiente', 'anioSiguiente'
        ));
    }

    public function imprimirReporteMensual(Request $request)
    {
        $mes = (int) $request->get('mes', Carbon::now()->month);
        $anio = (int) $request->get('anio', Carbon::now()->year);

        $conteos = AtencionEvento::whereYear('fecha_hora', $anio)
            ->whereMonth('fecha_hora', $mes)
            ->selectRaw('tipo_atencion, COUNT(*) as total')
            ->groupBy('tipo_atencion')
            ->pluck('total', 'tipo_atencion')
            ->toArray();

        $totales = [
            'AULA'    => $conteos['AULA'] ?? 0,
            'PADRES'  => $conteos['PADRES'] ?? 0,
            'ALUMNO'  => $conteos['ALUMNO'] ?? 0,
            'DOCENTE' => $conteos['DOCENTE'] ?? 0,
        ];
        $totalMes = array_sum($totales);

        $casosAltaPrioridad = ExpedienteCiclo::with(['alumno', 'atenciones' => function($q) use ($mes, $anio) {
                $q->whereYear('fecha_hora', $anio)->whereMonth('fecha_hora', $mes);
            }])
            ->where('prioridad', 'ALTA')
            ->where('estado_ciclo', 'ACTIVO')
            ->get();

        $intervencionesMes = AtencionEvento::with(['expediente.alumno'])
            ->whereYear('fecha_hora', $anio)
            ->whereMonth('fecha_hora', $mes)
            ->orderBy('fecha_hora', 'desc')
            ->get();

        $nombresMeses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $nombreMes = $nombresMeses[$mes] ?? 'Mes';

        return view('dashboard.imprimir', compact(
            'totales', 'totalMes', 'mes', 'anio', 'nombreMes', 
            'casosAltaPrioridad', 'intervencionesMes'
        ));
    }
}
