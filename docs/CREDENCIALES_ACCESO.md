# Catálogo de Cuentas y Credenciales de Acceso Oficial — PsicoData
**Colegio Paidos Campeche — Departamento de Psicopedagogía (Nivel Primaria)**

Este documento contiene las credenciales autorizadas para operar y realizar pruebas en el servidor institucional. Las credenciales de demostración rápida y los accesos sin contraseña han sido removidos del código fuente para garantizar la seguridad del sistema y el cumplimiento de las normativas de protección de datos de salud infantil.

---

## 1. Matriz Oficial de Usuarios

| Nombre Completo | Cargo Institucional | Rol en Sistema | Correo de Acceso (Usuario) | Contraseña | Nivel de Acceso |
| :--- | :--- | :---: | :--- | :---: | :--- |
| **Mercedes Segovia Mayo** | Psicóloga Titular / Admin | `PSICOPEDAGOGIA` | `mercysegovia@colegiopaidoscampeche.edu.mx`<br>*(Alias: `mercy@colegiopaidoscampeche.edu.mx`)* | `paidos2026` | **Acceso Completo (Admin):** Expedientes, intervenciones, bitácora móvil de aula, brincado de ciclos y respaldos. |
| **Greyci Salas Cahuich** | Psicóloga de Primaria | `PSICOPEDAGOGIA` | `greycia@colegiopaidoscampeche.edu.mx`<br>*(Alias: `greyci@colegiopaidoscampeche.edu.mx`)* | `paidos2026` | **Acceso Clínico:** Edición de expedientes, registro de intervenciones y notas de aula móvil. |
| **Damaris Morales Bastarrachea** | Directora de Nivel Primaria | `DIRECCION` | `damarismorales@colegiopaidoscampeche.edu.mx`<br>*(Alias: `damaris@colegiopaidoscampeche.edu.mx`)* | `paidos2026` | **Solo Lectura:** Consulta de expedientes, métricas de dashboard e informe mensual para firma. |
| **Administración de TI** | Soporte e Infraestructura LAN | `ADMIN` | `admin@colegiopaidoscampeche.edu.mx` | `paidos2026` | **Infraestructura:** Gestión de respaldos SQL (`mysqldump`) y mantenimiento. |
| **Mtra. Docente Titular** | Plantilla Docente *(Prueba de Bloqueo)* | `DOCENTE` | `docente@colegiopaidoscampeche.edu.mx` | `paidos2026` | **Restringido:** Acceso únicamente a métricas; expedientes clínicos bloqueados por confidencialidad (403). |

---

## 2. Instrucciones de Inicio y Cierre de Sesión

1. **Ingreso:**
   - Dirígete en el navegador a la dirección del servidor (ej. `http://192.168.1.150:8000/` o `http://127.0.0.1:8000/`).
   - Ingresa tu **correo institucional** y tu **contraseña**.
   - Haz clic en **"Iniciar Sesión"**.

2. **Cambio de Usuario:**
   - Para cambiar de cuenta, haz clic en el botón **"Salir"** (icono de puerta de salida) ubicado en la esquina superior derecha del panel.
   - El sistema cerrará la sesión de forma segura e invalidará el token.
   - Introduce las credenciales del nuevo usuario en el formulario de inicio de sesión.
