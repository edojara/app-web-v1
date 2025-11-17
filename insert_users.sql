-- Script para insertar usuarios de prueba con contraseña 'secret123'
-- Hash generado con: password_hash("secret123", PASSWORD_BCRYPT)

USE app_web_db;

-- Limpiar usuarios anteriores (opcional)
-- DELETE FROM users WHERE email IN ('admin@example.com', 'lector@example.com', 'acreditador@example.com', 'informes@example.com');

-- Insertar usuarios de prueba para cada rol
INSERT INTO users (name, email, password, role_id, estado) VALUES
('Admin Usuario', 'admin@example.com', '$2y$10$2Qa.bUxkDH47xYzCxcDp2.gxpEqBGhQWfIOOqATefRwZjDyOa1xC2', 1, 'activo'),
('Lector Usuario', 'lector@example.com', '$2y$10$2Qa.bUxkDH47xYzCxcDp2.gxpEqBGhQWfIOOqATefRwZjDyOa1xC2', 2, 'activo'),
('Acreditador Usuario', 'acreditador@example.com', '$2y$10$2Qa.bUxkDH47xYzCxcDp2.gxpEqBGhQWfIOOqATefRwZjDyOa1xC2', 3, 'activo'),
('Informes Usuario', 'informes@example.com', '$2y$10$2Qa.bUxkDH47xYzCxcDp2.gxpEqBGhQWfIOOqATefRwZjDyOa1xC2', 4, 'activo')
ON DUPLICATE KEY UPDATE password=VALUES(password), role_id=VALUES(role_id), estado=VALUES(estado);

-- Verificar inserción
SELECT id, name, email, role_id, estado FROM users WHERE email IN ('admin@example.com', 'lector@example.com', 'acreditador@example.com', 'informes@example.com') ORDER BY id;
