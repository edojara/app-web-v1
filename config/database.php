<?php
/**
 * Configuración de Base de Datos
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'app_web_db');

// Crear conexión
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    // Establecer charset
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
