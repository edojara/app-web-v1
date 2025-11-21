# Copilot Instructions - Sistema de Acreditación

## Arquitectura General

Este es un sistema MVC personalizado en PHP vanilla (sin frameworks) para gestión de acreditaciones de eventos educativos. La aplicación maneja instituciones, participantes, eventos, inscripciones y check-ins con autenticación dual (local + Google OAuth).

### Estructura MVC
- **Enrutador**: `public/index.php` - convierte URLs limpias a controlador/acción (`?url=participantes/view/123`)
- **Controladores**: `app/controllers/*Controller.php` - lógica de negocio, instancian modelos y renderizan vistas
- **Modelos**: `app/models/*.php` - acceso a datos usando MySQLi con prepared statements
- **Vistas**: `app/views/` - PHP templates que usan `VIEWS_PATH`, `APP_URL` y layout header/footer

### Flujo de Enrutamiento
```php
// URL: /?url=participantes/edit/123
// → ParticipantesController::edit()
// → Guiones en URL se convierten a guiones bajos: google-login → google_login()
```

## Configuración Multi-Entorno

**CRÍTICO**: La configuración se centraliza en `config/config.php`:
- `DOMAIN_URL` - dominio base (ej: `http://acreditacion.grupoeducar.cl`)
- `APP_URL` - ruta pública de la app (usualmente igual a `DOMAIN_URL`)
- Usar `APP_URL` en todas las URLs absolutas en vistas y redirecciones

Ver `CONFIG_DOMAIN.md` para cambios de entorno. No hardcodear URLs.

## Base de Datos y Migraciones

**Conexión**: Variable global `$conn` (MySQLi) inicializada en `config/database.php`

**Migraciones**: Archivos SQL separados en raíz:
- `migration_*.sql` - esquemas de tablas principales
- `insert_*.sql` - datos de prueba
- `migrate_*.sql` - cambios de esquema (OAuth, auditoría)

**Tablas principales**:
- `users` (con `role_id`, `auth_type`, `google_id`)
- `instituciones` (universidades/organizaciones)
- `participantes` (asistentes con RUT chileno)
- `eventos` (conferencias con fechas múltiples)
- `inscripciones` (relación participante-evento)
- `checkins` (asistencia diaria)
- `auditoria_app` (logs de acciones en módulos)
- `user_audit_logs` (logs de cambios en usuarios)

## Patrones de Código

### Modelos (acceso a datos)
```php
class Participante {
    private $conn;
    private $table = 'participantes';
    
    public function __construct() {
        global $conn;  // SIEMPRE usar $conn global
        $this->conn = $conn;
    }
    
    // SIEMPRE usar prepared statements
    public function getByRut($rut) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE rut = ?");
        $stmt->bind_param("s", $rut);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
```

### Controladores (lógica de negocio)
```php
class ParticipantesController {
    private $participanteModel;
    private $auditoriaModel;
    
    public function __construct() {
        $this->participanteModel = new Participante();
        $this->auditoriaModel = new AuditoriaApp();  // Para logging
    }
    
    public function index() {
        $participantes = $this->participanteModel->getAll();
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/participantes/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
}
```

### Auditoría
**SIEMPRE** registrar operaciones CRUD importantes:
```php
$this->auditoriaModel->log(
    'participantes',           // módulo
    'crear',                   // acción
    $participanteId,           // registro_id
    $nombreCompleto,           // registro_nombre
    ['campo' => 'valor']       // cambios (opcional)
);
```

### Autenticación
- Sesión se inicia en `public/index.php`
- `$_SESSION['user_id']`, `$_SESSION['user_role_id']`, `$_SESSION['auth_type']`
- AuthController excluido de auth check (permite login/OAuth)
- Google OAuth: credenciales en `.env`, flujo en `AuthController::googleLogin()` y `google_callback()`

### RUT Chileno (dato específico del dominio)
- Formato: `12.345.678-9`
- Normalización: `preg_replace('/[^0-9kK]/', '', $rut)` antes de consultas
- Ver `Participante::getByRut()` para patrón

## Vistas y Assets

- **Layout**: Siempre incluir `layout/header.php` y `layout/footer.php`
- **Assets**: Usar `<?php echo APP_URL; ?>/assets/css/style.css`
- **JavaScript**: `public/assets/js/main.js` - funciones globales (toggle menus, validaciones RUT)
- **Páginas**: No usar emojis en títulos salvo en navegación (🎓 ya definido)

## Debugging

`config/config.php` define `DEBUG` constant:
```php
if (DEBUG) {
    echo "Error: " . $e->getMessage();
}
```

Cambiar a `false` en producción.

## Convenciones

- **Nombres de archivo**: PascalCase para clases (`ParticipantesController.php`)
- **Nombres de tabla**: snake_case plural (`participantes`, `user_audit_logs`)
- **Métodos**: camelCase (`getByRut()`, `updateLastLogin()`)
- **Constantes**: UPPER_SNAKE_CASE (`VIEWS_PATH`, `APP_URL`)
- **SQL**: Siempre preparar statements, nunca concatenar strings

## Deployment y Git Workflow

**CRÍTICO**: El servidor web se actualiza vía SSH + Git pull desde GitHub.

### Flujo de deployment típico:
```bash
# 1. En desarrollo local - commit y push
git add .
git commit -m "descripción del cambio"
git push origin main

# 2. Conectar al servidor vía SSH (sin contraseña - clave SSH configurada)
ssh 192.168.100.222
cd /var/www/html/app-web-v1  # o ruta según configuración Apache
git pull origin main

# 3. Aplicar migraciones si hay cambios de schema
mysql -u app_user -p app_web_db < migration_nueva.sql

# 4. Verificar permisos (si es necesario)
chmod -R 755 app/ config/ public/

# Deploy en un solo comando desde local (alternativa):
ssh 192.168.100.222 "cd /var/www/html/app-web-v1 && git pull origin main"
```

### Archivos NO versionados (ver `.gitignore`):
- `.env` - credenciales de Google OAuth (crear manualmente en servidor)
- `*.log` - logs de aplicación
- `/vendor/` `/node_modules/` - dependencias (no usadas actualmente)

**IMPORTANTE**: Credenciales en servidor deben configurarse manualmente:
```bash
# En servidor, crear .env basándose en .env.example
cp .env.example .env
nano .env  # Añadir GOOGLE_CLIENT_ID y GOOGLE_CLIENT_SECRET reales
```

## Comandos Útiles

```bash
# Aplicar migración
mysql -u app_user -p app_web_db < migration_participantes.sql

# Resetear base de datos (desarrollo - ¡NO en producción!)
mysql -u app_user -p app_web_db < database.sql

# Ver estado de Apache (útil después de deploy)
sudo systemctl status apache2

# Logs de errores PHP (si DEBUG=false)
tail -f /var/log/apache2/error.log
```

## Integraciones Externas

- **Google OAuth 2.0**: Ver `GOOGLE_OAUTH_SETUP.md` - requiere `.env` con `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET`
- **Apache mod_rewrite**: URLs limpias dependen de `.htaccess` en `public/`
- **GitHub**: Repositorio sincronizado vía SSH, pull manual en servidor después de push

## Notas de Desarrollo

- **Sin Composer**: No hay autoloading, usar `require_once` explícito
- **Sin ORM**: Queries SQL directos con MySQLi
- **Sin validación frontend**: Validar en controladores (ver `ParticipantesController::store()`)
- **Timezone**: `America/Mexico_City` definida en `config/config.php`
