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

// DEBUG COMPLETO - SIEMPRE para cualquier URL con parámetro
$urlOriginal = $url;
$url = explode('/', $url);

// Obtener el controlador (por defecto: Home)
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Home';
$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . 'Controller.php';

// Obtener la acción (por defecto: index)
$action = isset($url[1]) ? $url[1] : 'index';

// Mostrar debug SIEMPRE si hay parámetro url
if (isset($_GET['url'])) {
    echo "<!DOCTYPE html><html><body style='font-family: monospace; padding: 20px;'>";
    echo "<h1 style='background: #ffeb3b; padding: 10px;'>🔍 DEBUG COMPLETO - Routing</h1>";
    echo "<div style='background: #f5f5f5; padding: 15px; margin: 10px 0;'>";
    echo "<h2>1. Parámetros recibidos</h2>";
    echo "<p><strong>\$_GET['url']:</strong> '<span style='color: blue;'>" . ($_GET['url'] ?? 'NO DEFINIDO') . "</span>'</p>";
    echo "<p><strong>\$urlOriginal (después de trim):</strong> '<span style='color: blue;'>$urlOriginal</span>'</p>";
    echo "</div>";
    
    echo "<div style='background: #e3f2fd; padding: 15px; margin: 10px 0;'>";
    echo "<h2>2. Después de explode('/', \$url)</h2>";
    echo "<p><strong>\$url array:</strong> <pre style='background: white; padding: 10px;'>" . print_r($url, true) . "</pre></p>";
    echo "<p><strong>\$url[0]:</strong> '<span style='color: green;'>" . ($url[0] ?? 'NO DEFINIDO') . "</span>'</p>";
    echo "<p><strong>\$url[1]:</strong> '<span style='color: green;'>" . ($url[1] ?? 'NO DEFINIDO') . "</span>'</p>";
    echo "</div>";
    
    echo "<div style='background: #fff3e0; padding: 15px; margin: 10px 0;'>";
    echo "<h2>3. Routing calculado</h2>";
    echo "<p><strong>\$controllerName:</strong> '<span style='color: purple; font-weight: bold;'>$controllerName</span>'</p>";
    echo "<p><strong>strtolower(\$controllerName):</strong> '<span style='color: purple; font-weight: bold;'>" . strtolower($controllerName) . "</span>'</p>";
    echo "<p><strong>Comparación: strtolower('$controllerName') === 'auth':</strong> <span style='font-size: 20px; font-weight: bold; color: " . (strtolower($controllerName) === 'auth' ? 'green' : 'red') . ";'>" . (strtolower($controllerName) === 'auth' ? 'TRUE ✓' : 'FALSE ✗') . "</span></p>";
    echo "<p><strong>\$action:</strong> '<span style='color: purple;'>$action</span>'</p>";
    echo "</div>";
    
    echo "<div style='background: #ffcdd2; padding: 15px; margin: 10px 0;'>";
    echo "<h2>4. Estado de autenticación</h2>";
    $isAuth = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    echo "<p><strong>¿Usuario autenticado?:</strong> <span style='font-weight: bold; color: " . ($isAuth ? 'green' : 'red') . ";'>" . ($isAuth ? 'SÍ' : 'NO') . "</span></p>";
    echo "<p><strong>¿Se va a redirigir?:</strong> <span style='font-weight: bold; color: " . (!$isAuth && strtolower($controllerName) !== 'auth' ? 'red' : 'green') . ";'>" . (!$isAuth && strtolower($controllerName) !== 'auth' ? 'SÍ - VA A REDIRIGIR AL LOGIN' : 'NO - Dejará pasar') . "</span></p>";
    echo "</div>";
    
    echo "<hr style='margin: 20px 0; border: 2px solid #000;'>";
    echo "<p style='font-size: 16px; font-weight: bold;'>⏱️ Continuando en 10 segundos...</p>";
    echo "</body></html>";
    flush();
    sleep(10);
}

// Si el usuario no está autenticado, redirigir al login
// EXCEPTO para el controlador Auth (login, registro, OAuth, etc.)
$isAuthenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// DEBUG ADICIONAL
echo "<div style='background: yellow; padding: 20px; margin: 20px; border: 5px solid red;'>";
echo "<h2>DEBUG ANTES DE VERIFICAR AUTH</h2>";
echo "<p>\$controllerName = '<strong>" . $controllerName . "</strong>'</p>";
echo "<p>strtolower(\$controllerName) = '<strong>" . strtolower($controllerName) . "</strong>'</p>";
echo "<p>strtolower(\$controllerName) === 'auth' = " . (strtolower($controllerName) === 'auth' ? '<strong style=\"color:green;\">TRUE</strong>' : '<strong style=\"color:red;\">FALSE</strong>') . "</p>";
echo "<p>strtolower(\$controllerName) !== 'auth' = " . (strtolower($controllerName) !== 'auth' ? '<strong style=\"color:red;\">TRUE (va a redirigir)</strong>' : '<strong style=\"color:green;\">FALSE (NO redirige)</strong>') . "</p>";
echo "<p>\$isAuthenticated = " . ($isAuthenticated ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>Condición completa: !isAuthenticated && !== 'auth' = " . (!$isAuthenticated && strtolower($controllerName) !== 'auth' ? '<strong style=\"color:red; font-size:24px;\">TRUE - ENTRARÁ AL IF</strong>' : '<strong style=\"color:green; font-size:24px;\">FALSE - NO ENTRARÁ AL IF</strong>') . "</p>";
echo "</div>";
flush();
sleep(5);

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
