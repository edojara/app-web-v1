<?php
// Página de prueba independiente para Google OAuth
require_once dirname(dirname(__FILE__)) . '/config/config.php';

// Simular lo que hace index.php
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = rtrim($url, '/');
$url = trim($url);
$url = explode('/', $url);

$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Home';
$action = isset($url[1]) ? $url[1] : 'index';

echo "<!DOCTYPE html>";
echo "<html><head><title>Test Google OAuth</title></head><body>";
echo "<h1>Test de redirección a Google OAuth</h1>";
echo "<p>APP_URL: " . APP_URL . "</p>";
echo "<p>URL GET param: " . ($_GET['url'] ?? 'NO DEFINIDO') . "</p>";
echo "<p>Controller Name: " . $controllerName . "</p>";
echo "<p>Controller Name (lowercase): " . strtolower($controllerName) . "</p>";
echo "<p>Action: " . $action . "</p>";
echo "<p>¿Es 'auth'?: " . (strtolower($controllerName) === 'auth' ? 'SÍ' : 'NO') . "</p>";
echo "<hr>";
echo "<a href='" . APP_URL . "/?url=auth/google-login' style='font-size: 20px; color: blue;'>Click aquí para ir a Google OAuth</a>";
echo "<hr>";
echo "<button onclick='window.location.href=\"" . APP_URL . "/?url=auth/google-login\"'>Botón con JavaScript</button>";
echo "</body></html>";
?>
