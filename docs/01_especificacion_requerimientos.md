# 01. Especificación de Requerimientos de Software (SRS)
**Proyecto:** PsicoData — Colegio Paidos Campeche  
**Módulo:** Departamento de Psicopedagogía  

---

## 1. Introducción y Propósito

El presente documento formaliza las reglas de negocio, requerimientos funcionales y requerimientos no funcionales para la operación del sistema **PsicoData**. La plataforma digitaliza la gestión psicopedagógica del Colegio Paidos Campeche, garantizando la confidencialidad clínica, trazabilidad por ciclo escolar y agregación estadística de las atenciones escolares.

---

## 2. Matriz de Acceso de Usuarios Oficial (Colegio Paidos Campeche)

| Usuario / Actor | Rol / Permisos | Correo Institucional | Alcance y Facultades Operativas |
| :--- | :--- | :--- | :--- |
| **Mercedes Segovia Mayo** (Psicóloga Mercy) | **Acceso Completo** (`PSICOPEDAGOGIA` / `ADMIN`) | `mercysegovia@colegiopaidoscampeche.edu.mx` | Administración, edición de expedientes clínicos, captura de intervenciones, modo aula móvil, brincado de ciclos y cierres de mes. |
| **Greyci Salas Cahuich** (Psicóloga Greyci) | **Acceso Completo** (`PSICOPEDAGOGIA`) | `greycia@colegiopaidoscampeche.edu.mx` | Edición de expedientes, captura de intervenciones, observaciones en aula y notas de evolución. |
| **Damaris Morales Bastarrachea** (Directora de Primaria) | **Solo Lectura** (`DIRECCION`) | `damarismorales@colegiopaidoscampeche.edu.mx` | Consulta de expedientes, auditoría de casos de alta prioridad, reportes e informes ejecutivos trimestrales y mensuales con firma de visto bueno. |
| **Plantilla Docente (Restringido)** | **Sin Acceso** (`DOCENTE`) | `docente@colegiopaidoscampeche.edu.mx` | Colabora en acuerdos pedagógicos. **No tiene acceso a consultar expedientes clínicos** (bloqueo HTTP 403 por confidencialidad según RN-01). |
| **Administrador de TI** | **Infraestructura** (`ADMIN`) | `admin@colegiopaidoscampeche.edu.mx` | Servidor físico local, copias de seguridad de base de datos (`mysqldump`) y soporte técnico. |

---

## 3. Reglas de Negocio (RN)

Estas reglas definen las políticas y restricciones operativas del departamento de Psicopedagogía:

* **RN-01 (Privacidad Estricta de Expedientes):** La información psicopedagógica únicamente puede ser consultada y editada por el personal autorizado del departamento y la Dirección. Los docentes u otro personal externo no tienen acceso a estos expedientes (bloqueo HTTP 403 Forbidden).
* **RN-02 (Permanencia del Seguimiento):** Los alumnos con diagnósticos (como TEA, TDAH, trastornos del lenguaje, etc.) no reciben un "alta" definitiva; su seguimiento se mantiene activo durante toda su estancia en la institución educativa.
* **RN-03 (Niveles de Prioridad de Atención):** Los estudiantes se catalogan en dos niveles de atención:
  - **Alta Prioridad:** Exige intervenciones y seguimiento de forma mensual.
  - **Seguimiento:** Requiere atención de manera trimestral.
* **RN-04 (Trazabilidad e Inalterabilidad por Ciclo):** Al finalizar un ciclo escolar, el historial de observaciones y atenciones de ese periodo queda cerrado y congelado (`CONGELADO`) para consulta histórica. Al abrir un nuevo ciclo ("brincado"), se heredan los antecedentes clínicos sin modificar ni borrar los registros pasados.
* **RN-05 (Convivencia de Formatos Institucionales):** El sistema actúa como un concentrador digital para captura rápida y métricas, pero no reemplaza la papelería oficial requerida por supervisión escolar, la cual debe seguir entregándose físicamente con firmas autógrafas (se provee exportación e impresión formal membretada con 4 firmas).

---

## 4. Requerimientos Funcionales (RF)

Estas son las funciones y acciones que el sistema permite realizar a los usuarios:

### Módulo de Gestión de Expedientes y Alumnos
* **RF-01 (Registro de Ficha Psicopedagógica):** El sistema permite dar de alta a un estudiante capturando: nombre completo, grado, grupo, docente titular, diagnóstico clínico, si cuenta con valoración física, tratamiento farmacológico (y su detalle), adecuaciones curriculares, especialista externo a cargo y nivel de prioridad.
* **RF-02 (Búsqueda y Filtrado):** Se puede buscar a los estudiantes por su nombre y aplicar filtros dinámicos por grado, grupo y nivel de prioridad (`Alta Prioridad` vs `Seguimiento Trimestral`).
* **RF-03 (Edición y Acciones de Ficha):** Es posible modificar las variables del alumno (actualización de diagnóstico o cambio de fármaco) y cuenta con la opción para dar de baja / transferir a archivo muerto si el alumno egresa o ya no continúa en la escuela, con posibilidad de reactivación.

### Módulo de Seguimiento e Intervenciones
* **RF-04 (Registro de Atenciones):** El sistema permite registrar cada intervención categorizándola en uno de 4 tipos operativos:
  1. *Observación en aula* (`AULA` - incluye Modo Aula móvil touch).
  2. *Entrevista con padres* (`PADRES`).
  3. *Entrevista con alumno / Nota de evolución* (`ALUMNO`).
  4. *Reunión con docentes* (`DOCENTE`).
* **RF-05 (Detalle de la Intervención):** Para cada registro de atención se captura la fecha, hora, duración en minutos, asistentes/participantes, motivo, observaciones detalladas y los compromisos o acuerdos pactados.
* **RF-06 (Historial del Estudiante):** El sistema muestra una línea de tiempo cronológica inversa que lista todas las atenciones, incidencias y notas de evolución asociadas al expediente del alumno.

### Módulo de Métricas y Reportes
* **RF-07 (Cierre Mensual Automático):** El sistema automatiza el conteo mensual de actividades para la Dirección, mostrando los totales de observaciones en aula, entrevistas con padres, entrevistas con alumnos y reuniones docentes en un mes y año específicos, junto con los casos prioritarios atendidos.
* **RF-08 (Exportación y Descargas):** El sistema permite descargar e imprimir la información de los expedientes e informes mensuales en formato membretado PDF con el bloque oficial de 4 firmas para estructurar y enviar informes a los padres de familia o para la supervisión escolar.

### Módulo de Transición de Ciclo ("Brincado")
* **RF-09 (Pase de Ciclo):** El sistema cuenta con una función asistida que permite clonar y transferir la ficha del alumno hacia un nuevo ciclo escolar, actualizando sus datos (promoción de grado y grupo) y gestionando casos especiales (repetidores, egresados a secundaria y archivo muerto), manteniendo intactas sus notas evolutivas de ciclos pasados.

---

## 5. Requerimientos No Funcionales (RNF)

Estos requerimientos establecen los atributos de calidad, infraestructura y seguridad del sistema:

* **RNF-01 (Seguridad y Resguardo Local):** La aplicación y la base de datos están alojadas en un servidor físico local dentro de las instalaciones del colegio para salvaguardar la privacidad de la información sensible de los menores.
* **RNF-02 (Cifrado y Privacidad):** El sistema cuenta con cifrado seguro (algoritmo Bcrypt con factor de costo configurado) para las contraseñas de los usuarios autorizados, garantizando accesos protegidos.
* **RNF-03 (Acceso mediante Red Institucional):** El sistema web es accesible de manera local únicamente desde la red LAN/Wi-Fi interna del colegio para las computadoras de psicopedagogía y dirección. Los usuarios no autenticados son dirigidos de inmediato a la pantalla de Login.
* **RNF-04 (Usabilidad y Rendimiento):** La interfaz de usuario es rápida, minimalista y responsiva (construida con Tailwind CSS), con barra inferior táctil para dispositivos móviles, orientada a evitar fricción manual y optimizar el tiempo de captura de los expedientes y notas de evolución (eliminando consultas $N+1$ mediante eager loading).
* **RNF-05 (Integridad de Base de Datos):** La arquitectura de base de datos relacional (MariaDB) mantiene llaves foráneas consistentes (`onDelete('cascade')`) y cuenta con rutinas de respaldo (backup) periódicas con `mysqldump`, retención automática de 30 copias y opción de descarga a USB.
