<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\ExpedienteCiclo;
use App\Models\CicloEscolar;
use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\UpdateAlumnoRequest;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');
        $prioridad = $request->get('prioridad');
        $grado = $request->get('grado');
        $estado = $request->get('estado', 'ACTIVO');
        $orden = $request->get('orden', 'grado_asc');

        // Optimización Eager Loading (evita problema N+1)
        $query = ExpedienteCiclo::with(['alumno', 'ciclo']);

        // Filtro por Estado (Pestañas)
        if ($estado === 'BAJAS') {
            $query->whereIn('estado_ciclo', ['ARCHIVO_MUERTO', 'SECUNDARIA', 'REPETIDOR']);
        } elseif ($estado === 'TODOS') {
            // Sin filtro de estado
        } else {
            $query->where('estado_ciclo', 'ACTIVO');
        }

        // Filtro por Búsqueda de Nombre o CURP
        if ($buscar) {
            $query->whereHas('alumno', function($q) use ($buscar) {
                $q->where('nombre_completo', 'like', "%{$buscar}%")
                  ->orWhere('curp', 'like', "%{$buscar}%");
            });
        }

        // Filtro por Nivel de Prioridad
        if ($prioridad) {
            $query->where('prioridad', $prioridad);
        }

        // Filtro por Grado y Grupo
        if ($grado) {
            $query->where('grado_grupo', $grado);
        }

        // Ordenamiento Dinámico
        switch ($orden) {
            case 'nombre_asc':
                $query->join('alumnos as a_ord', 'a_ord.id', '=', 'expedientes_ciclo.alumno_id')
                      ->orderBy('a_ord.nombre_completo', 'asc')
                      ->select('expedientes_ciclo.*');
                break;
            case 'nombre_desc':
                $query->join('alumnos as a_ord', 'a_ord.id', '=', 'expedientes_ciclo.alumno_id')
                      ->orderBy('a_ord.nombre_completo', 'desc')
                      ->select('expedientes_ciclo.*');
                break;
            case 'prioridad_alta':
                $query->orderByRaw("CASE WHEN prioridad = 'ALTA' THEN 1 ELSE 2 END")
                      ->orderBy('grado_grupo');
                break;
            case 'recientes':
                $query->orderBy('expedientes_ciclo.updated_at', 'desc');
                break;
            case 'grado_desc':
                $query->orderBy('grado_grupo', 'desc');
                break;
            case 'grado_asc':
            default:
                $query->orderBy('grado_grupo', 'asc');
                break;
        }

        $expedientes = $query->get();

        // Lista de grados disponibles para el selector dinámico
        $gradosDisponibles = ExpedienteCiclo::select('grado_grupo')
            ->distinct()
            ->orderBy('grado_grupo')
            ->pluck('grado_grupo');

        $totalActivos = ExpedienteCiclo::where('estado_ciclo', 'ACTIVO')->count();
        $totalBajas = ExpedienteCiclo::whereIn('estado_ciclo', ['ARCHIVO_MUERTO', 'SECUNDARIA', 'REPETIDOR'])->count();

        return view('alumnos.index', compact(
            'expedientes', 'buscar', 'prioridad', 'grado', 'estado', 'orden',
            'gradosDisponibles', 'totalActivos', 'totalBajas'
        ));
    }

    public function show($id)
    {
        // Eager loading profundo para prevenir N+1 al cargar intervenciones y datos del alumno
        $expediente = ExpedienteCiclo::with(['alumno', 'atenciones', 'ciclo'])->findOrFail($id);
        return view('alumnos.show', compact('expediente'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(StoreAlumnoRequest $request)
    {
        $alumno = Alumno::create([
            'nombre_completo' => $request->input('nombre_completo'),
            'curp' => $request->input('curp'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'tutor_nombre' => $request->input('tutor_nombre'),
            'tutor_telefono' => $request->input('tutor_telefono'),
            'estatus_general' => 'ACTIVO'
        ]);

        $ciclo = CicloEscolar::where('estado', 'ACTIVO')->first() ?? CicloEscolar::create([
            'nombre_ciclo' => '2026-2027',
            'fecha_inicio' => '2026-08-24',
            'fecha_fin' => '2027-07-09',
            'estado' => 'ACTIVO'
        ]);

        $expediente = ExpedienteCiclo::create([
            'alumno_id' => $alumno->id,
            'ciclo_id' => $ciclo->id,
            'grado_grupo' => $request->input('grado_grupo'),
            'docente_titular' => $request->input('docente_titular'),
            'canalizado_por' => $request->input('canalizado_por', 'Docente titular'),
            'fecha_ingreso_programa' => $request->input('fecha_ingreso_programa', now()->format('Y-m-d')),
            'diagnostico' => $request->input('diagnostico'),
            'area_atencion' => $request->input('area_atencion', 'Socioemocional / Aprendizaje'),
            'tiene_valoracion_fisica' => $request->boolean('tiene_valoracion_fisica'),
            'tratamiento_farmacologico' => $request->boolean('tratamiento_farmacologico'),
            'detalle_farmaco' => $request->input('detalle_farmaco'),
            'requiere_adecuaciones' => $request->boolean('requiere_adecuaciones'),
            'detalle_adecuaciones' => $request->input('detalle_adecuaciones'),
            'compromisos_familia' => $request->input('compromisos_familia'),
            'especialista_externo' => $request->input('especialista_externo'),
            'prioridad' => $request->input('prioridad'),
            'estado_ciclo' => 'ACTIVO'
        ]);

        return redirect()->route('alumnos.show', $expediente->id)->with('success', 'Ficha del alumno guardada con éxito.');
    }

    public function edit($id)
    {
        // Eager loading de alumno y ciclo
        $expediente = ExpedienteCiclo::with(['alumno', 'ciclo'])->findOrFail($id);
        return view('alumnos.edit', compact('expediente'));
    }

    public function update(UpdateAlumnoRequest $request, $id)
    {
        $expediente = ExpedienteCiclo::with('alumno')->findOrFail($id);
        $alumno = $expediente->alumno;

        $alumno->update([
            'nombre_completo' => $request->input('nombre_completo'),
            'curp' => $request->input('curp'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'tutor_nombre' => $request->input('tutor_nombre'),
            'tutor_telefono' => $request->input('tutor_telefono'),
            'estatus_general' => in_array($request->input('estado_ciclo'), ['ARCHIVO_MUERTO', 'SECUNDARIA']) 
                ? ($request->input('estado_ciclo') === 'SECUNDARIA' ? 'EGRESADO_SECUNDARIA' : 'ARCHIVO_MUERTO')
                : 'ACTIVO',
        ]);

        $expediente->update([
            'grado_grupo' => $request->input('grado_grupo'),
            'docente_titular' => $request->input('docente_titular'),
            'canalizado_por' => $request->input('canalizado_por'),
            'fecha_ingreso_programa' => $request->input('fecha_ingreso_programa'),
            'diagnostico' => $request->input('diagnostico'),
            'area_atencion' => $request->input('area_atencion'),
            'tiene_valoracion_fisica' => $request->boolean('tiene_valoracion_fisica'),
            'tratamiento_farmacologico' => $request->boolean('tratamiento_farmacologico'),
            'detalle_farmaco' => $request->input('detalle_farmaco'),
            'requiere_adecuaciones' => $request->boolean('requiere_adecuaciones'),
            'detalle_adecuaciones' => $request->input('detalle_adecuaciones'),
            'compromisos_familia' => $request->input('compromisos_familia'),
            'especialista_externo' => $request->input('especialista_externo'),
            'prioridad' => $request->input('prioridad'),
            'estado_ciclo' => $request->input('estado_ciclo'),
        ]);

        return redirect()->route('alumnos.show', $expediente->id)->with('success', 'Expediente y datos institucionales actualizados correctamente.');
    }

    public function darDeBaja(Request $request, $id)
    {
        $expediente = ExpedienteCiclo::with('alumno')->findOrFail($id);

        $request->validate([
            'motivo_baja' => 'required|in:ARCHIVO_MUERTO,SECUNDARIA,REPETIDOR',
            'nota_baja' => 'nullable|string|max:250'
        ]);

        $estadoBaja = $request->input('motivo_baja');
        $expediente->update(['estado_ciclo' => $estadoBaja]);

        $estatusGeneral = ($estadoBaja === 'SECUNDARIA') ? 'EGRESADO_SECUNDARIA' : 'ARCHIVO_MUERTO';
        $expediente->alumno->update(['estatus_general' => $estatusGeneral]);

        return redirect()->route('alumnos.index')
            ->with('success', "El estudiante {$expediente->alumno->nombre_completo} ha sido dado de baja en el ciclo ({$estadoBaja}).");
    }

    public function reactivar($id)
    {
        $expediente = ExpedienteCiclo::with('alumno')->findOrFail($id);
        $expediente->update(['estado_ciclo' => 'ACTIVO']);
        $expediente->alumno->update(['estatus_general' => 'ACTIVO']);

        return redirect()->route('alumnos.show', $expediente->id)
            ->with('success', "El expediente de {$expediente->alumno->nombre_completo} ha sido reactivado exitosamente.");
    }

    public function destroy(Request $request, $id)
    {
        $expediente = ExpedienteCiclo::with('alumno')->findOrFail($id);
        $nombre = $expediente->alumno->nombre_completo;

        if ($request->input('tipo_eliminacion') === 'permanente') {
            $alumno = $expediente->alumno;
            $expediente->delete();
            if ($alumno->expedientes()->count() === 0) {
                $alumno->delete();
            }
            return redirect()->route('alumnos.index')->with('success', "El expediente de {$nombre} fue eliminado permanentemente.");
        }

        $expediente->update(['estado_ciclo' => 'ARCHIVO_MUERTO']);
        $expediente->alumno->update(['estatus_general' => 'ARCHIVO_MUERTO']);

        return redirect()->route('alumnos.index')->with('success', "El expediente de {$nombre} fue movido a Archivo Muerto.");
    }

    public function imprimirExpediente($id)
    {
        // Eager loading de alumno, atenciones y ciclo
        $expediente = ExpedienteCiclo::with(['alumno', 'atenciones', 'ciclo'])->findOrFail($id);
        return view('alumnos.imprimir', compact('expediente'));
    }
}
