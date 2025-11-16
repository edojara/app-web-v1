<?php
/**
 * Punto de entrada principal de la aplicación
 */

// Cargar configuración
require_once dirname(dirname(__FILE__)) . '/config/config.php';

// Obtener la URL solicitada
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Obtener el controlador (por defecto: Home)
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Home';
$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . 'Controller.php';

// Obtener la acción (por defecto: index)
$action = isset($url[1]) ? $url[1] : 'index';

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
