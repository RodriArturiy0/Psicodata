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

        $casosAltaPrioridad = ExpedienteCiclo::with('alumno')
            ->where('prioridad', 'ALTA')
            ->where('estado_ciclo', 'ACTIVO')
            ->get();

        return view('dashboard', compact('totales', 'totalMes', 'mes', 'anio', 'casosAltaPrioridad'));
    }
}