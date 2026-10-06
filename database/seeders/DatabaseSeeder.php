<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CicloEscolar;
use App\Models\Alumno;
use App\Models\ExpedienteCiclo;
use App\Models\AtencionEvento;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $psico = User::create([
            'name' => 'Psicopedagogía Paidos',
            'email' => 'psicopedagogia@colegiopaidoscampeche.edu.mx',
            'password' => Hash::make('paidos2026'),
        ]);

        $ciclo = CicloEscolar::create([
            'nombre_ciclo' => '2026-2027',
            'fecha_inicio' => '2026-08-24',
            'fecha_fin' => '2027-07-09',
            'estado' => 'ACTIVO',
        ]);

        $alumnos = [
            [
                'nombre' => 'Mateo Emiliano Canul Morales',
                'grado' => '2-A',
                'docente' => 'Mtra. Claudia Heredia',
                'diag' => 'Trastorno del Espectro Autista (TEA Nivel 1)',
                'fisica' => true,
                'farmaco' => false,
                'adecuacion' => true,
                'det_a' => 'Instrucciones visuales breves y pausas activas.',
                'especialista' => 'Dra. Sofía Méndez (Neuróloga Pediatra)',
                'prioridad' => 'ALTA',
            ],
            [
                'nombre' => 'Valentina Sofía Rosado Tun',
                'grado' => '3-B',
                'docente' => 'Prof. Manuel Cahuich',
                'diag' => 'TDAH con predominio de inatención',
                'fisica' => true,
                'farmaco' => true,
                'adecuacion' => true,
                'det_a' => 'Lugar preferencial al frente del aula.',
                'especialista' => 'Dr. Fernando Pech (Paidopsiquiatra)',
                'prioridad' => 'ALTA',
            ],
            [
                'nombre' => 'Santiago Gael Poot Dzib',
                'grado' => '1-A',
                'docente' => 'Mtra. Andrea Santos',
                'diag' => 'Trastorno del lenguaje expresivo',
                'fisica' => true,
                'farmaco' => false,
                'adecuacion' => false,
                'det_a' => null,
                'especialista' => 'Lic. Terapista María José Lira',
                'prioridad' => 'SEGUIMIENTO',
            ],
        ];

        foreach ($alumnos as $d) {
            $al = Alumno::create(['nombre_completo' => $d['nombre'], 'estatus_general' => 'ACTIVO']);
            $exp = ExpedienteCiclo::create([
                'alumno_id' => $al->id,
                'ciclo_id' => $ciclo->id,
                'grado_grupo' => $d['grado'],
                'docente_titular' => $d['docente'],
                'diagnostico' => $d['diag'],
                'tiene_valoracion_fisica' => $d['fisica'],
                'tratamiento_farmacologico' => $d['farmaco'],
                'requiere_adecuaciones' => $d['adecuacion'],
                'detalle_adecuaciones' => $d['det_a'],
                'especialista_externo' => $d['especialista'],
                'prioridad' => $d['prioridad'],
                'estado_ciclo' => 'ACTIVO'
            ]);

            AtencionEvento::create([
                'expediente_ciclo_id' => $exp->id,
                'user_id' => $psico->id,
                'tipo_atencion' => 'AULA',
                'fecha_hora' => Carbon::create(2026, 9, 10, 10, 0),
                'motivo' => 'Observación de clase inicial',
                'observaciones' => 'Se observa buena integración en el aula con tiempos de atención estables.',
                'acuerdos' => 'Mantener apoyos visuales.'
            ]);

            AtencionEvento::create([
                'expediente_ciclo_id' => $exp->id,
                'user_id' => $psico->id,
                'tipo_atencion' => 'PADRES',
                'fecha_hora' => Carbon::create(2026, 9, 22, 11, 30),
                'motivo' => 'Reporte de entrevista institucional',
                'observaciones' => 'Padres entregan recomendaciones del médico externo.',
                'acuerdos' => 'Cita de seguimiento trimestral en noviembre.'
            ]);

            AtencionEvento::create([
                'expediente_ciclo_id' => $exp->id,
                'user_id' => $psico->id,
                'tipo_atencion' => 'ALUMNO',
                'fecha_hora' => Carbon::create(2026, 10, 2, 9, 30),
                'motivo' => 'Nota de evolución individual',
                'observaciones' => 'El alumno externa agrado con su grupo y maestra.',
                'acuerdos' => 'Monitorear hábitos de trabajo.'
            ]);
        }
    }
}