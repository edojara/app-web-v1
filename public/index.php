<?php
/**
 * Punto de entrada principal de la aplicación
 */

// Cargar configuración
require_once dirname(dirname(__FILE__)) . '/config/config.php';

// Iniciar sesión para manejo de autenticación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = trim($url);

// Si la URL está vacía o es solo app-web-v1, usar 'home'
if (empty($url) || $url === 'app-web-v1') {
    $url = 'home';
}

$url = explode('/', $url);

// Obtener el controlador (por defecto: Home)
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Home';
$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . 'Controller.php';

// Obtener la acción (por defecto: index)
$action = isset($url[1]) ? $url[1] : 'index';

// DEBUG temporal - escribir en archivo
if (isset($_GET['url']) && strpos($_GET['url'], 'auth') !== false) {
    file_put_contents('/tmp/debug_app.log', 
        date('Y-m-d H:i:s') . " - URL: " . $_GET['url'] . "\n" .
        "Controller: $controllerName\n" .
        "Lowercase: " . strtolower($controllerName) . "\n" .
        "Is auth?: " . (strtolower($controllerName) === 'auth' ? 'YES' : 'NO') . "\n" .
        "URL array: " . print_r($url, true) . "\n\n",
        FILE_APPEND
    );
}

// Si el usuario no está autenticado, redirigir al login
// EXCEPTO para el controlador Auth (login, registro, OAuth, etc.)
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
if (!$isAuthenticated && strtolower($controllerName) !== 'auth') {
    header('Location: ' . APP_URL . '/?url=auth/login');
    exit;
}

// Convertir guiones a guiones bajos (google-login -> google_login)
$action = str_replace('-', '_', $action);

// Verificar si el controlador existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Nombre de la clase del controlador
    $className = $controllerName . 'Controller';
    
    // Verificar si la clase existe
    if (class_exists($className)) {
        $controller = new $className();
        
        // Verificar si el método existe
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            http_response_code(404);
            echo "Acción no encontrada: $action";
        }
    } else {
        http_response_code(404);
        echo "Clase de controlador no encontrada: $className";
    }
} else {
    http_response_code(404);
    echo "Controlador no encontrado: $controllerName";
}
?>
