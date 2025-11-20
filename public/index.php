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

// Si el usuario no está autenticado, redirigir al login
// EXCEPTO para el controlador Auth (login, registro, OAuth, etc.)
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

if (!$isAuthenticated && strtolower($controllerName) !== 'auth') {
    header('Location: ' . APP_URL . '/?url=auth/login');
    exit;
}

// Convertir guiones a guiones bajos (google-login -> google_login)
$action = str_replace('-', '_', $action);

// DEBUG: Mostrar qué se va a ejecutar
if (strtolower($controllerName) === 'auth' && $action === 'google_login') {
    echo '<div style="background: orange; padding: 30px; font-size: 20px;">';
    echo '<h1>DEBUG ANTES DE EJECUTAR MÉTODO</h1>';
    echo '<p>Controlador: ' . $controllerName . '</p>';
    echo '<p>Archivo: ' . $controllerFile . '</p>';
    echo '<p>¿Existe archivo? ' . (file_exists($controllerFile) ? 'SÍ' : 'NO') . '</p>';
    echo '<p>Acción: ' . $action . '</p>';
    echo '<p>Clase: ' . $controllerName . 'Controller</p>';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $className = $controllerName . 'Controller';
        echo '<p>¿Existe clase? ' . (class_exists($className) ? 'SÍ' : 'NO') . '</p>';
        if (class_exists($className)) {
            $controller = new $className();
            echo '<p>¿Existe método ' . $action . '? ' . (method_exists($controller, $action) ? 'SÍ' : 'NO') . '</p>';
        }
    }
    echo '</div>';
    sleep(3);
}

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
