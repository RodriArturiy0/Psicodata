# 05. Manual de Instalación, Configuración y Despliegue Local
**Proyecto:** PsicoData — Colegio Paidos Campeche  

---

## 1. Requisitos Previos del Sistema

El sistema está diseñado para operar en un **servidor físico local dentro de la red del colegio (LAN)**.

### 1.1 Requisitos de Hardware del Servidor
- **Procesador:** 2 núcleos x86_64 o superior.
- **Memoria RAM:** Mínimo 4 GB (8 GB recomendado).
- **Almacenamiento:** 50 GB de espacio disponible (SSD preferente para optimizar I/O en base de datos).
- **Red:** Conexión cableada Ethernet a la infraestructura LAN del colegio con IP estática asignada (ej. `192.168.1.50`).

### 1.2 Requisitos de Software
- **Sistema Operativo:** Windows Server / Windows 10/11 Pro o Linux (Ubuntu Server 22.04 / 24.04 LTS).
- **PHP:** Versión 8.2 o superior con las siguientes extensiones habilitadas:
  - `php-pdo`
  - `php-pdo_mysql`
  - `php-mbstring`
  - `php-openssl`
  - `php-tokenizer`
  - `php-xml`
  - `php-ctype`
  - `php-json`
  - `php-curl`
- **Gestor de Dependencias:** Composer 2.x.
- **Motor de Base de Datos:** MariaDB 10.5+ o MySQL 8.0+.
- **Navegador en Equipos Clientes:** Google Chrome, Microsoft Edge, Mozilla Firefox o Safari actualizado.

---

## 2. Procedimiento de Instalación Paso a Paso

### Paso 1: Clonar o Copiar el Código Fuente en Directorio Local Exclusivo
> [!CAUTION]
> **REGLA DE ORO DE INFRAESTRUCTURA:**
> Nunca aloje el proyecto ni la base de datos dentro de carpetas sincronizadas en la nube (**Microsoft OneDrive**, **Dropbox**, **Google Drive**).
> La sincronización en caliente bloquea los archivos de tablas (`.ibd`), satura la CPU con los más de 35,000 archivos de `vendor/` y arroja errores fatales de bloqueo (`database is locked`).

Colocar el proyecto en una ruta local directa:
- En Windows: `C:\Proyectos\psicodata` (desarrollo) o `C:\servidor\psicodata` (producción).
- En Linux: `/var/www/psicodata`.

```bash
git clone https://github.com/tu-organizacion/psicodata.git C:\servidor\psicodata
cd C:\servidor\psicodata
```
Para verificar la estabilidad de la ruta elegida, ejecute:
```bash
php artisan app:check-environment
```

### Paso 2: Instalar Dependencias de PHP
Ejecutar Composer para descargar las librerías del framework:
```bash
composer install --optimize-autoloader --no-dev
```

### Paso 3: Configurar el Archivo de Entorno (`.env`)
Crear una copia de `.env.example` como `.env`:
```bash
cp .env.example .env
```
Editar los parámetros esenciales de conexión:
```env
APP_NAME="PsicoData"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://192.168.1.50:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=psicodata
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_segura
```

### Paso 4: Generar la Clave de Cifrado de la Aplicación
```bash
php artisan key:generate
```

### Paso 5: Preparar la Base de Datos y Ejecutar Migraciones
1. Iniciar sesión en MariaDB y crear la base de datos vacía:
   ```sql
   CREATE DATABASE psicodata CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Ejecutar las migraciones y poblar los datos iniciales institucionales:
   ```bash
   php artisan migrate --seed
   ```
   *Nota: El seeder [`DatabaseSeeder.php`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/database/seeders/DatabaseSeeder.php) creará la cuenta de psicopedagogía predeterminada, el ciclo escolar 2026-2027 y los expedientes piloto.*

### Paso 6: Ajustar Permisos de Carpetas
Asegurar permisos de lectura y escritura para el servidor web en:
- `storage/`
- `bootstrap/cache/`

---

## 3. Puesta en Marcha en Red Local (LAN)

Para permitir que otros equipos de cómputo del colegio (dirección, coordinaciones, psicopedagogía) accedan al sistema, el servicio debe escuchar en la interfaz de red local.

### Opción A: Modo Servicio Rápido (Desarrollo / Entorno Controlado)
```bash
php artisan serve --host=0.0.0.0 --port=8000
```
Cualquier equipo conectado a la misma red puede ingresar ingresando en su navegador:
`http://192.168.1.50:8000` *(Sustituir por la IP estática real del servidor).*

### Opción B: Modo Producción con Servidor Web Dedicado (Recomendado)
Configurar Apache o Nginx apuntando el DocumentRoot a la carpeta `/public` del proyecto, con reescritura de URLs (`mod_rewrite` habilitado).

---

## 4. Política y Automatización de Respaldos (mysqldump)

El sistema integra rutinas automatizadas para proteger la base de datos MariaDB:

### 4.1 Respaldo Manual Inmediato
Desde terminal:
```bash
php artisan db:backup
```
O directamente desde la interfaz web en el módulo de **Respaldos** (`/respaldos`).

### 4.2 Automatización Nocturna en Windows Server (Colegio Paidos)
El script listo para ejecución se ubica en [`scripts/backup_database.ps1`](file:///c:/Users/Luis_/OneDrive/Documentos/Psicodata/psicodata/scripts/backup_database.ps1). Para registrarlo en el Programador de Tareas:
```powershell
schtasks /create /tn "PsicoData_Backup_Diario" /tr "powershell.exe -ExecutionPolicy Bypass -File C:\servidor\psicodata\scripts\backup_database.ps1" /sc daily /st 23:00 /ru SYSTEM
```

### 4.3 Política de Retención y Custodia Externa
* El sistema conserva automáticamente los **últimos 30 respaldos** en `storage/app/backups/`.
* Se recomienda descargar semanalmente una copia `.sql` a una memoria USB resguardada bajo llave en la Dirección General.

