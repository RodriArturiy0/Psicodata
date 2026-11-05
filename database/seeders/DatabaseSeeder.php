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
        // 1. Matriz de Acceso Oficial de Usuarios (Colegio Paidos Campeche)
        // 1. Psicóloga Mercy - Acceso Completo (Admin, edición, captura de intervenciones y cierres de mes)
        $mercy = User::updateOrCreate(
            ['email' => 'mercysegovia@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Mercedes Segovia Mayo',
                'password' => Hash::make('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria / Admin',
                'activo' => true,
            ]
        );

        // 2. Psicóloga Greyci - Acceso Completo (Edición de expedientes, captura y notas de evolución)
        $greyci = User::updateOrCreate(
            ['email' => 'greycia@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Greyci Salas Cahuich',
                'password' => Hash::make('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria',
                'activo' => true,
            ]
        );

        // 3. Directora de Primaria - Solo Lectura (Consulta de expedientes, reportes e informes)
        $damaris = User::updateOrCreate(
            ['email' => 'damarismorales@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Damaris Morales Bastarrachea',
                'password' => Hash::make('paidos2026'),
                'rol' => 'DIRECCION',
                'cargo' => 'Directora de Primaria',
                'activo' => true,
            ]
        );

        // Cuentas de alias / compatibilidad
        User::updateOrCreate(
            ['email' => 'mercy@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Mercedes Segovia Mayo',
                'password' => Hash::make('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria',
                'activo' => true,
            ]
        );
        User::updateOrCreate(
            ['email' => 'greyci@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Greyci Salas Cahuich',
                'password' => Hash::make('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria',
                'activo' => true,
            ]
        );
        User::updateOrCreate(
            ['email' => 'damaris@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Damaris Morales Bastarrachea',
                'password' => Hash::make('paidos2026'),
                'rol' => 'DIRECCION',
                'cargo' => 'Directora de Primaria',
                'activo' => true,
            ]
        );

        // 2. Accesos Técnicos y de Prueba
        $docente = User::updateOrCreate(
            ['email' => 'docente@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Mtra. Docente Titular',
                'password' => Hash::make('paidos2026'),
                'rol' => 'DOCENTE',
                'cargo' => 'Plantilla Docente (Prueba Bloqueo 403)',
                'activo' => true,
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Administración de TI',
                'password' => Hash::make('paidos2026'),
                'rol' => 'ADMIN',
                'cargo' => 'Servidor Local y TI',
                'activo' => true,
            ]
        );

        // Alias para compatibilidad hacia atrás
        $psico = User::updateOrCreate(
            ['email' => 'psicopedagogia@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Psicopedagogía Paidos',
                'password' => Hash::make('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Especialista Titular',
                'activo' => true,
            ]
        );

        $direccion = User::updateOrCreate(
            ['email' => 'direccion@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Dirección General Paidos',
                'password' => Hash::make('paidos2026'),
                'rol' => 'DIRECCION',
                'cargo' => 'Dirección Escolar',
                'activo' => true,
            ]
        );

        $ciclo = CicloEscolar::firstOrCreate(
            ['nombre_ciclo' => '2026-2027'],
            [
                'fecha_inicio' => '2026-08-24',
                'fecha_fin' => '2027-07-09',
                'estado' => 'ACTIVO',
            ]
        );

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
            $al = Alumno::firstOrCreate(
                ['nombre_completo' => $d['nombre']],
                ['estatus_general' => 'ACTIVO']
            );

            $exp = ExpedienteCiclo::firstOrCreate(
                [
                    'alumno_id' => $al->id,
                    'ciclo_id' => $ciclo->id,
                ],
                [
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
                ]
            );

            if ($exp->wasRecentlyCreated) {
                AtencionEvento::create([
                    'expediente_ciclo_id' => $exp->id,
                    'user_id' => $mercy->id,
                    'tipo_atencion' => 'AULA',
                    'fecha_hora' => Carbon::create(2026, 9, 10, 10, 0),
                    'motivo' => 'Observación de clase inicial',
                    'observaciones' => 'Se observa buena integración en el aula con tiempos de atención estables.',
                    'acuerdos' => 'Mantener apoyos visuales.'
                ]);

                AtencionEvento::create([
                    'expediente_ciclo_id' => $exp->id,
                    'user_id' => $greyci->id,
                    'tipo_atencion' => 'PADRES',
                    'fecha_hora' => Carbon::create(2026, 9, 22, 11, 30),
                    'motivo' => 'Reporte de entrevista institucional',
                    'observaciones' => 'Padres entregan recomendaciones del médico externo.',
                    'acuerdos' => 'Cita de seguimiento trimestral en noviembre.'
                ]);

                AtencionEvento::create([
                    'expediente_ciclo_id' => $exp->id,
                    'user_id' => $mercy->id,
                    'tipo_atencion' => 'ALUMNO',
                    'fecha_hora' => Carbon::create(2026, 10, 2, 9, 30),
                    'motivo' => 'Nota de evolución individual',
                    'observaciones' => 'El alumno externa agrado con su grupo y maestra.',
                    'acuerdos' => 'Monitorear hábitos de trabajo.'
                ]);
            }
        }
    }
}