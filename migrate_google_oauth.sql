-- Script para agregar soporte a autenticación con Google OAuth2

USE app_web_db;

-- Agregar columnas para OAuth2 a la tabla users (si no existen)
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS auth_type ENUM('local', 'google') DEFAULT 'local' COMMENT 'Tipo de autenticación: local o google',
ADD COLUMN IF NOT EXISTS google_id VARCHAR(255) UNIQUE COMMENT 'ID de Google para OAuth2',
ADD COLUMN IF NOT EXISTS password_is_nullable BOOLEAN DEFAULT FALSE COMMENT 'Indicar si la contraseña es opcional',
MODIFY COLUMN password VARCHAR(255) COMMENT 'Contraseña (puede ser NULL para OAuth2)';

-- Hacer la contraseña nullable para usuarios con OAuth2
ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NULL;

-- Crear índice para búsquedas por google_id
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_google_id (google_id);

-- Crear tabla de sesiones de OAuth2 para seguridad (CSRF tokens)
CREATE TABLE IF NOT EXISTS oauth_states (
    id INT PRIMARY KEY AUTO_INCREMENT,
    state_token VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_state_token (state_token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificar cambios
DESCRIBE users;
