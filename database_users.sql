-- Script adicional para perfiles y permisos

-- Tabla de roles/perfiles
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar los 4 perfiles
INSERT INTO roles (nombre, descripcion) VALUES
('administrador', 'Acceso total a la plataforma'),
('lector', 'Puede visualizar información y reportes'),
('acreditador', 'Puede acreditar y validar información'),
('informes', 'Acceso a generación y visualización de informes');

-- Modificar tabla users para agregar rol_id
ALTER TABLE users ADD COLUMN role_id INT DEFAULT NULL;
ALTER TABLE users ADD COLUMN estado ENUM('activo', 'inactivo') DEFAULT 'activo';
ALTER TABLE users ADD CONSTRAINT fk_users_roles FOREIGN KEY (role_id) REFERENCES roles(id);
ALTER TABLE users ADD INDEX idx_role (role_id);

-- Actualizar usuarios existentes con roles
UPDATE users SET role_id = 1 WHERE id = 1; -- Admin
UPDATE users SET role_id = 2 WHERE id = 2; -- Lector
