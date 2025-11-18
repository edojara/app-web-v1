-- Migración: Agregar tabla de participantes
-- 17 de noviembre de 2025

CREATE TABLE IF NOT EXISTS participantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    institucion_id INT NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    rut VARCHAR(12) NOT NULL,
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (institucion_id) REFERENCES instituciones(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rut (rut),
    INDEX idx_institucion (institucion_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificar creación
SELECT COUNT(*) as total_participantes FROM participantes;
DESCRIBE participantes;
