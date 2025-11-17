-- ============================================
-- MIGRACIÓN: Sistema de Instituciones y Auditoría de Aplicación
-- Fecha: 2025-11-17
-- Descripción: Tablas para gestión de instituciones académicas con contactos
--              y sistema de auditoría separado para la aplicación
-- ============================================

-- Tabla de Instituciones
CREATE TABLE IF NOT EXISTS instituciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_nombre (nombre),
    INDEX idx_ciudad (ciudad),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Contactos de Instituciones
CREATE TABLE IF NOT EXISTS contactos_institucion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institucion_id INT NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    ocupacion VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_institucion (institucion_id),
    INDEX idx_nombre (nombre_completo),
    
    FOREIGN KEY (institucion_id) REFERENCES instituciones(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Auditoría de Aplicación (separada de auditoría de usuarios)
CREATE TABLE IF NOT EXISTS auditoria_app (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modulo VARCHAR(50) NOT NULL,                -- instituciones, programas, evaluaciones, etc.
    accion VARCHAR(50) NOT NULL,                -- crear, editar, eliminar
    registro_id INT,                            -- ID del registro afectado
    registro_nombre VARCHAR(255),               -- Nombre/descripción del registro
    usuario_id INT NOT NULL,                    -- Usuario que realizó la acción
    cambios TEXT,                               -- JSON con los cambios
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_modulo (modulo),
    INDEX idx_accion (accion),
    INDEX idx_usuario (usuario_id),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
