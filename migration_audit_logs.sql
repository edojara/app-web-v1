-- ============================================
-- MIGRACIÓN: Sistema de Auditoría de Usuarios
-- Fecha: 2025-11-17
-- Descripción: Tabla para registrar todos los cambios realizados en usuarios
-- ============================================

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,                    -- ID del usuario que realizó la acción
    usuario_afectado_id INT DEFAULT NULL,       -- ID del usuario que fue afectado (NULL para acciones generales)
    accion VARCHAR(50) NOT NULL,                -- Tipo de acción: 'crear_usuario', 'editar_usuario', 'eliminar_usuario', etc.
    tabla_afectada VARCHAR(50) DEFAULT 'users', -- Tabla donde se realizó el cambio
    cambios TEXT,                                -- JSON con los cambios: {"antes": {...}, "despues": {...}}
    ip_address VARCHAR(45),                      -- Dirección IP del usuario que realizó la acción
    user_agent TEXT,                             -- Navegador/dispositivo utilizado
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuario_id (usuario_id),
    INDEX idx_usuario_afectado (usuario_afectado_id),
    INDEX idx_accion (accion),
    INDEX idx_created_at (created_at),
    
    FOREIGN KEY (usuario_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_afectado_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agregar algunos registros de ejemplo (opcional)
-- INSERT INTO audit_logs (usuario_id, usuario_afectado_id, accion, cambios, ip_address) 
-- VALUES (1, 2, 'editar_usuario', '{"antes": {"email": "old@example.com"}, "despues": {"email": "new@example.com"}}', '192.168.1.1');
