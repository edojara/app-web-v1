<?php
/**
 * Configuración General de la Aplicación
 */

// Datos de la aplicación
define('APP_NAME', 'App Web LAMP');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/app-web-v1');

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
