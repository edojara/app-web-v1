-- Script para agregar campos de último login y crear tabla de auditoría

USE app_web_db;

-- Agregar columna de último login a la tabla users
SET @columnname = "last_login";
SET @tablename = "users";
SET @dbname = DATABASE();
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE (table_name = @tablename) AND (table_schema = @dbname) AND (column_name = @columnname)) > 0,
    "SELECT 1",
    CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " TIMESTAMP NULL COMMENT 'Último login exitoso'")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Crear tabla de auditoría para logs de cambios en usuarios
CREATE TABLE IF NOT EXISTS user_audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_user_id INT,
    admin_user_name VARCHAR(100),
    target_user_id INT NOT NULL,
    target_user_name VARCHAR(100),
    action VARCHAR(50) NOT NULL COMMENT 'create, update, delete',
    changes JSON COMMENT 'Cambios realizados en JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin (admin_user_id),
    INDEX idx_target (target_user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at),
    FOREIGN KEY (admin_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (target_user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificar cambios
SELECT 'Migración completada. Estructura actualizada:' as status;
DESCRIBE users;
