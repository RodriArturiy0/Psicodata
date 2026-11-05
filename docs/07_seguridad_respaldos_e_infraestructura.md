# 07. Seguridad, Privacidad e Infraestructura del Sistema
**Proyecto:** PsicoData — Colegio Paidos Campeche  
**Módulo:** Departamento de Psicopedagogía  

---

## 1. Control Estricto de Roles y Confidencialidad Clínica

### 1.1 Marco Normativo y Justificación Institucional
La información procesada en **PsicoData** comprende datos personales sensibles relativos a la salud física, estado neuropsicológico, diagnósticos psiquiátricos, administración de psicofármacos y problemáticas intrafamiliares de menores de edad.

Conforme a la **Ley General de Protección de Datos Personales en Posesión de Sujetos Obligados**, la normativa educativa de la **SEP** y el reglamento ético-pedagógico del Colegio Paidos Campeche:
* El acceso al expediente psicopedagógico detallado está **estrictamente restringido** a la Especialista en Psicopedagogía y a la Dirección Escolar.
* **La plantilla docente no tiene autorización de consulta** directa a los expedientes clínicos, bitácoras de entrevistas con tutores, ni fichas farmacológicas. La docente solo recibe por canales verbales o circulares pedagógicas las adaptaciones curriculares específicas que debe implementar en el salón de clases, sin acceso a información sensible de diagnóstico profundo.

### 1.2 Matriz de Roles y Niveles de Acceso (RBAC)

| Módulo / Acción | Psicopedagogía (`PSICOPEDAGOGIA`) | Dirección Escolar (`DIRECCION`) | Plantilla Docente (`DOCENTE`) | Administrador TI (`ADMIN`) |
| :--- | :---: | :---: | :---: | :---: |
| **Cierres y Métricas (Dashboard)** | Acceso Total | Acceso Total | Vista Restringida | Acceso Total |
| **Informe Ejecutivo Mensual (PDF)** | Generación y Firma | Consulta y Firma | ❌ Bloqueado | ❌ Bloqueado |
| **Directorio de Expedientes** | Alta, Edición y Bajas | Consulta y Auditoría | ❌ **BLOQUEO 403** | Consulta Técnica |
| **Ficha Clínica y Diagnóstico** | Edición Completa | Consulta | ❌ **BLOQUEO 403** | ❌ Bloqueado |
| **Intervenciones y Modo Aula 📱** | Registro Completo | Consulta | ❌ **BLOQUEO 403** | ❌ Bloqueado |
| **Ciclos y Pase de Grado ("Brincado")**| Ejecución | Supervisión | ❌ **BLOQUEO 403** | Soporte Técnico |
| **Copias de Seguridad (mysqldump)** | Generación Manual | Descarga | ❌ **BLOQUEO 403** | Gestión Completa |

### 1.3 Implementación Técnica del Bloqueo a Docentes
* **Middleware `EnsureCanAccessExpedientes` (`can.expedientes`):**
  Interpreta el rol del usuario autenticado. Si un usuario con rol `DOCENTE` intenta realizar cualquier petición a rutas de expedientes (`/alumnos`, `/atenciones`, `/aula/observacion`, `/ciclos`, `/respaldos`), el sistema intercepta la petición y responde con código **HTTP 403 Forbidden**.
* **Vista Especializada de Seguridad (`resources/views/errors/403.blade.php`):**
  Explica formalmente el motivo legal e institucional del bloqueo para disuadir intentos no autorizados y evitar confusiones en la plantilla magisterial.

---

## 2. Estrategia de Respaldos Locales (MariaDB / mysqldump)

El sistema opera dentro del servidor físico institucional del Colegio Paidos Campeche sin depender de servicios de almacenamiento en la nube pública, resguardando la soberanía de los datos sensibles.

### 2.1 Comando Artisan Dedicado (`php artisan db:backup`)
Se desarrolló el comando de consola `App\Console\Commands\DatabaseBackupCommand` con las siguientes características:
1. **Detección Automática del Motor:** Conecta con las credenciales activas en `.env` para MariaDB/MySQL.
2. **Volcado No Bloqueante (`mysqldump`):**
   ```bash
   mysqldump --host="127.0.0.1" --port="3306" --user="root" --single-transaction --quick --routines --triggers "psicodata" > "storage/app/backups/backup_psicodata_YYYY-MM-DD_HHmmss.sql"
   ```
   * `--single-transaction`: Garantiza consistencia transaccional en tablas InnoDB sin bloquear lecturas ni escrituras concurrentes.
   * `--routines` y `--triggers`: Preserva procedimientos y disparadores de la base de datos.
3. **Política de Retención Automática:** Mantiene automáticamente los últimos **30 respaldos** en disco físico, depurando los más antiguos para prevenir la saturación del almacenamiento del servidor.
4. **Registro de Auditoría:** Toda ejecución se documenta con fecha, duración en segundos y peso en KB en `storage/logs/backups.log`.

### 2.2 Rutina Programada en el Servidor (Automated Cron / Task Scheduler)
En `routes/console.php` se encuentra programada la ejecución diaria a las 23:00 hrs:
```php
Schedule::command('db:backup')->dailyAt('23:00')->appendOutputTo(storage_path('logs/backups.log'));
```

#### A. Configuración en Windows Server (Colegio Paidos Campeche)
El proyecto incluye el script listo para ejecutar: [`scripts/backup_database.ps1`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/scripts/backup_database.ps1). Para programarlo en el Programador de Tareas de Windows:
```powershell
schtasks /create /tn "PsicoData_Respaldo_Diario" /tr "powershell.exe -ExecutionPolicy Bypass -File C:\servidor\psicodata\scripts\backup_database.ps1" /sc daily /st 23:00 /ru SYSTEM
```

#### B. Configuración en Linux Server
Script: [`scripts/backup_database.sh`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/scripts/backup_database.sh).
```bash
# Agregar a crontab:
0 23 * * * /var/www/psicodata/scripts/backup_database.sh >> /var/log/psicodata_backup.log 2>&1
```

### 2.3 Panel Web de Gestión de Respaldos (`/respaldos`)
La especialista, dirección o personal de TI puede:
* Consultar la lista histórica de respaldos con fecha y peso en MB.
* Presionar el botón **"Generar Respaldo Ahora (mysqldump)"** antes de realizar procesos sensibles (como el cambio de ciclo escolar).
* **Descargar el archivo `.sql` directamente** para guardarlo en una unidad USB institucional o disco externo fuera del servidor principal.

---

## 3. Estabilidad en Entorno de Desarrollo y Desvinculación de OneDrive

### 3.1 Causa Técnica de los Bloqueos en Servicios de Nube Sincronizada
Alojar la carpeta del proyecto dentro de **Microsoft OneDrive**, **Dropbox** o **Google Drive** genera fallos críticos de operación:

1. **Bloqueo Concurrente de Archivos de Base de Datos (`Database is locked`):**
   OneDrive monitorea el sistema de archivos mediante eventos de Windows (`ReadDirectoryChangesW`). Cuando MariaDB o SQLite escriben en sus archivos de datos (`.ibd`, `.sqlite-journal`, `.sqlite-wal`), OneDrive intenta abrirlos inmediatamente en modo lectura/carga para subirlos a la nube. Esto colisiona con el bloqueo exclusivo del motor de base de datos, arrojando excepciones fatales `SQLSTATE[HY000]: General error: 5 database is locked`.
2. **Saturación Extrema de CPU e I/O por la Carpeta `vendor/`:**
   Laravel contiene más de 35,000 archivos PHP pequeños en `vendor/`. OneDrive intenta sincronizar cada uno individualmente, provocando saturación continua del disco al 100% y tiempos de respuesta de varios segundos en solicitudes locales.
3. **Corrupción en Transacciones de Pase de Grado:**
   Si OneDrive bloquea un archivo temporal durante el "Brincado", la transacción atómica puede abortar inesperadamente.

### 3.2 Diagnóstico Automatizado (`php artisan app:check-environment`)
Se desarrolló un comando de verificación que analiza la ruta base del sistema y emite advertencias técnicas en consola si detecta que la aplicación corre bajo carpetas sincronizadas.

### 3.3 Procedimiento de Migración a Directorio Local Seguro
Para garantizar el rendimiento óptimo, el proyecto debe residir en una ruta local independiente:
* Directorio recomendado de desarrollo: `C:\Proyectos\psicodata`
* Directorio recomendado de producción: `C:\servidor\psicodata` o `C:\laragon\www\psicodata`

El script automatizado [`scripts/migrar_fuera_de_onedrive.ps1`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/scripts/migrar_fuera_de_onedrive.ps1) ejecuta la copia excluyendo cachés y carpetas pesadas:
```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\migrar_fuera_de_onedrive.ps1 -Destino "C:\Proyectos\psicodata"
```
Una vez copiado:
```powershell
cd C:\Proyectos\psicodata
composer install
php artisan migrate --seed
php artisan app:check-environment
```
Esto asegura **0% de bloqueos de archivo**, ejecución instantánea de pruebas y absoluta integridad de datos.

