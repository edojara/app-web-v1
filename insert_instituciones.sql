-- Insertar instituciones de ejemplo
-- 17 de noviembre de 2025

INSERT INTO instituciones (nombre, direccion, ciudad, estado, created_at, updated_at) VALUES
('Universidad de Chile', 'Av. Libertador Bernardo O''Higgins 1058', 'Santiago', 'activa', NOW(), NOW()),
('Pontificia Universidad Católica de Chile', 'Av. Libertador Bernardo O''Higgins 340', 'Santiago', 'activa', NOW(), NOW()),
('Universidad de Santiago de Chile', 'Av. Libertador Bernardo O''Higgins 3363', 'Santiago', 'activa', NOW(), NOW()),
('Universidad de Concepción', 'Víctor Lamas 1290', 'Concepción', 'activa', NOW(), NOW()),
('Universidad Técnica Federico Santa María', 'Av. España 1680', 'Valparaíso', 'activa', NOW(), NOW()),
('Universidad Austral de Chile', 'Av. Los Pinos s/n', 'Valdivia', 'activa', NOW(), NOW()),
('Universidad de Valparaíso', 'Blanco 951', 'Valparaíso', 'activa', NOW(), NOW()),
('Universidad Católica del Norte', 'Av. Angamos 0610', 'Antofagasta', 'activa', NOW(), NOW()),
('Universidad de La Frontera', 'Av. Francisco Salazar 01145', 'Temuco', 'activa', NOW(), NOW()),
('Universidad de Talca', 'Av. Lircay s/n', 'Talca', 'activa', NOW(), NOW()),
('Universidad de Antofagasta', 'Av. Angamos 601', 'Antofagasta', 'activa', NOW(), NOW()),
('Universidad Católica de Valparaíso', 'Av. Brasil 2950', 'Valparaíso', 'activa', NOW(), NOW()),
('Universidad de Tarapacá', 'Av. 18 de Septiembre 2222', 'Arica', 'activa', NOW(), NOW()),
('Universidad del Bío-Bío', 'Av. Collao 1202', 'Concepción', 'activa', NOW(), NOW()),
('Universidad de Magallanes', 'Av. Bulnes 01855', 'Punta Arenas', 'activa', NOW(), NOW());

-- Verificar inserción
SELECT COUNT(*) as total_instituciones FROM instituciones;
