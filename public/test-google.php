<?php
// Página de prueba independiente para Google OAuth
require_once dirname(dirname(__FILE__)) . '/config/config.php';

echo "<!DOCTYPE html>";
echo "<html><head><title>Test Google OAuth</title></head><body>";
echo "<h1>Test de redirección a Google OAuth</h1>";
echo "<p>APP_URL: " . APP_URL . "</p>";
echo "<p>URL destino: " . APP_URL . "/?url=auth/google-login</p>";
echo "<hr>";
echo "<a href='" . APP_URL . "/?url=auth/google-login' style='font-size: 20px; color: blue;'>Click aquí para ir a Google OAuth</a>";
echo "<hr>";
echo "<button onclick='window.location.href=\"" . APP_URL . "/?url=auth/google-login\"'>Botón con JavaScript</button>";
echo "</body></html>";
?>
