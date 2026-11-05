<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CicloEscolar;
use App\Models\ExpedienteCiclo;
use App\Http\Requests\TransicionCicloRequest;

class CicloController extends Controller
{
    public function index()
    {
        $ciclos = CicloEscolar::withCount([
            'expedientes as total_expedientes',
            'expedientes as activos_expedientes' => function($q) {
                $q->where('estado_ciclo', 'ACTIVO');
            }
        ])->orderBy('fecha_inicio', 'desc')->get();

        $cicloActivo = CicloEscolar::where('estado', 'ACTIVO')->first();

        return view('ciclos.index', compact('ciclos', 'cicloActivo'));
    }

    public function transicionForm()
    {
        // Obtener ciclo activo
        $cicloActivo = CicloEscolar::where('estado', 'ACTIVO')->first();

        if (!$cicloActivo) {
            return redirect()->route('dashboard')->with('error', 'No existe un ciclo escolar activo actualmente para cerrar.');
        }

        // Cargar todos los expedientes del ciclo activo con eager loading para evitar N+1
        $expedientes = ExpedienteCiclo::with('alumno')
            ->where('ciclo_id', $cicloActivo->id)
            ->where('estado_ciclo', 'ACTIVO')
            ->orderBy('grado_grupo')
            ->get();

        // Sugerir nombre del siguiente ciclo (ej. 2026-2027 -> 2027-2028)
        $siguienteCiclo = '2027-2028';
        if (preg_match('/^(\d{4})-(\d{4})$/', $cicloActivo->nombre_ciclo, $coincidencias)) {
            $y1 = (int)$coincidencias[1] + 1;
            $y2 = (int)$coincidencias[2] + 1;
            $siguienteCiclo = "{$y1}-{$y2}";
        }

        return view('ciclos.transicion', compact('cicloActivo', 'expedientes', 'siguienteCiclo'));
    }

    public function ejecutarTransicion(TransicionCicloRequest $request)
    {
        $cicloActivo = CicloEscolar::where('estado', 'ACTIVO')->firstOrFail();

        $conteos = [
            'promovidos' => 0,
            'repetidores' => 0,
            'secundaria' => 0,
            'archivo_muerto' => 0
        ];

        DB::transaction(function() use ($request, $cicloActivo, &$conteos) {
            // 1. Congelar e inmovilizar el ciclo escolar saliente
            $cicloActivo->update(['estado' => 'CONGELADO']);

            // 2. Abrir el nuevo periodo lectivo
            $nuevoCiclo = CicloEscolar::create([
                'nombre_ciclo' => $request->input('nombre_nuevo_ciclo'),
                'fecha_inicio' => $request->input('fecha_inicio'),
                'fecha_fin' => $request->input('fecha_fin'),
                'estado' => 'ACTIVO'
            ]);

            // 3. Procesar el brinco de cada estudiante
            $estudiantesData = $request->input('estudiantes', []);

            foreach ($estudiantesData as $expedienteId => $config) {
                $expedienteViejo = ExpedienteCiclo::with('alumno')->find($expedienteId);
                if (!$expedienteViejo || $expedienteViejo->ciclo_id !== $cicloActivo->id) {
                    continue;
                }

                $accion = $config['accion'] ?? 'PROMOVER';

                if ($accion === 'PROMOVER') {
                    // Marcar anterior como PROMOVIDO
                    $expedienteViejo->update(['estado_ciclo' => 'PROMOVIDO']);

                    $nuevoGrado = !empty($config['nuevo_grado']) ? $config['nuevo_grado'] : $this->calcularSiguienteGrado($expedienteViejo->grado_grupo);
                    $nuevoDocente = !empty($config['nuevo_docente']) ? $config['nuevo_docente'] : 'Por asignar';

                    // Heredar expediente clínico intacto en el nuevo ciclo
                    ExpedienteCiclo::create([
                        'alumno_id' => $expedienteViejo->alumno_id,
                        'ciclo_id' => $nuevoCiclo->id,
                        'grado_grupo' => $nuevoGrado,
                        'docente_titular' => $nuevoDocente,
                        'canalizado_por' => $expedienteViejo->canalizado_por,
                        'fecha_ingreso_programa' => $expedienteViejo->fecha_ingreso_programa,
                        'diagnostico' => $expedienteViejo->diagnostico,
                        'area_atencion' => $expedienteViejo->area_atencion,
                        'tiene_valoracion_fisica' => $expedienteViejo->tiene_valoracion_fisica,
                        'tratamiento_farmacologico' => $expedienteViejo->tratamiento_farmacologico,
                        'detalle_farmaco' => $expedienteViejo->detalle_farmaco,
                        'requiere_adecuaciones' => $expedienteViejo->requiere_adecuaciones,
                        'detalle_adecuaciones' => $expedienteViejo->detalle_adecuaciones,
                        'compromisos_familia' => $expedienteViejo->compromisos_familia,
                        'especialista_externo' => $expedienteViejo->especialista_externo,
                        'prioridad' => $expedienteViejo->prioridad,
                        'estado_ciclo' => 'ACTIVO'
                    ]);

                    $conteos['promovidos']++;

                } elseif ($accion === 'REPETIR') {
                    // Alumno repite curso: conserva el mismo grado
                    $expedienteViejo->update(['estado_ciclo' => 'REPETIDOR']);

                    $nuevoDocente = !empty($config['nuevo_docente']) ? $config['nuevo_docente'] : 'Por asignar';

                    ExpedienteCiclo::create([
                        'alumno_id' => $expedienteViejo->alumno_id,
                        'ciclo_id' => $nuevoCiclo->id,
                        'grado_grupo' => $expedienteViejo->grado_grupo, // Mismo grado
                        'docente_titular' => $nuevoDocente,
                        'canalizado_por' => $expedienteViejo->canalizado_por,
                        'fecha_ingreso_programa' => $expedienteViejo->fecha_ingreso_programa,
                        'diagnostico' => $expedienteViejo->diagnostico,
                        'area_atencion' => $expedienteViejo->area_atencion,
                        'tiene_valoracion_fisica' => $expedienteViejo->tiene_valoracion_fisica,
                        'tratamiento_farmacologico' => $expedienteViejo->tratamiento_farmacologico,
                        'detalle_farmaco' => $expedienteViejo->detalle_farmaco,
                        'requiere_adecuaciones' => $expedienteViejo->requiere_adecuaciones,
                        'detalle_adecuaciones' => $expedienteViejo->detalle_adecuaciones,
                        'compromisos_familia' => $expedienteViejo->compromisos_familia,
                        'especialista_externo' => $expedienteViejo->especialista_externo,
                        'prioridad' => $expedienteViejo->prioridad,
                        'estado_ciclo' => 'ACTIVO'
                    ]);

                    $conteos['repetidores']++;

                } elseif ($accion === 'SECUNDARIA') {
                    // Pasa a Secundaria: Egreso institucional
                    $expedienteViejo->update(['estado_ciclo' => 'SECUNDARIA']);
                    $expedienteViejo->alumno->update(['estatus_general' => 'EGRESADO_SECUNDARIA']);
                    $conteos['secundaria']++;

                } elseif ($accion === 'ARCHIVO_MUERTO') {
                    // Baja institucional / Retiro
                    $expedienteViejo->update(['estado_ciclo' => 'ARCHIVO_MUERTO']);
                    $expedienteViejo->alumno->update(['estatus_general' => 'ARCHIVO_MUERTO']);
                    $conteos['archivo_muerto']++;
                }
            }
        });

        $resumen = "Transición y Brincado completado con éxito. Ciclo {$cicloActivo->nombre_ciclo} congelado. " .
                   "Nuevo ciclo activo creado. {$conteos['promovidos']} promovidos, {$conteos['repetidores']} repetidores, " .
                   "{$conteos['secundaria']} egresados a secundaria y {$conteos['archivo_muerto']} enviados a archivo muerto.";

        return redirect()->route('alumnos.index')->with('success', $resumen);
    }

    private function calcularSiguienteGrado(string $gradoGrupo): string
    {
        // Ej: "1-A" -> "2-A", "2-B" -> "3-B", "5-A" -> "6-A", "6-A" -> "SECUNDARIA"
        if (preg_match('/^(\d)(.*)$/', trim($gradoGrupo), $matches)) {
            $num = (int)$matches[1];
            $resto = $matches[2];
            if ($num >= 6) {
                return 'SECUNDARIA';
            }
            return ($num + 1) . $resto;
        }
        return $gradoGrupo;
    }
}

