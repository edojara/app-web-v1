-- Tabla para registrar los check-ins de participantes en eventos
CREATE TABLE IF NOT EXISTS checkins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inscripcion_id INT NOT NULL,
    fecha_checkin DATE NOT NULL,
    hora_checkin TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (inscripcion_id) REFERENCES inscripciones(id) ON DELETE CASCADE,
    UNIQUE KEY unique_checkin_diario (inscripcion_id, fecha_checkin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para mejorar el rendimiento
CREATE INDEX idx_inscripcion_id ON checkins(inscripcion_id);
CREATE INDEX idx_fecha_checkin ON checkins(fecha_checkin);
