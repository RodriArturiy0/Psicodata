# 04. Especificación de Módulos y Operaciones CRUD
**Proyecto:** PsicoData — Colegio Paidos Campeche  

---

## 1. Módulo 1: Gestión Integral de Alumnos y Expedientes (CRUD Completo)

Este módulo administra la matriculación psicopedagógica, actualización de diagnósticos y datos personales, control de bajas/archivo muerto y la consulta de antecedentes.

### 1.1 Matriz de Rutas y Endpoints
```php
// routes/web.php
Route::resource('alumnos', AlumnoController::class);
Route::patch('alumnos/{id}/baja', [AlumnoController::class, 'darDeBaja'])->name('alumnos.baja');
Route::patch('alumnos/{id}/reactivar', [AlumnoController::class, 'reactivar'])->name('alumnos.reactivar');
Route::get('alumnos/{id}/imprimir', [AlumnoController::class, 'imprimirExpediente'])->name('alumnos.imprimir');
```

| Método HTTP | URI | Nombre de Ruta | Acción en Controlador | Vista Asociada |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/alumnos` | `alumnos.index` | `AlumnoController@index` | `alumnos/index.blade.php` |
| `GET` | `/alumnos/create` | `alumnos.create` | `AlumnoController@create` | `alumnos/create.blade.php` |
| `POST` | `/alumnos` | `alumnos.store` | `AlumnoController@store` | Redirección a `alumnos.show` |
| `GET` | `/alumnos/{id}` | `alumnos.show` | `AlumnoController@show` | `alumnos/show.blade.php` |
| `GET` | `/alumnos/{id}/edit` | `alumnos.edit` | `AlumnoController@edit` | `alumnos/edit.blade.php` |
| `PUT/PATCH` | `/alumnos/{id}` | `alumnos.update` | `AlumnoController@update` | Redirección a `alumnos.show` |
| `PATCH` | `/alumnos/{id}/baja` | `alumnos.baja` | `AlumnoController@darDeBaja` | Redirección a `alumnos.index` |
| `PATCH` | `/alumnos/{id}/reactivar` | `alumnos.reactivar` | `AlumnoController@reactivar` | Redirección a `alumnos.show` |
| `DELETE` | `/alumnos/{id}` | `alumnos.destroy` | `AlumnoController@destroy` | Redirección a `alumnos.index` |
| `GET` | `/alumnos/{id}/imprimir`| `alumnos.imprimir` | `AlumnoController@imprimirExpediente` | `alumnos/imprimir.blade.php` |

---

### 1.2 Detalle de Operaciones CRUD

#### Operación `index(Request $request)` — Listado, Filtros y Pestañas
- **Propósito:** Mostrar los expedientes clasificados por estado (Activos, Bajas/Archivo Muerto, Todos).
- **Filtros disponibles:**
  - Pestaña de estado (`estado`): `ACTIVO` (por defecto), `BAJAS` (Archivo Muerto / Egresados), o `TODOS`.
  - Búsqueda por nombre (`buscar`): Búsqueda parcial case-insensitive.
  - Filtro por prioridad (`prioridad`): `ALTA` o `SEGUIMIENTO`.
- **Contadores de cabecera:** Muestra en vivo el total de expedientes activos vs en archivo muerto.

#### Operación `create()` y `store(Request $request)` — Alta Atómica
- Valida datos de identidad y condición clínica.
- Genera el `Alumno`, garantiza la existencia del `CicloEscolar` y crea el `ExpedienteCiclo` vinculado.

#### Operación `edit($id)` y `update(Request $request, $id)` — Edición Clínica y Personal
- Permite actualizar:
  - Nombre completo y CURP del alumno.
  - Grado/grupo y docente titular asignado.
  - Diagnóstico psicopedagógico y especialista externo tratante.
  - Estatus de valoración física, tratamiento farmacológico (nombre de fármaco y dosis) y adecuaciones curriculares (detalle pedagógico).
  - Prioridad de seguimiento (`ALTA` o `SEGUIMIENTO`) y estado en el ciclo escolar (`ACTIVO`, `PROMOVIDO`, `REPETIDOR`, `SECUNDARIA`, `ARCHIVO_MUERTO`).

#### Operación `darDeBaja(Request $request, $id)` y `reactivar($id)` — Gestión de Bajas
- Cambia el `estado_ciclo` y `estatus_general` a `ARCHIVO_MUERTO` o `EGRESADO_SECUNDARIA`.
- **Beneficio clínico:** Retira al estudiante de las listas y métricas activas sin eliminar físicamente su historial de intervenciones y antecedentes médico-escolares.
- La función `reactivar` permite regresar al alumno al estado `ACTIVO` en caso de reingreso.

---

## 2. Módulo 2: Captura Móvil de Observaciones en Aula (Modo Aula 📱)

Desarrollado específicamente para que la psicopedagoga capture incidencias y notas de observación directamente desde su teléfono o tablet mientras supervisa el salón de clases.

### 2.1 Rutas y Endpoints
```php
// routes/web.php
Route::get('aula/observacion', [AtencionController::class, 'createMovilAula'])->name('atenciones.movil.aula');
Route::post('aula/observacion', [AtencionController::class, 'storeMovilAula'])->name('atenciones.movil.store');
```

### 2.2 Características Clave de la Experiencia Móvil
- **Diseño Touch-Friendly:** Botones de gran tamaño (área táctil mínima de 44px) y navegación inferior fija.
- **Filtro Rápido en Vivo de Estudiantes:** Permite escribir las primeras letras del nombre o del grupo para seleccionar instantáneamente al alumno.
- **Chips de Atajos de Conducta:** Botones de un solo toque que rellenan el motivo automáticamente:
  - *Atención dispersa / Dificultad para enfocar*
  - *Disrupción / Desafío a la consigna*
  - *Crisis de autorregulación emocional*
  - *Interacción social / Conflicto entre pares*
  - *Avance pedagógico significativo*
  - *Fatiga / Desinterés / Somnolencia*
  - *Respuesta favorable a adecuación curricular*
- **Compatibilidad con Dictado por Voz:** Textareas amplias optimizadas para el micrófono del teclado móvil.
- **Modo Continuo en Aula:** Opción para guardar y mantenerse en la pantalla para capturar al siguiente alumno sin recargar el navegador.

---

## 3. Módulo 3: Impresión y Exportación Formal a PDF

Provee vistas especialmente maquetadas bajo estándares de impresión (`@media print`, tamaño Carta, saltos de página inteligentes `page-break-inside: avoid`).

### 3.1 Expediente Completo del Alumno (`/alumnos/{id}/imprimir`)
- Encabezado con heráldica institucional del Colegio Paidos Campeche.
- Ficha de identidad y CURP.
- Diagnóstico clínico, especialista tratante externo, farmacología y adecuaciones curriculares en aula.
- Tabla formal con la bitácora completa de intervenciones, observaciones y acuerdos.
- **Sección oficial de cuatro firmas:** Psicopedagoga titular, Dirección del plantel, Docente del grupo y Padre/Madre/Tutor legal.
- Leyenda legal de confidencialidad médica y escolar.

### 3.2 Reporte Mensual de Dirección (`/dashboard/imprimir`)
- Resumen cuantitativo por categoría: Aula, Padres, Alumno y Docente.
- Gran total de intervenciones del mes.
- Estado de atención y cobertura de los casos de Alta Prioridad.
- Bitácora desglosada de intervenciones del periodo.
- Bloque de firmas de entrega y recepción oficial entre Psicopedagogía y Dirección Escolar.

---

## 4. Módulo 4: Ciclos Escolares y Transición de Periodo ("Brincado")

Este módulo administra la vigencia temporal de los ciclos escolares y ejecuta el proceso de pase de grado institucional mediante transacciones seguras en MariaDB.

### 4.1 Matriz de Rutas y Endpoints
```php
// routes/web.php
Route::get('ciclos', [CicloController::class, 'index'])->name('ciclos.index');
Route::get('ciclos/transicion', [CicloController::class, 'transicionForm'])->name('ciclos.transicion');
Route::post('ciclos/transicion', [CicloController::class, 'ejecutarTransicion'])->name('ciclos.transicion.store');
```

| Método HTTP | URI | Nombre de Ruta | Acción en Controlador | Vista Asociada |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/ciclos` | `ciclos.index` | `CicloController@index` | `ciclos/index.blade.php` |
| `GET` | `/ciclos/transicion` | `ciclos.transicion` | `CicloController@transicionForm` | `ciclos/transicion.blade.php` |
| `POST` | `/ciclos/transicion` | `ciclos.transicion.store` | `CicloController@ejecutarTransicion` | Redirección a `ciclos.index` |

### 4.2 Lógica del Proceso de Transición ("Brincado")
El método `ejecutarTransicion(TransicionCicloRequest $request)` opera dentro de una transacción atómica `DB::transaction()` garantizando consistencia absoluta:

1. **Inmovilización del Ciclo Saliente:**
   El ciclo saliente cambia su estado a `CONGELADO`, bloqueando la alteración retroactiva de expedientes o intervenciones pasadas.
2. **Apertura del Nuevo Ciclo:**
   Se crea el nuevo registro en `ciclos_escolares` con estado `ACTIVO` y las fechas seleccionadas por la institución.
3. **Casos Especiales en el Pase de Alumnos:**
   - **`PROMOVER`:**
     - El expediente anterior se sella con `estado_ciclo = 'PROMOVIDO'`.
     - Se crea un nuevo registro en `expedientes_ciclo` asignado al nuevo ciclo con el nuevo grado/grupo (ej. `3-A`).
     - **Herencia Clínica:** Se clonan íntegramente los campos diagnósticos (`diagnostico`, `tiene_valoracion_fisica`, `tratamiento_farmacologico`, `detalle_farmaco`, `requiere_adecuaciones`, `detalle_adecuaciones`, `especialista_externo`, `prioridad`).
     - Las bitácoras anteriores quedan asociadas a su expediente de origen, garantizando que el nuevo año inicie con historial limpio para nuevas notas pero con antecedentes accesibles.
   - **`REPETIR`:**
     - El expediente anterior se actualiza con `estado_ciclo = 'REPETIDOR'`.
     - Se genera una nueva ficha en el ciclo entrante con el mismo grado asignado, heredando antecedentes médicos y adecuaciones.
   - **`SECUNDARIA`:**
     - Para alumnos de 6° grado que concluyen primaria.
     - El expediente anterior se sella como `SECUNDARIA`.
     - El alumno actualiza su estatus general a `EGRESADO_SECUNDARIA`. No se abre expediente en el nuevo ciclo.
   - **`ARCHIVO_MUERTO`:**
     - Para alumnos que se dan de baja del plantel.
     - El expediente anterior queda marcado como `ARCHIVO_MUERTO` y el alumno se marca con estatus `ARCHIVO_MUERTO`.

---

## 5. Módulo 5: Validación Estricta con Form Requests

Para evitar registros incompletos o formatos de fecha erróneos, la lógica de validación fue desacoplada de los controladores e implementada en clases `FormRequest` especializadas con mensajes en español y reglas estrictas:

| Clase Form Request | Controlador | Reglas Clave |
| :--- | :--- | :--- |
| `StoreAlumnoRequest` | `AlumnoController@store` | `nombre_completo` (required, max:255), `curp` (nullable, regex:18 caracteres alfanuméricos), `grado_grupo` (required), `diagnostico` (required), `prioridad` (in:ALTA,SEGUIMIENTO). |
| `UpdateAlumnoRequest` | `AlumnoController@update` | Mismas reglas de identidad aplicando `Rule::unique('alumnos')->ignore(...)` para CURP; validación de enums para `estado_ciclo` (`ACTIVO`, `PROMOVIDO`, `REPETIDOR`, `SECUNDARIA`, `ARCHIVO_MUERTO`). |
| `StoreAtencionRequest` | `AtencionController@store` | `expediente_ciclo_id` (required, exists), `tipo_atencion` (in:AULA,PADRES,ALUMNO,DOCENTE), `fecha_hora` (required, date), `duracion_minutos` (integer, min:5, max:240), `motivo` (required, min:5), `observaciones` (required, min:5). |
| `StoreMovilAulaRequest` | `AtencionController@storeMovilAula` | Adaptado para captura rápida en aula móvil; valida selección de alumno activo, motivo (min:3) y observaciones de conducta (min:3). |
| `TransicionCicloRequest` | `CicloController@ejecutarTransicion` | `nombre_ciclo` (required, unique:ciclos_escolares), `fecha_inicio` (required, date), `fecha_fin` (required, date, after:fecha_inicio), `alumnos` (required, array), `alumnos.*.accion` (in:PROMOVER,REPETIR,SECUNDARIA,ARCHIVO_MUERTO). |

---

## 6. Módulo 6: Optimización de Consultas Eloquent (Anti-N+1)

Se implementó carga ansiosa (*eager loading*) en todos los métodos de consulta para evitar el antipatrón de $N+1$ queries:

```php
// En AlumnoController@index:
$expedientes = ExpedienteCiclo::with(['alumno', 'ciclo'])
    ->where('ciclo_id', $ciclo->id)
    ->get();

// En AlumnoController@show:
$expediente = ExpedienteCiclo::with([
    'alumno',
    'ciclo',
    'atenciones' => fn($q) => $q->orderBy('fecha_hora', 'desc')
])->findOrFail($id);

// En AtencionController@create:
$expedientes = ExpedienteCiclo::with('alumno')
    ->where('ciclo_id', $ciclo->id)
    ->where('estado_ciclo', 'ACTIVO')
    ->get();
```
**Resultado:** Reducción del tiempo de respuesta y de consumo de recursos en MariaDB de $\mathcal{O}(N)$ consultas a solo 2 o 3 consultas agregadas constantes por petición.

---

## 7. Módulo 7: Seguridad, Autenticación y Control de Roles

Garantiza que la información clínica y diagnóstica sea consultada con exclusividad por Psicopedagogía y Dirección, aplicando un bloqueo absoluto a la plantilla docente.

### 7.1 Matriz de Rutas de Seguridad
```php
// routes/web.php
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('cambiar-rol/{rol}', [AuthController::class, 'switchRole'])->name('switch.role');
```

### 7.2 Lógica de Negocio y Middleware (`EnsureCanAccessExpedientes`)
* Todo acceso a expedientes (`alumnos.*`), bitácoras (`atenciones.*`), modo aula (`aula.*`) y brincado (`ciclos.*`) es interceptado por el middleware `can.expedientes`.
* Si un usuario con rol `DOCENTE` intenta realizar una petición:
  1. La solicitud se aborta con código **HTTP 403 Forbidden**.
  2. Se renderiza la vista institucional `errors/403.blade.php` fundamentada en la Ley de Protección de Datos de Salud Infantil.
  3. En la barra de navegación se ocultan los enlaces a expedientes y se muestra una insignia de advertencia.

---

## 8. Módulo 8: Copias de Seguridad Locales (mysqldump) e Infraestructura

Administra la persistencia segura de la base de datos MariaDB y la estabilidad del servidor local.

### 8.1 Rutas de Respaldos
```php
// routes/web.php (Protegido por can.expedientes)
Route::get('respaldos', [BackupController::class, 'index'])->name('respaldos.index');
Route::post('respaldos', [BackupController::class, 'crear'])->name('respaldos.crear');
Route::get('respaldos/descargar/{archivo}', [BackupController::class, 'descargar'])->name('respaldos.descargar');
Route::delete('respaldos/{archivo}', [BackupController::class, 'eliminar'])->name('respaldos.eliminar');
```

### 8.2 Comando Artisan `php artisan db:backup`
* Ejecuta `mysqldump` con la bandera `--single-transaction` para evitar bloqueos de tablas InnoDB.
* Guarda los volcados en `storage/app/backups/backup_psicodata_YYYY-MM-DD_HHmmss.sql`.
* Aplica una política de retención automática que conserva las **30 copias más recientes**, eliminando excedentes.
* Permite la descarga directa de archivos SQL para su custodia en memorias USB institucionales.

### 8.3 Comando de Estabilidad `php artisan app:check-environment`
* Verifica que el proyecto no se encuentre dentro de carpetas sincronizadas en la nube (OneDrive/Dropbox) para evitar errores de bloqueo (`database is locked`) y saturación de CPU en `vendor/`.
* Se acompaña del script de migración [`scripts/migrar_fuera_de_onedrive.ps1`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/scripts/migrar_fuera_de_onedrive.ps1).
