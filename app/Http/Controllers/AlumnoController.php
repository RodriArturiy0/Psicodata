<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\ExpedienteCiclo;
use App\Models\CicloEscolar;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $prioridad = $request->get('prioridad');

        $query = ExpedienteCiclo::with('alumno')->where('estado_ciclo', 'ACTIVO');

        if ($buscar) {
            $query->whereHas('alumno', fn($q) => $q->where('nombre_completo', 'like', "%{$buscar}%"));
        }
        if ($prioridad) {
            $query->where('prioridad', $prioridad);
        }

        $expedientes = $query->orderBy('grado_grupo')->get();
        return view('alumnos.index', compact('expedientes', 'buscar', 'prioridad'));
    }

    public function show($id)
    {
        $expediente = ExpedienteCiclo::with(['alumno', 'atenciones'])->findOrFail($id);
        return view('alumnos.show', compact('expediente'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:150',
            'grado_grupo' => 'required|string|max:10',
            'docente_titular' => 'required|string|max:120',
            'diagnostico' => 'required|string|max:150',
            'prioridad' => 'required|in:ALTA,SEGUIMIENTO',
        ]);

        $alumno = Alumno::create([
            'nombre_completo' => $request->input('nombre_completo'),
            'estatus_general' => 'ACTIVO'
        ]);

        $ciclo = CicloEscolar::where('estado', 'ACTIVO')->first() ?? CicloEscolar::create([
            'nombre_ciclo' => '2026-2027',
            'fecha_inicio' => '2026-08-24',
            'fecha_fin' => '2027-07-09',
            'estado' => 'ACTIVO'
        ]);

        ExpedienteCiclo::create([
            'alumno_id' => $alumno->id,
            'ciclo_id' => $ciclo->id,
            'grado_grupo' => $request->input('grado_grupo'),
            'docente_titular' => $request->input('docente_titular'),
            'diagnostico' => $request->input('diagnostico'),
            'tiene_valoracion_fisica' => $request->boolean('tiene_valoracion_fisica'),
            'tratamiento_farmacologico' => $request->boolean('tratamiento_farmacologico'),
            'detalle_farmaco' => $request->input('detalle_farmaco'),
            'requiere_adecuaciones' => $request->boolean('requiere_adecuaciones'),
            'detalle_adecuaciones' => $request->input('detalle_adecuaciones'),
            'especialista_externo' => $request->input('especialista_externo'),
            'prioridad' => $request->input('prioridad'),
            'estado_ciclo' => 'ACTIVO'
        ]);

        return redirect()->route('alumnos.index')->with('success', 'Ficha del alumno guardada con éxito.');
    }
}