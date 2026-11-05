# Documentación Oficial del Proyecto PsicoData
**Colegio Paidos Campeche — Departamento de Psicopedagogía**

Bienvenido al repositorio de documentación oficial del sistema **PsicoData**. Esta suite documental ha sido estructurada siguiendo estándares formales de ingeniería de software para facilitar el mantenimiento, la auditoría y el desarrollo continuo del proyecto.

---

## 📑 Estructura de la Documentación

| Archivo | Descripción | Audiencia Objetivo |
| :--- | :--- | :--- |
| [01. Especificación de Requerimientos](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/01_especificacion_requerimientos.md) | Requisitos funcionales (RF) y no funcionales (RNF), actores del sistema y alcance. | Analistas, Dirección, Desarrolladores |
| [02. Arquitectura y Diseño](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/02_arquitectura_y_diseno.md) | Arquitectura MVC en Laravel, componentes, capas de la aplicación y flujo de datos. | Arquitectos de Software, Desarrolladores |
| [03. Base de Datos y Diccionario](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/03_base_de_datos.md) | Diagrama Entidad-Relación, estructura de tablas, tipos de datos, llaves e integridad referencial. | DBAs, Desarrolladores Backend |
| [04. Especificación de Módulos y CRUD](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/04_especificacion_modulos_y_crud.md) | Explicación detallada de rutas, controladores, validaciones de formularios y vistas Blade. | Desarrolladores Frontend/Backend |
| [05. Manual de Instalación y Despliegue](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/05_manual_instalacion_y_despliegue.md) | Guía paso a paso para desplegar en servidor físico local (LAN), MariaDB, Composer y Laravel. | SysAdmins, Soporte Técnico |
| [06. Manual de Usuario y Operación](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/06_manual_de_usuario.md) | Guía operativa para el personal de psicopedagogía (alta, edición, bajas, modo aula móvil y exportación PDF). | Psicopedagogas, Docentes, Dirección |
| [07. Seguridad, Respaldos e Infraestructura](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/07_seguridad_respaldos_e_infraestructura.md) | Control de roles, bloqueo estricto a docentes, rutinas mysqldump y aislamiento de OneDrive. | SysAdmins, Dirección, Desarrolladores |
| [Documento Maestro Consolidado (MD)](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/DOCUMENTACION_PSICODATA.md) | Documento unificado en Markdown para visualización rápida. | Todos los perfiles |
| [Documento Maestro en PDF](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/docs/DOCUMENTACION_PSICODATA.pdf) | Versión lista para imprimir o enviar formalmente a Dirección. | Dirección General, Supervisores |

---

## 🚀 Nuevas Funcionalidades Incorporadas

1. **📱 Modo Aula (Captura Móvil en Vivo):**
   - Vista touch-friendly para smartphone/tablet con atajos rápidos de conducta, filtro de estudiantes y compatibilidad con dictado por voz.
2. **✏️ Gestión Completa de Expedientes (Edición y Bajas):**
   - Actualización completa de diagnósticos, CURP, fármacos y adecuaciones curriculares.
   - Módulo de bajas lógicas (*Archivo Muerto*, *Egresados a Secundaria*) con protección de historial médico y opción de reactivación.
3. **🖨️ Módulo de Exportación e Impresión Oficial (PDF):**
   - Expedientes clínicos con heráldica institucional, bitácora de intervenciones y bloque formal de 4 firmas.
   - Informe mensual ejecutivo para Dirección Escolar con balance cuantitativo y casos de Alta Prioridad.
4. **🔄 Transición y Cierre de Ciclo ("Brincado de Grado"):**
   - Cierre atómico del periodo anterior (`CONGELADO`) y apertura del nuevo año (`ACTIVO`).
   - Herencia automática de diagnósticos, medicación y adecuaciones sin sobrescribir notas pasadas.
   - Manejo de casos especiales: repetidores, egresados a secundaria y archivo muerto.
5. **🛡️ Validación Estricta y Optimización Backend (MariaDB):**
   - Clases `FormRequest` desacopladas (`StoreAlumnoRequest`, `UpdateAlumnoRequest`, `StoreAtencionRequest`, `StoreMovilAulaRequest`, `TransicionCicloRequest`) con mensajes en español y reglas de integridad.
   - Carga ansiosa (*eager loading* con `with(...)`) para erradicar el problema de consultas $N+1$ en Eloquent.
6. **🔒 Seguridad, Privacidad e Infraestructura:**
   - **Control Estricto de Roles:** Bloqueo absoluto (HTTP 403) a la plantilla docente para consultar expedientes o diagnósticos clínicos.
   - **Respaldos Automatizados (mysqldump):** Comando `db:backup`, panel web y retención de 30 copias.
   - **Estabilidad de Entorno:** Herramienta `app:check-environment` y script de migración para desvincular el proyecto de OneDrive y evitar bloqueos de base de datos.

---

## 🎯 Información del Proyecto

- **Nombre del Sistema:** PsicoData
- **Institución:** Colegio Paidos Campeche
- **Área Responsable:** Módulo de Psicopedagogía
- **Entorno de Operación:** Servidor Físico Local en Red de Área Local (LAN)
- **Base de Datos:** MariaDB Local
- **Ciclo Escolar Vigente:** 2026-2027
