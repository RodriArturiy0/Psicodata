# 03. Base de Datos y Diccionario de Datos
**Proyecto:** PsicoData — Colegio Paidos Campeche  

---

## 1. Diagrama Entidad-Relación (ERD)

```text
  +-------------------------+             +-------------------------------+
  |    ciclos_escolares     |             |            alumnos            |
  +-------------------------+             +-------------------------------+
  | PK  id                  |             | PK  id                        |
  |     nombre_ciclo (U)    |             |     nombre_completo           |
  |     fecha_inicio        |             |     curp (U, null)            |
  |     fecha_fin           |             |     fecha_nacimiento (null)   |
  |     estado              |             |     tutor_nombre (null)       |
  +-------------------------+             |     tutor_telefono (null)     |
               │ 1                        |     estatus_general           |
               │                          +-------------------------------+
               │                                          │ 1
               │ N                                        │
  +-------------------------------------------------------┼---------------+
  |                           expedientes_ciclo           │ N             |
  +-----------------------------------------------------------------------+
  | PK  id                                                                |
  | FK  alumno_id    -------> alumnos(id) [ON DELETE CASCADE]             |
  | FK  ciclo_id     -------> ciclos_escolares(id) [ON DELETE CASCADE]    |
  |     grado_grupo                                                       |
  |     docente_titular                                                   |
  |     canalizado_por (null)                                             |
  |     fecha_ingreso_programa (null)                                     |
  |     diagnostico                                                       |
  |     area_atencion (null)                                              |
  |     tiene_valoracion_fisica                                           |
  |     tratamiento_farmacologico                                         |
  |     detalle_farmaco                                                   |
  |     requiere_adecuaciones                                             |
  |     detalle_adecuaciones                                              |
  |     compromisos_familia (null)                                        |
  |     especialista_externo                                              |
  |     prioridad (ALTA, SEGUIMIENTO)                                     |
  |     estado_ciclo (ACTIVO, PROMOVIDO, REPETIDOR, ...)                  |
  +-----------------------------------------------------------------------+
               │ 1
               │
               │ N
  +-----------------------------------------------------------------------+
  |                           atenciones_eventos                          |
  +-----------------------------------------------------------------------+
  | PK  id                                                                |
  | FK  expediente_ciclo_id -> expedientes_ciclo(id) [ON DELETE CASCADE]  |
  | FK  user_id             -> users(id) [ON DELETE SET NULL]             |
  |     tipo_atencion (AULA, PADRES, ALUMNO, DOCENTE)                     |
  |     duracion_minutos (null)                                           |
  |     fecha_hora                                                        |
  |     participantes                                                     |
  |     motivo                                                            |
  |     observaciones                                                     |
  |     acuerdos                                                          |
  |     estatus_compromiso (EN_PROCESO, CUMPLIDO, PENDIENTE)              |
  +-----------------------------------------------------------------------+
```

---

## 2. Diccionario de Datos Detallado

### Tabla: `ciclos_escolares`
Controla la temporalidad de los periodos escolares anuales.

| Campo | Tipo | Nulo | Clave | Valor por Defecto | Descripción |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | BIGINT UNSIGNED | NO | PK | Auto-incremental | Identificador único del ciclo escolar. |
| `nombre_ciclo` | VARCHAR(20) | NO | UNIQUE | — | Etiqueta del ciclo (ej. `2026-2027`). |
| `fecha_inicio` | DATE | NO | — | — | Fecha oficial de inicio de clases. |
| `fecha_fin` | DATE | NO | — | — | Fecha de término del periodo lectivo. |
| `estado` | ENUM | NO | — | `'ACTIVO'` | Estado del ciclo (`ACTIVO`, `INACTIVO`, `CONGELADO`). |
| `created_at` | TIMESTAMP | SÍ | — | NULL | Marca de tiempo de inserción. |
| `updated_at` | TIMESTAMP | SÍ | — | NULL | Marca de tiempo de última modificación. |

---

### Tabla: `alumnos`
Almacena la identidad permanente e inmutable del estudiante dentro del colegio, así como los datos institucionales del tutor.

| Campo | Tipo | Nulo | Clave | Valor por Defecto | Descripción |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | BIGINT UNSIGNED | NO | PK | Auto-incremental | Identificador único del estudiante. |
| `nombre_completo`| VARCHAR(150) | NO | — | — | Nombre y apellidos completos del alumno. |
| `curp` | VARCHAR(18) | SÍ | UNIQUE | NULL | Clave Única de Registro de Población. |
| `fecha_nacimiento`| DATE | SÍ | — | NULL | Fecha de nacimiento oficial del alumno. |
| `tutor_nombre` | VARCHAR(150) | SÍ | — | NULL | Nombre completo del padre, madre o tutor legal. |
| `tutor_telefono` | VARCHAR(30) | SÍ | — | NULL | Teléfono de contacto / emergencias de la familia. |
| `estatus_general`| ENUM | NO | — | `'ACTIVO'` | Condición institucional (`ACTIVO`, `EGRESADO_SECUNDARIA`, `ARCHIVO_MUERTO`). |
| `created_at` | TIMESTAMP | SÍ | — | NULL | Fecha de creación del registro. |
| `updated_at` | TIMESTAMP | SÍ | — | NULL | Fecha de última edición. |

---

### Tabla: `expedientes_ciclo`
Ficha psicopedagógica oficial adaptada a los formatos del Colegio Paidos Campeche.

| Campo | Tipo | Nulo | Clave | Valor por Defecto | Descripción |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | BIGINT UNSIGNED | NO | PK | Auto-incremental | Identificador único del expediente anual. |
| `alumno_id` | BIGINT UNSIGNED | NO | FK | — | Referencia al alumno (`alumnos.id`). |
| `ciclo_id` | BIGINT UNSIGNED | NO | FK | — | Referencia al ciclo (`ciclos_escolares.id`). |
| `grado_grupo` | VARCHAR(10) | NO | — | — | Grado y grupo cursado (ej. `2-A`). |
| `docente_titular`| VARCHAR(120) | NO | — | — | Nombre del maestro(a) titular del aula. |
| `canalizado_por` | VARCHAR(100) | SÍ | — | NULL | Entidad que derivó al alumno (Docente, Dirección, Padres). |
| `fecha_ingreso_programa`| DATE | SÍ | — | NULL | Fecha oficial de apertura de la ficha. |
| `diagnostico` | VARCHAR(150) | NO | — | — | Diagnóstico psicopedagógico (ej. TEA, TDAH). |
| `area_atencion` | VARCHAR(100) | SÍ | — | NULL | Área: Socioemocional, Cognitiva, Lenguaje, Motora. |
| `tiene_valoracion_fisica` | BOOLEAN | NO | — | `TRUE` | Cuenta con dictamen médico físico en archivo. |
| `tratamiento_farmacologico`| BOOLEAN | NO | — | `FALSE` | Toma medicamentos controlados. |
| `detalle_farmaco`| VARCHAR(150) | SÍ | — | NULL | Fármaco, dosis y horario escolar. |
| `requiere_adecuaciones` | BOOLEAN | NO | — | `FALSE` | Ajustes razonables requeridos en aula. |
| `detalle_adecuaciones` | TEXT | SÍ | — | NULL | Descripción detallada de las adecuaciones. |
| `compromisos_familia` | TEXT | SÍ | — | NULL | Compromisos formales firmados por los tutores. |
| `especialista_externo` | VARCHAR(150) | SÍ | — | NULL | Terapeuta, neurólogo o paidopsiquiatra tratante. |
| `prioridad` | ENUM | NO | — | `'SEGUIMIENTO'` | Frecuencia (`ALTA` = mensual, `SEGUIMIENTO` = trimestral). |
| `estado_ciclo` | ENUM | NO | — | `'ACTIVO'` | Estatus anual (`ACTIVO`, `PROMOVIDO`, `REPETIDOR`, `SECUNDARIA`, `ARCHIVO_MUERTO`). |
| `created_at` | TIMESTAMP | SÍ | — | NULL | Fecha de creación del expediente. |
| `updated_at` | TIMESTAMP | SÍ | — | NULL | Fecha de última actualización. |

---

### Tabla: `atenciones_eventos`
Registro individual de intervenciones, observaciones y acuerdos alcanzados.

| Campo | Tipo | Nulo | Clave | Valor por Defecto | Descripción |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | BIGINT UNSIGNED | NO | PK | Auto-incremental | Identificador único de la atención. |
| `expediente_ciclo_id` | BIGINT UNSIGNED | NO | FK | — | Referencia al expediente anual intervenido. |
| `user_id` | BIGINT UNSIGNED | SÍ | FK | NULL | Especialista que capturó la atención (`users.id`). |
| `tipo_atencion` | ENUM | NO | — | — | Ámbito: `AULA`, `PADRES`, `ALUMNO`, `DOCENTE`. |
| `duracion_minutos` | SMALLINT | SÍ | — | NULL | Duración de la sesión u observación en minutos. |
| `fecha_hora` | DATETIME | NO | — | — | Fecha y hora en la que se llevó a cabo el evento. |
| `participantes` | VARCHAR(200) | SÍ | — | NULL | Nombres de personas presentes en la sesión. |
| `motivo` | TEXT | NO | — | — | Causa o situación que originó la intervención. |
| `observaciones`| TEXT | NO | — | — | Anotaciones clínicas y pedagógicas detalladas. |
| `acuerdos` | TEXT | SÍ | — | NULL | Compromisos o tareas acordadas entre las partes. |
| `estatus_compromiso` | VARCHAR(50) | SÍ | — | `'EN_PROCESO'`| Seguimiento del acuerdo (`EN_PROCESO`, `CUMPLIDO`, `PENDIENTE`). |
| `created_at` | TIMESTAMP | SÍ | — | NULL | Marca de tiempo de registro. |
| `updated_at` | TIMESTAMP | SÍ | — | NULL | Marca de tiempo de modificación. |

---

## 3. Integridad Referencial y Reglas de Cascada

1. **`expedientes_ciclo.alumno_id` y `expedientes_ciclo.ciclo_id`:** `->onDelete('cascade')`.
2. **`atenciones_eventos.expediente_ciclo_id`:** `->onDelete('cascade')`.
3. **`atenciones_eventos.user_id`:** `->nullOnDelete()`. Preserva la autoría de las intervenciones aún si un usuario se retira.
