-- Script para agregar soporte a autenticación con Google OAuth2

USE app_web_db;

-- Verificar si la columna auth_type ya existe, si no, agregarla
SET @dbname = DATABASE();
SET @tablename = "users";
SET @columnname = "auth_type";
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE (table_name = @tablename) AND (table_schema = @dbname) AND (column_name = @columnname)) > 0,
    "SELECT 1",
    CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " ENUM('local', 'google') DEFAULT 'local' COMMENT 'Tipo de autenticación: local o google'")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Agregar columna google_id si no existe
SET @columnname = "google_id";
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE (table_name = @tablename) AND (table_schema = @dbname) AND (column_name = @columnname)) > 0,
    "SELECT 1",
    CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " VARCHAR(255) UNIQUE COMMENT 'ID de Google para OAuth2'")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Hacer la contraseña nullable si aún no lo es
ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NULL;

-- Crear índice para búsquedas por google_id si no existe
SET @indexname = "idx_google_id";
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_name = @tablename AND table_schema = @dbname AND index_name = @indexname) > 0,
    "SELECT 1",
    CONCAT("ALTER TABLE ", @tablename, " ADD INDEX ", @indexname, " (google_id)")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

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
SELECT 'Migración completada. Estructura de users:' as status;
DESCRIBE users;
