# App Web LAMP v1.0.0

Una aplicación web moderna desarrollada con la arquitectura LAMP (Linux, Apache, MySQL, PHP).

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache 2.4+ con mod_rewrite habilitado
- Linux (Ubuntu, CentOS, Debian, etc.)

## Instalación

### 1. Clonar o descargar el proyecto

```bash
cd /var/www/html
git clone <tu-repositorio> app-web-v1
cd app-web-v1
```

### 2. Configurar permisos

```bash
chmod -R 755 app/
chmod -R 755 public/
chmod -R 755 config/
```

### 3. Crear la base de datos

```bash
mysql -u root -p < database.sql
```

O manualmente en MySQL:

```sql
CREATE DATABASE app_web_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_web_db;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 4. Configurar base de datos

Edita `config/database.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
define('DB_NAME', 'app_web_db');
```

### 5. Configurar Apache

Crea un virtual host en Apache:

```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html/app-web-v1/public
    
    <Directory /var/www/html/app-web-v1/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Reinicia Apache:

```bash
sudo systemctl restart apache2
```

### 6. Acceder a la aplicación

Abre tu navegador y ve a:

```
http://localhost/app-web-v1/public/
```

## Estructura del Proyecto

```
app-web-v1/
├── app/
│   ├── controllers/     # Controladores (lógica de negocio)
│   ├── models/          # Modelos (interacción con BD)
│   └── views/           # Vistas (presentación)
│       └── layout/      # Plantillas comunes (header, footer)
├── config/
│   ├── config.php       # Configuración general
│   └── database.php     # Configuración de base de datos
├── public/
│   ├── index.php        # Punto de entrada (router principal)
│   └── .htaccess        # Reglas de reescritura de URLs
├── README.md            # Este archivo
└── database.sql         # Script de base de datos
```

## Enrutamiento

El sistema utiliza URLs limpias sin .php:

- `index.php` → Página de inicio
- `index.php?url=home/about` → Página de acerca de
- `index.php?url=users/profile/123` → Perfil de usuario

## Creando nuevos Controladores

1. Crea un archivo en `app/controllers/MiControlador.php`:

```php
<?php
class MiControladorController {
    public function index() {
        $pageTitle = "Mi Página";
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/micontrolador/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
}
?>
```

2. Accede con: `http://localhost/app-web-v1/public/index.php?url=micontrolador`

## Seguridad

- ✓ Preparados contra SQL Injection (prepared statements)
- ✓ Contraseñas hasheadas con bcrypt
- ✓ Validación de entrada
- ✓ Headers de seguridad
- ✓ CSRF protection (a implementar)

## Licencia

MIT

## Soporte

Para reportar bugs o sugerencias, abre un issue en el repositorio.
