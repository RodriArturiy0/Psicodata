<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtencionEvento;
use App\Models\ExpedienteCiclo;

class AtencionController extends Controller
{
    public function create(Request $request)
    {
        $expedienteId = $request->get('expediente_id');
        $expedientes = ExpedienteCiclo::with('alumno')->where('estado_ciclo', 'ACTIVO')->get();
        return view('atenciones.create', compact('expedientes', 'expedienteId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expediente_ciclo_id' => 'required|exists:expedientes_ciclo,id',
            'tipo_atencion' => 'required|in:AULA,PADRES,ALUMNO,DOCENTE',
            'fecha_hora' => 'required|date',
            'participantes' => 'nullable|string|max:200',
            'motivo' => 'required|string',
            'observaciones' => 'required|string',
            'acuerdos' => 'nullable|string',
        ]);

        AtencionEvento::create($data);

        return redirect()->route('alumnos.show', $data['expediente_ciclo_id'])
            ->with('success', 'Intervención registrada y sumada a las métricas del mes.');
    }
}