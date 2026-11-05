# 02. Arquitectura de Software y Diseño del Sistema
**Proyecto:** PsicoData — Colegio Paidos Campeche  

---

## 1. Patrón Arquitectónico: Modelo-Vista-Controlador (MVC)

**PsicoData** está estructurado sobre el patrón arquitectónico estándar **MVC** provisto por el framework **Laravel**, garantizando una clara separación entre la lógica de negocio, el almacenamiento de datos y la presentación en interfaz de usuario.

```text
               +-------------------------------------------------+
               |             Navegador Web (Cliente LAN)        |
               +-------------------------------------------------+
                                      │ HTTP Request
                                      ▼
               +-------------------------------------------------+
               |               routes/web.php                    |
               +-------------------------------------------------+
                                      │ Enrutamiento
                                      ▼
               +-------------------------------------------------+
               |            Controladores (app/Http/...)         |
               |   AlumnoController, AtencionController,        |
               |   DashboardController                           |
               +-------------------------------------------------+
                         │                            │
           Eloquent ORM  │                            │ Compacta datos
                         ▼                            ▼
  +--------------------------------+       +---------------------------------+
  |      Modelos (app/Models)      |       |      Vistas (resources/views)   |
  |  Alumno, CicloEscolar,         |       |  Blade Templates + Tailwind CSS |
  |  ExpedienteCiclo, etc.         |       +---------------------------------+
  +--------------------------------+                      │
                 │                                        │ Render HTML
                 ▼                                        ▼
  +--------------------------------+       +---------------------------------+
  |    Base de Datos (MariaDB)     |       |       HTML / CSS al Cliente     |
  +--------------------------------+       +---------------------------------+
```

---

## 2. Descripción de Capas del Sistema

### Capa 1: Enrutamiento (`routes/web.php`)
- Define los puntos de entrada HTTP del sistema.
- Emplea `Route::resource('alumnos', AlumnoController::class)->only(['index', 'show', 'create', 'store'])` para generar rutas semánticas RESTful estándar.
- Asigna nombres unívocos a cada ruta (`dashboard`, `alumnos.index`, `atenciones.create`, etc.) para desacoplar las URLs de las plantillas Blade mediante el helper `route('nombre')`.

### Capa 2: Controladores (`app/Http/Controllers/`)
Contienen la orquestación del flujo de datos y las validaciones de entrada:
- [`DashboardController`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/app/Http/Controllers/DashboardController.php): Procesa agregaciones SQL con `COUNT(*)` y agrupa métricas mensuales.
- [`AlumnoController`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/app/Http/Controllers/AlumnoController.php): Orquesta la transacción atómica entre `Alumno` y `ExpedienteCiclo`, y gestiona el filtrado y el renderizado del historial del estudiante.
- [`AtencionController`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/app/Http/Controllers/AtencionController.php): Valida la consistencia de los eventos y almacena los compromisos y acuerdos alcanzados.

### Capa 3: Modelos y ORM Eloquent (`app/Models/`)
- Mapean las tablas relacionales de la base de datos a objetos PHP.
- Definen las relaciones relacionales (`hasMany`, `belongsTo`) permitiendo el uso de métodos fluidos como `$expediente->alumno->nombre_completo` o `$expediente->atenciones`.
- Utilizan `$fillable` para proteger contra asignación masiva inadvertida (*mass assignment protection*).
- Implementan `$casts` para convertir automáticamente atributos como `fecha_hora` a instancias completas de Carbon.

### Capa 4: Capa de Presentación (Blade + Tailwind CSS)
- Localizada en `resources/views/`.
- Utiliza la plantilla maestra [`layouts/app.blade.php`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/resources/views/layouts/app.blade.php) que incluye:
  - Barra lateral de navegación permanente (*sidebar*) con accesos directos al Dashboard, Expedientes y Registro de Intervención.
  - Indicador de estado del servidor en tiempo real ("Servidor Físico Local • LAN", "MariaDB Local Conectada").
  - Contenedor para mensajes flash de notificación (`session('success')`).
  - Iconografía vectorial basada en **FontAwesome 6**.
  - Estilos declarativos con **Tailwind CSS**.

---

## 3. Decisiones Clave de Diseño y Patrones Aplicados

1. **Eager Loading para mitigar el problema N+1:**
   En lugar de consultar la base de datos en cada iteración de un bucle de expedientes, los controladores cargan previamente las relaciones:
   ```php
   ExpedienteCiclo::with('alumno')->where('estado_ciclo', 'ACTIVO')->get();
   ```
2. **Desacoplamiento Alumno / Expediente:**
   Evita la redundancia de datos personales al matricular a un estudiante en ciclos escolares futuros.
3. **Persistencia y Resiliencia en Registro de Alumnos:**
   Si la base de datos no tiene un ciclo activo registrado, el controlador lo crea preventivamente para evitar interrupciones de servicio al usuario final.
4. **Validación Declarativa en Backend:**
   Todas las entradas de formularios son validadas en el servidor mediante `$request->validate([...])`, garantizando la integridad de tipos, límites de caracteres y claves foráneas válidas.

