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

// DEBUG COMPLETO - Solo para URLs que contengan 'auth'
$urlOriginal = $url;
$url = explode('/', $url);

// Obtener el controlador (por defecto: Home)
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Home';
$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . 'Controller.php';

// Obtener la acción (por defecto: index)
$action = isset($url[1]) ? $url[1] : 'index';

// Mostrar debug completo
if (isset($_GET['url']) && strpos($_GET['url'], 'auth') !== false) {
    echo "<!DOCTYPE html><html><body>";
    echo "<h1>DEBUG COMPLETO - Google OAuth</h1>";
    echo "<h2>1. Parámetros recibidos</h2>";
    echo "<p><strong>\$_GET['url']:</strong> " . ($_GET['url'] ?? 'NO DEFINIDO') . "</p>";
    echo "<p><strong>\$urlOriginal:</strong> $urlOriginal</p>";
    
    echo "<h2>2. Después de explode</h2>";
    echo "<p><strong>\$url array:</strong> <pre>" . print_r($url, true) . "</pre></p>";
    echo "<p><strong>\$url[0]:</strong> " . ($url[0] ?? 'NO DEFINIDO') . "</p>";
    echo "<p><strong>\$url[1]:</strong> " . ($url[1] ?? 'NO DEFINIDO') . "</p>";
    
    echo "<h2>3. Routing</h2>";
    echo "<p><strong>\$controllerName:</strong> '$controllerName'</p>";
    echo "<p><strong>\$controllerName (lowercase):</strong> '" . strtolower($controllerName) . "'</p>";
    echo "<p><strong>¿Es 'auth'?:</strong> " . (strtolower($controllerName) === 'auth' ? 'SÍ ✓' : 'NO ✗') . "</p>";
    echo "<p><strong>\$action:</strong> $action</p>";
    
    echo "<h2>4. Autenticación</h2>";
    echo "<p><strong>¿Está autenticado?:</strong> " . (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? 'SÍ' : 'NO') . "</p>";
    
    echo "<hr>";
    echo "<p>Continuando en 5 segundos...</p>";
    echo "</body></html>";
    flush();
    sleep(5);
}

// Si el usuario no está autenticado, redirigir al login
// EXCEPTO para el controlador Auth (login, registro, OAuth, etc.)
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
if (!$isAuthenticated && strtolower($controllerName) !== 'auth') {
    echo "<p style='color: red;'><strong>REDIRIGIENDO AL LOGIN porque:</strong></p>";
    echo "<p>- No autenticado: " . (!$isAuthenticated ? 'SÍ' : 'NO') . "</p>";
    echo "<p>- Controlador no es auth: " . (strtolower($controllerName) !== 'auth' ? 'SÍ' : 'NO') . "</p>";
    flush();
    sleep(2);
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
