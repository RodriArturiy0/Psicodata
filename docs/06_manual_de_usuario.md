# 06. Manual de Usuario y Operación Institucional
**Proyecto:** PsicoData — Colegio Paidos Campeche  
**Módulo:** Departamento de Psicopedagogía  

---

## 1. Introducción al Entorno de Trabajo

El sistema **PsicoData** está optimizado para facilitar la captura ágil durante la jornada escolar tanto en computadoras de escritorio como en dispositivos móviles (celulares o tablets). Su interfaz está dividida en:

1. **Barra Lateral Izquierda (Menú de Navegación):**
   - **Cierres y Métricas:** Panel con conteos del mes, gran total e indicadores de casos prioritarios.
   - **Expedientes:** Directorio clasificado en fichas activas, bajas y archivo muerto.
   - **+ Intervención General:** Formulario completo para registrar citas, sesiones o entrevistas.
   - **Modo Aula (Móvil) 📱:** Vista ultrarrápida diseñada para capturar observaciones directamente en clase.
2. **Área de Trabajo Principal:** Muestra los formularios, tablas dinámicas, líneas de tiempo de seguimiento y botones de exportación a PDF.

---

## 2. Flujo Operativo 1: Alta y Registro de un Estudiante

1. En el menú lateral, hacer clic en **"Expedientes"**.
2. En la esquina superior derecha, presionar **"+ Nueva Ficha"**.
3. Completar los campos solicitados:
   - **Nombre Completo del Estudiante** y **CURP** (opcional).
   - **Grado y Grupo** (ej. `2-A`) y **Docente Titular**.
   - **Diagnóstico:** Especificar la condición clínica o psicopedagógica (ej. *TEA Nivel 1*, *TDAH*, *Trastorno del lenguaje*).
   - **Nivel de Prioridad:**
     - **Alta Prioridad:** Casos que requieren intervención y supervisión mensual activa.
     - **Seguimiento:** Casos estables con monitoreo trimestral.
   - **Especialista Externo Tratante:** Médico o terapeuta externo.
   - **Indicadores Médicos:** Marcar si cuenta con valoración física física entregada o si consume medicamentos controlados.
4. Presionar **"Guardar Ficha"**. El sistema creará el registro y abrirá automáticamente el expediente.

---

## 3. Flujo Operativo 2: Edición de Datos Clínicos y Personales

Cuando el diagnóstico cambia, se ajusta la medicación o se modifican las adecuaciones curriculares:

1. Ingresar a **"Expedientes"** y seleccionar el alumno, o abrir su vista de detalle.
2. Presionar el botón **"Editar Ficha"** (ícono de lápiz).
3. En el formulario de edición podrás actualizar:
   - Nombre oficial o CURP.
   - Cambio de grado, grupo o docente titular.
   - Diagnóstico y especialista tratante externo.
   - Medicamento y dosificación (aparece automáticamente al activar la casilla *Tratamiento farmacológico*).
   - Adecuaciones curriculares específicas para el aula (al activar la casilla *Requiere adecuaciones*).
   - Estado en el ciclo escolar (`ACTIVO`, `PROMOVIDO`, `REPETIDOR`, `SECUNDARIA`, `ARCHIVO_MUERTO`).
4. Presionar **"Guardar Cambios"**.

---

## 4. Flujo Operativo 3: Módulo de Bajas y Archivo Muerto

Cuando un estudiante egresa de la institución o se retira del colegio:

1. **Desde la Lista de Expedientes:**
   - La tabla cuenta con pestañas superiores: **Activos**, **Bajas / Archivo Muerto** y **Todos**.
2. **Para dar de baja a un alumno:**
   - Abrir la edición del alumno o presionar la acción de baja en su perfil.
   - Seleccionar el motivo de retiro:
     - `Archivo Muerto`: Baja general por retiro de la institución.
     - `Egresó a Secundaria`: Conclusión formal de nivel escolar.
     - `Repetidor`: En caso de recursamiento.
   - Confirmar la acción.
3. **Preservación del Historial:** El alumno desaparecerá de la lista activa y de los conteos mensuales del ciclo para no alterar las estadísticas, pero **su expediente e historial clínico quedan 100% protegidos** en la pestaña *Bajas / Archivo Muerto*.
4. **Reactivación:** En caso de reingreso, dentro de la ficha del alumno aparecerá un botón verde **"Reactivar Estudiante"** que lo regresará al estado activo con un solo clic.

---

## 5. Flujo Operativo 4: Captura Móvil de Observaciones en Aula (Modo Aula 📱)

Diseñado para usarse en celular o tablet mientras la especialista camina por los salones de clase:

1. Acceder a **"Modo Aula 📱"** desde la barra lateral o mediante el enlace directo `/aula/observacion`.
2. **Seleccionar Estudiante:** Utiliza el campo de búsqueda rápida para escribir las primeras letras del nombre o del grupo (ej. `2-A`); el selector resaltará al alumno de inmediato.
3. **Atajos Rápidos de Motivo (Chips táctiles):** Presiona uno de los botones de atajo para no tener que escribir:
   - *Atención dispersa / Dificultad para enfocar*
   - *Disrupción / Desafío a la consigna*
   - *Crisis de autorregulación emocional*
   - *Interacción social / Conflicto entre pares*
   - *Avance pedagógico significativo*
   - *Fatiga / Desinterés / Somnolencia*
   - *Respuesta favorable a adecuación curricular*
4. **Detalle Observado:** Escribe las anotaciones de clase o utiliza el botón de micrófono del teclado de tu celular para dictar por voz. Puedes presionar **"+ Hora actual"** para registrar marcas de tiempo exactas.
5. **Guardar:**
   - Selecciona **"Seguir en aula"** para guardar y continuar inmediatamente con el siguiente alumno.
   - O selecciona **"Ver expediente"** para revisar la línea de tiempo completa del alumno.
6. Presionar el botón inferior **"Guardar Observación en Aula"**. El evento se anexará al expediente del estudiante y se sumará a la categoría `AULA` en las métricas de Dirección.

---

## 6. Flujo Operativo 5: Impresión y Exportación a PDF

### A. Expediente Clínico Completo del Alumno
1. Entrar al perfil del estudiante y presionar el botón **"Imprimir / PDF"**.
2. Se abrirá la vista oficial con formato de hoja membretada del Colegio Paidos.
3. Presionar el botón superior **"Imprimir / Guardar como PDF"** (o presionar `Ctrl + P`).
4. En el diálogo de impresión de tu navegador:
   - Destino: Seleccionar **"Guardar como PDF"** o tu impresora física.
   - El documento incluye datos de identidad, condición médica, bitácora completa de intervenciones y el **bloque oficial de cuatro firmas** (Psicopedagoga, Dirección, Maestro de grupo y Tutor).

### B. Informe Mensual para Dirección Escolar
1. Ir al panel principal **"Cierres y Métricas"**.
2. Seleccionar el mes y año deseados y presionar **"Ver"**.
3. Presionar el botón **"Imprimir Informe / PDF"**.
4. Se generará el informe ejecutivo mensual con los totales por categoría (Aula, Padres, Alumnos, Docentes), el seguimiento de casos de Alta Prioridad y el espacio de firmas para la entrega formal a la Dirección General.

---

## 7. Flujo Operativo 6: Transición de Ciclo Escolar y "Brincado de Grado"

Al concluir el año académico institucional, la especialista o el administrador realiza el cierre de ciclo escolar mediante el asistente de "Brincado", garantizando que los expedientes pasados queden inalterables y el nuevo ciclo arranque con antecedentes clínicos listos:

1. **Ingreso al Módulo:**
   - En la barra lateral o barra superior, hacer clic en **"Ciclos y Brincado"**.
   - En el panel de periodos lectivos, presionar el botón azul **"Iniciar Pase de Ciclo ("Brincado")"**.
2. **Definir el Nuevo Ciclo Escolar:**
   - **Nombre del Nuevo Ciclo:** Escribir la denominación institucional (ej. `2027-2028`).
   - **Fecha de Inicio y Fecha de Fin:** Fechas oficiales del calendario escolar.
3. **Revisión y Ajuste de Alumnos en el Asistente:**
   - El sistema cargará automáticamente a todos los alumnos activos del ciclo saliente y predecirá su pase al siguiente grado:
     - Los alumnos de `1°` a `5°` se sugerirán como **Promover** al grado siguiente (ej. de `1-A` a `2-A`).
     - Los alumnos de `6°` se sugerirán automáticamente como **Pasa a Secundaria**.
   - Para cada alumno puedes ajustar la acción según su situación particular:
     - **Promover al Siguiente Grado:** Pasa al grado superior conservando diagnósticos y adecuaciones.
     - **Repetidor (Mismo Grado):** Conserva el mismo grado para el nuevo año lectivo.
     - **Pasa a Secundaria:** Egresa formalmente de la primaria del colegio y su expediente queda congelado para consultas históricas.
     - **Baja / Archivo Muerto:** El alumno no continuará en la escuela.
4. **Confirmación y Ejecución:**
   - Presionar **"Confirmar y Ejecutar Brincado de Grado"**.
   - El sistema ejecutará el proceso en una transacción atómica:
     - El ciclo saliente quedará sellado como `CONGELADO`.
     - El nuevo ciclo escolar quedará configurado como `ACTIVO`.
     - Todos los antecedentes clínicos y adaptaciones se clonarán a las nuevas fichas escolares sin sobrescribir las notas de los años pasados.

---

## 8. Flujo Operativo 7: Control de Roles y Respaldo Local (mysqldump)

### A. Autenticación y Perfiles Institucionales
1. **Inicio de Sesión:** Ingrese a través de la pantalla institucional de login (`/login`) utilizando su cuenta oficial del Colegio Paidos.
2. **Niveles de Privacidad:**
   - **Psicopedagogía:** Acceso total a expedientes, fichas diagnósticas, modo aula y edición de intervenciones.
   - **Dirección Escolar:** Auditoría ejecutiva, visualización de métricas y descarga de reportes oficiales membretados.
   - **Plantilla Docente:** Si una cuenta con rol docente intenta acceder a expedientes o bitácoras, el sistema mostrará la pantalla de **Bloqueo 403 por Confidencialidad**, protegiendo los diagnósticos y psicofármacos del alumno.
3. **Conmutador Rápido:** Para propósitos de demostración o auditoría interna, se dispone de un selector de perfiles en la cabecera superior y en la pantalla de inicio.

### B. Generación y Descarga de Respaldos (mysqldump)
1. En el menú lateral o en la barra superior, hacer clic en **"Respaldos"**.
2. **Crear Respaldo Inmediato:** Presionar el botón azul **"Generar Respaldo Ahora (mysqldump)"**. El servidor físico creará una copia completa e inmutable de la base de datos en menos de 2 segundos.
3. **Custodia en Memoria USB:** En la tabla de respaldos, presione **"Descargar SQL"** para guardar una copia externa en una memoria USB institucional resguardada en Dirección.
4. **Política Automática:** El sistema conserva en disco los últimos 30 respaldos, purgando automáticamente los más antiguos para cuidar el espacio de almacenamiento.

