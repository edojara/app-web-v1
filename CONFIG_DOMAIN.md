# Configuración de Dominio

## Cambiar URL de la Aplicación

Para cambiar la URL de la aplicación, solo necesitas modificar una variable en:

**Archivo:** `config/config.php`

```php
// ⚙️ CONFIGURACIÓN DE DOMINIO - Cambiar aquí para diferentes ambientes
define('DOMAIN_URL', 'https://acreditacion.grupoeducar.cl');
define('APP_URL', DOMAIN_URL . '/app-web-v1');
```

## Ejemplos de Configuración

### Desarrollo Local
```php
define('DOMAIN_URL', 'http://localhost');
```
URLs resultantes:
- `http://localhost/app-web-v1/public/index.php`
- `http://localhost/app-web-v1/public/index.php?url=users`

### Servidor de Producción (IP)
```php
define('DOMAIN_URL', 'http://192.168.100.222');
```
URLs resultantes:
- `http://192.168.100.222/app-web-v1/public/index.php`
- `http://192.168.100.222/app-web-v1/public/index.php?url=users`

### Dominio de Producción
```php
define('DOMAIN_URL', 'https://acreditacion.grupoeducar.cl');
```
URLs resultantes:
- `https://acreditacion.grupoeducar.cl/app-web-v1/public/index.php`
- `https://acreditacion.grupoeducar.cl/app-web-v1/public/index.php?url=users`

## Pasos para Cambiar

1. Abre `config/config.php`
2. Busca la línea: `define('DOMAIN_URL', ...);`
3. Reemplaza el valor según tu ambiente
4. Guarda el archivo
5. Sincroniza con GitHub y el servidor

## Nota sobre HTTPS

Si usas HTTPS (recomendado para producción), asegúrate de:

1. Tener un certificado SSL válido instalado en Apache
2. Configurar el VirtualHost para soportar HTTPS
3. Redirigir HTTP → HTTPS en `.htaccess`

Ejemplo de redirect en `public/.htaccess`:
```apache
<IfModule mod_rewrite.c>
    # Redirigir HTTP a HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # ... resto de configuración
</IfModule>
```
