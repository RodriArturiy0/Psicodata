<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Alumno;
use App\Models\CicloEscolar;
use App\Models\ExpedienteCiclo;
use App\Models\AtencionEvento;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    private function crearDatosBase()
    {
        $usuario = User::firstOrCreate(
            ['email' => 'psicopedagogia@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Psicopedagogía Paidos',
                'password' => bcrypt('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Especialista Titular',
                'activo' => true
            ]
        );
        $this->actingAs($usuario);

        $ciclo = CicloEscolar::create([
            'nombre_ciclo' => '2026-2027',
            'fecha_inicio' => '2026-08-24',
            'fecha_fin' => '2027-07-09',
            'estado' => 'ACTIVO'
        ]);

        $alumno = Alumno::create([
            'nombre_completo' => 'Juan Pérez Test',
            'curp' => 'PETJ010101HDFRRN01',
            'tutor_nombre' => 'María Test Madre',
            'tutor_telefono' => '981 123 4567',
            'estatus_general' => 'ACTIVO'
        ]);

        $expediente = ExpedienteCiclo::create([
            'alumno_id' => $alumno->id,
            'ciclo_id' => $ciclo->id,
            'grado_grupo' => '2-A',
            'docente_titular' => 'Mtra. Claudia',
            'canalizado_por' => 'Docente titular',
            'fecha_ingreso_programa' => '2026-09-01',
            'diagnostico' => 'TDAH',
            'area_atencion' => 'Socioemocional y Conductual',
            'tiene_valoracion_fisica' => true,
            'prioridad' => 'ALTA',
            'estado_ciclo' => 'ACTIVO'
        ]);

        return compact('ciclo', 'alumno', 'expediente');
    }

    public function test_el_dashboard_carga_correctamente_con_selector_accesible_y_nomenclatura_unificada(): void
    {
        $this->crearDatosBase();

        $response = $this->get('/?mes=10&anio=2026');
        $response->assertStatus(200);
        $response->assertSee('Cierre Mensual de Actividades');
        $response->assertSee('Mes Anterior');
        $response->assertSee('Mes Siguiente');
        $response->assertSee('Ver Expediente');
    }

    public function test_filtros_y_ordenamiento_dinamico_en_expedientes(): void
    {
        $data = $this->crearDatosBase();

        $responsePrioridad = $this->get(route('alumnos.index', ['prioridad' => 'ALTA']));
        $responsePrioridad->assertStatus(200);
        $responsePrioridad->assertSee('Juan Pérez Test');
        $responsePrioridad->assertSee('Ver Expediente');

        $responseGrado = $this->get(route('alumnos.index', ['grado' => '2-A']));
        $responseGrado->assertStatus(200);
        $responseGrado->assertSee('Juan Pérez Test');

        $responseOrden = $this->get(route('alumnos.index', ['orden' => 'nombre_asc']));
        $responseOrden->assertStatus(200);
        $responseOrden->assertSee('Juan Pérez Test');
    }

    public function test_captura_movil_en_aula_carga_y_guarda(): void
    {
        $data = $this->crearDatosBase();

        $response = $this->get(route('atenciones.movil.aula'));
        $response->assertStatus(200);
        $response->assertSee('Observación en Aula');
        $response->assertSee('Ver Expediente');

        $postResponse = $this->post(route('atenciones.movil.store'), [
            'expediente_ciclo_id' => $data['expediente']->id,
            'duracion_minutos' => 25,
            'fecha_hora' => now()->format('Y-m-d H:i:s'),
            'motivo' => 'Atención dispersa en clase',
            'observaciones' => 'El alumno requiere apoyo visual para iniciar tarea.',
            'acuerdos' => 'Pausas activas de 2 minutos.',
            'accion_siguiente' => 'seguir_aula'
        ]);

        $postResponse->assertRedirect(route('atenciones.movil.aula'));
        $this->assertDatabaseHas('atenciones_eventos', [
            'expediente_ciclo_id' => $data['expediente']->id,
            'tipo_atencion' => 'AULA',
            'duracion_minutos' => 25,
            'motivo' => 'Atención dispersa en clase',
        ]);
    }

    public function test_edicion_y_baja_con_rubros_oficiales(): void
    {
        $data = $this->crearDatosBase();
        $exp = $data['expediente'];

        $editResponse = $this->get(route('alumnos.edit', $exp->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Editar Expediente Psicopedagógico');

        $updateResponse = $this->put(route('alumnos.update', $exp->id), [
            'nombre_completo' => 'Juan Pérez Modificado',
            'curp' => 'PETJ010101HDFRRN01',
            'fecha_nacimiento' => '2018-05-12',
            'tutor_nombre' => 'María Madre Tutora',
            'tutor_telefono' => '981 999 8877',
            'grado_grupo' => '2-B',
            'docente_titular' => 'Prof. Manuel',
            'canalizado_por' => 'Dirección Escolar',
            'diagnostico' => 'TDAH con adecuaciones',
            'area_atencion' => 'Cognitiva / Procesos de Aprendizaje',
            'prioridad' => 'ALTA',
            'estado_ciclo' => 'ACTIVO',
            'tiene_valoracion_fisica' => 1,
            'tratamiento_farmacologico' => 1,
            'detalle_farmaco' => 'Metilfenidato 10mg',
            'compromisos_familia' => 'Acudir a terapia externa semanal'
        ]);

        $updateResponse->assertRedirect(route('alumnos.show', $exp->id));
        $this->assertDatabaseHas('alumnos', [
            'nombre_completo' => 'Juan Pérez Modificado',
            'tutor_nombre' => 'María Madre Tutora'
        ]);

        $this->assertDatabaseHas('expedientes_ciclo', [
            'id' => $exp->id,
            'grado_grupo' => '2-B',
            'canalizado_por' => 'Dirección Escolar',
            'detalle_farmaco' => 'Metilfenidato 10mg'
        ]);

        $bajaResponse = $this->patch(route('alumnos.baja', $exp->id), [
            'motivo_baja' => 'ARCHIVO_MUERTO'
        ]);

        $bajaResponse->assertRedirect(route('alumnos.index'));
        $this->assertDatabaseHas('expedientes_ciclo', [
            'id' => $exp->id,
            'estado_ciclo' => 'ARCHIVO_MUERTO'
        ]);
    }

    public function test_vistas_de_impresion_y_exportacion_pdf(): void
    {
        $data = $this->crearDatosBase();

        $printExpediente = $this->get(route('alumnos.imprimir', $data['expediente']->id));
        $printExpediente->assertStatus(200);
        $printExpediente->assertSee('Expediente Clínico-Pedagógico');

        $printDashboard = $this->get(route('dashboard.imprimir', ['mes' => 10, 'anio' => 2026]));
        $printDashboard->assertStatus(200);
        $printDashboard->assertSee('Informe Mensual de Actividades e Intervenciones');
    }

    public function test_validacion_estricta_con_form_requests(): void
    {
        $user = User::factory()->create(['rol' => 'PSICOPEDAGOGIA']);
        $this->actingAs($user);

        // 1. Probar que campos requeridos vacíos son rechazados
        $responseAlumnoInvalido = $this->post(route('alumnos.store'), [
            'nombre_completo' => '', // Inválido
            'grado_grupo' => '',     // Inválido
            'diagnostico' => '',     // Inválido
            'prioridad' => 'OTRA',   // Inválido
        ]);
        $responseAlumnoInvalido->assertSessionHasErrors(['nombre_completo', 'grado_grupo', 'diagnostico', 'prioridad']);

        // 2. Probar formato de fecha incorrecto en atención
        $responseAtencionInvalida = $this->post(route('atenciones.store'), [
            'expediente_ciclo_id' => 99999, // Inexistente
            'tipo_atencion' => 'DESCONOCIDO',
            'fecha_hora' => 'fecha-no-valida',
            'motivo' => '',
            'observaciones' => ''
        ]);
        $responseAtencionInvalida->assertSessionHasErrors(['expediente_ciclo_id', 'tipo_atencion', 'fecha_hora', 'motivo', 'observaciones']);
    }

    public function test_transicion_de_ciclo_escolar_brincado_y_casos_especiales(): void
    {
        $user = User::factory()->create(['rol' => 'PSICOPEDAGOGIA']);
        $this->actingAs($user);

        $ciclo = CicloEscolar::create([
            'nombre_ciclo' => '2026-2027',
            'fecha_inicio' => '2026-08-24',
            'fecha_fin' => '2027-07-09',
            'estado' => 'ACTIVO'
        ]);

        // Alumno 1: Promovido (2-A -> 3-A)
        $al1 = Alumno::create(['nombre_completo' => 'Alumno Promovido', 'estatus_general' => 'ACTIVO']);
        $exp1 = ExpedienteCiclo::create([
            'alumno_id' => $al1->id, 'ciclo_id' => $ciclo->id,
            'grado_grupo' => '2-A', 'docente_titular' => 'Mtro. A',
            'diagnostico' => 'TEA Nivel 1', 'prioridad' => 'ALTA', 'estado_ciclo' => 'ACTIVO'
        ]);
        AtencionEvento::create([
            'expediente_ciclo_id' => $exp1->id, 'tipo_atencion' => 'AULA',
            'fecha_hora' => '2026-09-15 10:00:00', 'motivo' => 'Nota 2026', 'observaciones' => 'Nota que no debe borrarse'
        ]);

        // Alumno 2: Repetidor (2-B -> 2-B)
        $al2 = Alumno::create(['nombre_completo' => 'Alumno Repetidor', 'estatus_general' => 'ACTIVO']);
        $exp2 = ExpedienteCiclo::create([
            'alumno_id' => $al2->id, 'ciclo_id' => $ciclo->id,
            'grado_grupo' => '2-B', 'docente_titular' => 'Mtra. B',
            'diagnostico' => 'TDAH', 'prioridad' => 'SEGUIMIENTO', 'estado_ciclo' => 'ACTIVO'
        ]);

        // Alumno 3: 6° a Secundaria
        $al3 = Alumno::create(['nombre_completo' => 'Alumno Sexto Egresado', 'estatus_general' => 'ACTIVO']);
        $exp3 = ExpedienteCiclo::create([
            'alumno_id' => $al3->id, 'ciclo_id' => $ciclo->id,
            'grado_grupo' => '6-A', 'docente_titular' => 'Prof. C',
            'diagnostico' => 'Trastorno Lenguaje', 'prioridad' => 'SEGUIMIENTO', 'estado_ciclo' => 'ACTIVO'
        ]);

        // Alumno 4: Baja a Archivo Muerto
        $al4 = Alumno::create(['nombre_completo' => 'Alumno Baja Retiro', 'estatus_general' => 'ACTIVO']);
        $exp4 = ExpedienteCiclo::create([
            'alumno_id' => $al4->id, 'ciclo_id' => $ciclo->id,
            'grado_grupo' => '4-A', 'docente_titular' => 'Mtra. D',
            'diagnostico' => 'Dislexia', 'prioridad' => 'SEGUIMIENTO', 'estado_ciclo' => 'ACTIVO'
        ]);

        // Cargar vista de transición
        $vistaTransicion = $this->get(route('ciclos.transicion'));
        $vistaTransicion->assertStatus(200);
        $vistaTransicion->assertSee('Transición y Pase de Ciclo Escolar');

        // Ejecutar brinco
        $postTransicion = $this->post(route('ciclos.transicion.store'), [
            'nombre_nuevo_ciclo' => '2027-2028',
            'fecha_inicio' => '2027-08-23',
            'fecha_fin' => '2028-07-09',
            'estudiantes' => [
                $exp1->id => [
                    'accion' => 'PROMOVER',
                    'nuevo_grado' => '3-A',
                    'nuevo_docente' => 'Mtra. Nuevo Grado'
                ],
                $exp2->id => [
                    'accion' => 'REPETIR',
                    'nuevo_grado' => '2-B',
                    'nuevo_docente' => 'Mtra. Docente Repetidor'
                ],
                $exp3->id => [
                    'accion' => 'SECUNDARIA',
                ],
                $exp4->id => [
                    'accion' => 'ARCHIVO_MUERTO',
                ],
            ]
        ]);

        $postTransicion->assertRedirect(route('alumnos.index'));

        // 1. Verificar que el ciclo anterior quedó CONGELADO
        $this->assertDatabaseHas('ciclos_escolares', [
            'id' => $ciclo->id,
            'estado' => 'CONGELADO'
        ]);

        // 2. Verificar que el nuevo ciclo 2027-2028 quedó ACTIVO
        $this->assertDatabaseHas('ciclos_escolares', [
            'nombre_ciclo' => '2027-2028',
            'estado' => 'ACTIVO'
        ]);
        $nuevoCiclo = CicloEscolar::where('nombre_ciclo', '2027-2028')->first();

        // 3. Verificar que el alumno 1 tiene nuevo expediente en 3-A en el nuevo ciclo
        $this->assertDatabaseHas('expedientes_ciclo', [
            'alumno_id' => $al1->id,
            'ciclo_id' => $nuevoCiclo->id,
            'grado_grupo' => '3-A',
            'diagnostico' => 'TEA Nivel 1', // Antecedente heredado
            'estado_ciclo' => 'ACTIVO'
        ]);
        // Y su nota del año 2026 sigue intacta en el expediente viejo
        $this->assertDatabaseHas('atenciones_eventos', [
            'expediente_ciclo_id' => $exp1->id,
            'motivo' => 'Nota 2026'
        ]);
        $this->assertDatabaseHas('expedientes_ciclo', [
            'id' => $exp1->id,
            'estado_ciclo' => 'PROMOVIDO'
        ]);

        // 4. Verificar repetidor en 2-B
        $this->assertDatabaseHas('expedientes_ciclo', [
            'alumno_id' => $al2->id,
            'ciclo_id' => $nuevoCiclo->id,
            'grado_grupo' => '2-B',
            'estado_ciclo' => 'ACTIVO'
        ]);
        $this->assertDatabaseHas('expedientes_ciclo', [
            'id' => $exp2->id,
            'estado_ciclo' => 'REPETIDOR'
        ]);

        // 5. Verificar secundaria
        $this->assertDatabaseHas('expedientes_ciclo', [
            'id' => $exp3->id,
            'estado_ciclo' => 'SECUNDARIA'
        ]);
        $this->assertDatabaseHas('alumnos', [
            'id' => $al3->id,
            'estatus_general' => 'EGRESADO_SECUNDARIA'
        ]);

        // 6. Verificar archivo muerto
        $this->assertDatabaseHas('expedientes_ciclo', [
            'id' => $exp4->id,
            'estado_ciclo' => 'ARCHIVO_MUERTO'
        ]);
        $this->assertDatabaseHas('alumnos', [
            'id' => $al4->id,
            'estatus_general' => 'ARCHIVO_MUERTO'
        ]);
    }

    public function test_control_estricto_de_roles_bloquea_consulta_a_plantilla_docente(): void
    {
        $data = $this->crearDatosBase();

        $docente = User::create([
            'name' => 'Prof. Juan Docente',
            'email' => 'juan.docente@colegiopaidoscampeche.edu.mx',
            'password' => bcrypt('password'),
            'rol' => 'DOCENTE',
            'cargo' => 'Docente Titular',
            'activo' => true
        ]);

        $psicopedagoga = User::create([
            'name' => 'Lic. Psicología Titular',
            'email' => 'lic.psicologia@colegiopaidoscampeche.edu.mx',
            'password' => bcrypt('password'),
            'rol' => 'PSICOPEDAGOGIA',
            'cargo' => 'Especialista Titular',
            'activo' => true
        ]);

        // 1. Un usuario con rol DOCENTE debe recibir 403 Forbidden al intentar ver la lista de expedientes
        $responseDocenteIndex = $this->actingAs($docente)->get(route('alumnos.index'));
        $responseDocenteIndex->assertStatus(403);
        $responseDocenteIndex->assertSee('Acceso Restringido por Confidencialidad Clínica');
        $responseDocenteIndex->assertSee('plantilla docente tiene estrictamente restringido el acceso');

        // 2. Un usuario con rol DOCENTE debe recibir 403 al intentar consultar el detalle de un alumno
        $responseDocenteShow = $this->actingAs($docente)->get(route('alumnos.show', $data['expediente']->id));
        $responseDocenteShow->assertStatus(403);

        // 3. Un usuario con rol DOCENTE debe recibir 403 al intentar acceder a la captura de aula o intervenciones
        $responseDocenteAula = $this->actingAs($docente)->get(route('atenciones.movil.aula'));
        $responseDocenteAula->assertStatus(403);

        $responseDocenteCiclos = $this->actingAs($docente)->get(route('ciclos.index'));
        $responseDocenteCiclos->assertStatus(403);

        // 4. Psicopedagogía tiene acceso pleno (200 OK)
        $responsePsico = $this->actingAs($psicopedagoga)->get(route('alumnos.index'));
        $responsePsico->assertStatus(200);
        $responsePsico->assertSee('Juan Pérez Test');
    }

    public function test_rutina_de_respaldo_local_genera_archivo_sql_y_lo_lista(): void
    {
        $data = $this->crearDatosBase();

        // Ejecutar comando Artisan
        $exitCode = Artisan::call('db:backup', ['--filename' => 'test_backup_suite']);
        $this->assertEquals(0, $exitCode);

        $backupFile = storage_path('app/backups/test_backup_suite.sql');
        $this->assertTrue(File::exists($backupFile));

        // Verificar que la vista de respaldos carga correctamente y muestra el archivo
        $response = $this->get(route('respaldos.index'));
        $response->assertStatus(200);
        $response->assertSee('test_backup_suite.sql');
        $response->assertSee('Descargar SQL');

        // Limpieza de prueba
        if (File::exists($backupFile)) {
            File::delete($backupFile);
        }
    }

    public function test_comando_diagnostico_entorno_detecta_estabilidad(): void
    {
        $exitCode = Artisan::call('app:check-environment');
        $this->assertEquals(0, $exitCode);
        $output = Artisan::output();
        $this->assertStringContainsString('Diagnóstico de Infraestructura y Estabilidad', $output);
    }

    public function test_invitado_es_redirigido_a_login_al_entrar_al_servidor(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_accesos_institucionales_mercy_greyci_damaris_funcionan_correctamente(): void
    {
        $this->crearDatosBase();

        // 1. Acceso de Psicóloga Mercy - Mercedes Segovia Mayo (Acceso Completo con contraseña)
        $userMercy = User::updateOrCreate(
            ['email' => 'mercysegovia@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Mercedes Segovia Mayo',
                'password' => bcrypt('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria / Admin',
                'activo' => true,
            ]
        );
        $postMercy = $this->post(route('login.post'), [
            'email' => 'mercysegovia@colegiopaidoscampeche.edu.mx',
            'password' => 'paidos2026',
        ]);
        $postMercy->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($userMercy);
        $this->assertTrue($userMercy->puedeVerExpedientes());
        $this->assertTrue($userMercy->puedeEditarExpedientes());

        // Verificar que Mercy puede ver los expedientes y modo aula
        $responseMercyExp = $this->get(route('alumnos.index'));
        $responseMercyExp->assertStatus(200);
        $responseMercyExp->assertSee('+ Nueva Ficha');
        $responseMercyAula = $this->get(route('atenciones.movil.aula'));
        $responseMercyAula->assertStatus(200);

        // 2. Acceso de Psicóloga Greyci - Greyci Salas Cahuich (Acceso Completo con contraseña)
        $userGreyci = User::updateOrCreate(
            ['email' => 'greycia@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Greyci Salas Cahuich',
                'password' => bcrypt('paidos2026'),
                'rol' => 'PSICOPEDAGOGIA',
                'cargo' => 'Psicóloga de Primaria',
                'activo' => true,
            ]
        );
        $postGreyci = $this->post(route('login.post'), [
            'email' => 'greycia@colegiopaidoscampeche.edu.mx',
            'password' => 'paidos2026',
        ]);
        $postGreyci->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($userGreyci);
        $this->assertTrue($userGreyci->puedeVerExpedientes());
        $this->assertTrue($userGreyci->puedeEditarExpedientes());

        // 3. Acceso de Directora de Primaria - Damaris Morales Bastarrachea (Solo Lectura con contraseña)
        $userDamaris = User::updateOrCreate(
            ['email' => 'damarismorales@colegiopaidoscampeche.edu.mx'],
            [
                'name' => 'Damaris Morales Bastarrachea',
                'password' => bcrypt('paidos2026'),
                'rol' => 'DIRECCION',
                'cargo' => 'Directora de Primaria',
                'activo' => true,
            ]
        );
        $postDamaris = $this->post(route('login.post'), [
            'email' => 'damarismorales@colegiopaidoscampeche.edu.mx',
            'password' => 'paidos2026',
        ]);
        $postDamaris->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($userDamaris);
        $this->assertTrue($userDamaris->puedeVerExpedientes());
        $this->assertFalse($userDamaris->puedeEditarExpedientes()); // Solo Lectura
        $this->assertTrue($userDamaris->esDireccion());

        // Damaris puede consultar expedientes pero en modo solo lectura (sin botón de nueva ficha)
        $responseDamarisExp = $this->get(route('alumnos.index'));
        $responseDamarisExp->assertStatus(200);
        $responseDamarisExp->assertDontSee('+ Nueva Ficha');

        // Damaris puede consultar el informe de impresión del dashboard con su nombre oficial
        $responseDamarisInforme = $this->get(route('dashboard.imprimir'));
        $responseDamarisInforme->assertStatus(200);
        $responseDamarisInforme->assertSee('Damaris Morales Bastarrachea');

        // 4. La pantalla de login es limpia y no tiene credenciales ni botones de auto-login expuestos
        $this->post(route('logout'));
        $responseLogin = $this->get(route('login'));
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee('Acceso Institucional Seguro');
        $responseLogin->assertSee('Correo Institucional');
        $responseLogin->assertSee('Contraseña');
        $responseLogin->assertDontSee('paidos2026'); // La contraseña NO debe venir prellenada
        $responseLogin->assertDontSee('switch.role'); // NO debe haber botones de auto-login
    }
}
