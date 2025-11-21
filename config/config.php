<?php
/**
 * Configuración General de la Aplicación
 */

// Datos de la aplicación
define('APP_NAME', 'Acreditación');
define('APP_VERSION', '1.0.0');

// ⚙️ CONFIGURACIÓN DE DOMINIO - Cambiar aquí para diferentes ambientes
// Para desarrollo local: http://localhost
// Para servidor interno: http://acreditacion.grupoeducar.cl
define('DOMAIN_URL', 'http://acreditacion.grupoeducar.cl');

// APP_URL debe apuntar a la raíz donde se expone la aplicación.
// Si Apache está configurado con DocumentRoot -> /var/www/html/app-web-v1/public
// entonces la aplicación se sirve desde la raíz del dominio y APP_URL debe ser DOMAIN_URL.
define('APP_URL', DOMAIN_URL);

// Rutas
define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', BASE_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('MODELS_PATH', APP_PATH . '/models');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Modo debug (cambiar a false en producción)
define('DEBUG', true);

// Incluir configuración de base de datos
require_once BASE_PATH . '/config/database.php';
?>
