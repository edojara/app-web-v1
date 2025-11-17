-- Crear base de datos
CREATE DATABASE IF NOT EXISTS app_web_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_web_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios de ejemplo
INSERT INTO users (name, email, password) VALUES
('Admin', 'admin@example.com', '$2y$10$YourHashedPasswordHere'),
('Usuario Prueba', 'usuario@example.com', '$2y$10$YourHashedPasswordHere');
