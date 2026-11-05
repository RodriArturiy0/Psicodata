<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AtencionEvento;
use App\Models\ExpedienteCiclo;
use App\Http\Requests\StoreAtencionRequest;
use App\Http\Requests\StoreMovilAulaRequest;

class AtencionController extends Controller
{
    public function create(Request $request)
    {
        $expedienteId = $request->get('expediente_id');

        // Optimización Eager Loading with('alumno')
        $expedientes = ExpedienteCiclo::with('alumno')
            ->where('estado_ciclo', 'ACTIVO')
            ->orderBy('grado_grupo')
            ->get();

        return view('atenciones.create', compact('expedientes', 'expedienteId'));
    }

    public function store(StoreAtencionRequest $request)
    {
        $data = $request->validated();

        AtencionEvento::create($data);

        return redirect()->route('alumnos.show', $data['expediente_ciclo_id'])
            ->with('success', 'Intervención registrada y sumada a las métricas del mes.');
    }

    public function createMovilAula(Request $request)
    {
        $expedienteId = $request->get('expediente_id');

        // Optimización Eager Loading with('alumno')
        $expedientes = ExpedienteCiclo::with('alumno')
            ->where('estado_ciclo', 'ACTIVO')
            ->orderBy('grado_grupo')
            ->get();

        // Categorías rápidas para selección ágil táctil en el aula
        $chipsConducta = [
            'Atención dispersa / Dificultad para enfocar',
            'Disrupción / Desafío a la consigna',
            'Crisis de autorregulación emocional',
            'Interacción social / Conflicto entre pares',
            'Avance pedagógico significativo',
            'Fatiga / Desinterés / Somnolencia',
            'Respuesta favorable a adecuación curricular'
        ];

        return view('atenciones.movil_aula', compact('expedientes', 'expedienteId', 'chipsConducta'));
    }

    public function storeMovilAula(StoreMovilAulaRequest $request)
    {
        $data = $request->validated();
        $data['tipo_atencion'] = 'AULA';
        $data['participantes'] = $request->input('participantes', 'Docente titular y grupo');

        $evento = AtencionEvento::create($data);

        $accionSiguiente = $request->input('accion_siguiente', 'seguir_aula');

        if ($accionSiguiente === 'ver_expediente') {
            return redirect()->route('alumnos.show', $data['expediente_ciclo_id'])
                ->with('success', 'Observación de aula registrada con éxito en el expediente.');
        }

        return redirect()->route('atenciones.movil.aula')
            ->with('success', '¡Observación en aula guardada exitosamente! Puedes registrar la siguiente.');
    }
}
