<?php
/**
 * Configuración de Google OAuth2
 * 
 * Configura las credenciales usando variables de entorno:
 * 
 * 1. En Apache, agrega a VirtualHost:
 *    SetEnv GOOGLE_CLIENT_ID "your-client-id.apps.googleusercontent.com"
 *    SetEnv GOOGLE_CLIENT_SECRET "your-client-secret"
 * 
 * 2. O en la terminal (bash):
 *    export GOOGLE_CLIENT_ID="your-client-id.apps.googleusercontent.com"
 *    export GOOGLE_CLIENT_SECRET="your-client-secret"
 * 
 * 3. O crea un archivo .env en la raíz del proyecto con:
 *    GOOGLE_CLIENT_ID=your-client-id
 *    GOOGLE_CLIENT_SECRET=your-client-secret
 */

// Cargar variables de entorno desde .env si existe
$envFile = dirname(dirname(__FILE__)) . '/.env';
$googleClientId = '';
$googleClientSecret = '';

if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    if ($env) {
        $googleClientId = $env['GOOGLE_CLIENT_ID'] ?? '';
        $googleClientSecret = $env['GOOGLE_CLIENT_SECRET'] ?? '';
        
        // También establecer en variables de entorno para compatibilidad
        foreach ($env as $key => $value) {
            if (!getenv($key)) {
                putenv("$key=$value");
            }
        }
    }
}

// Si no se cargó desde .env, intentar desde variables de entorno del sistema
if (empty($googleClientId)) {
    $googleClientId = getenv('GOOGLE_CLIENT_ID') ?: '';
}
if (empty($googleClientSecret)) {
    $googleClientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: '';
}

// Google OAuth2 Credentials
define('GOOGLE_CLIENT_ID', $googleClientId);
define('GOOGLE_CLIENT_SECRET', $googleClientSecret);
define('GOOGLE_REDIRECT_URI', APP_URL . '/?url=auth/google-callback');

?>
