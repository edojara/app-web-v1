<?php
/**
 * Configuración de Google OAuth2
 * 
 * Para configurar:
 * 1. Ve a https://console.cloud.google.com
 * 2. Crea un nuevo proyecto
 * 3. Habilita Google+ API
 * 4. Crea credenciales OAuth2 (Aplicación web)
 * 5. Autoriza redirect: http://acreditacion.grupoeducar.cl/?url=auth/google-callback
 * 6. Copia los valores aquí
 */

// Google OAuth2 Credentials
define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', APP_URL . '/?url=auth/google-callback');

?>
