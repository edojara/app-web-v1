-- Insertar contactos para las instituciones
-- 17 de noviembre de 2025

INSERT INTO contactos_institucion (institucion_id, nombre_completo, ocupacion, telefono, email, created_at, updated_at) VALUES
-- Universidad de Chile (id: 1)
(1, 'Dr. Roberto Martínez Silva', 'Director de Acreditación', '+56 2 2978 6000', 'rmartinez@uchile.cl', NOW(), NOW()),
(1, 'Dra. Carolina Pérez López', 'Jefa de Calidad Académica', '+56 2 2978 6001', 'cperez@uchile.cl', NOW(), NOW()),

-- Pontificia Universidad Católica de Chile (id: 2)
(2, 'Dr. Andrés González Ruiz', 'Vicerrector Académico', '+56 2 2354 4000', 'agonzalez@uc.cl', NOW(), NOW()),
(2, 'Dra. María José Fernández', 'Directora de Evaluación', '+56 2 2354 4001', 'mjfernandez@uc.cl', NOW(), NOW()),

-- Universidad de Santiago de Chile (id: 3)
(3, 'Dr. Luis Contreras Mora', 'Director de Calidad', '+56 2 2718 0001', 'lcontreras@usach.cl', NOW(), NOW()),

-- Universidad de Concepción (id: 4)
(4, 'Dra. Patricia Rojas Campos', 'Coordinadora de Acreditación', '+56 41 220 3000', 'projas@udec.cl', NOW(), NOW()),
(4, 'Dr. Miguel Ángel Torres', 'Analista de Calidad', '+56 41 220 3001', 'mtorres@udec.cl', NOW(), NOW()),

-- Universidad Técnica Federico Santa María (id: 5)
(5, 'Ing. Ricardo Valenzuela López', 'Jefe de Aseguramiento de Calidad', '+56 32 265 4000', 'rvalenzuela@usm.cl', NOW(), NOW()),

-- Universidad Austral de Chile (id: 6)
(6, 'Dra. Sandra Muñoz Díaz', 'Directora de Calidad Institucional', '+56 63 222 1000', 'smunoz@uach.cl', NOW(), NOW()),
(6, 'Dr. Felipe Escobar Núñez', 'Coordinador de Evaluación', '+56 63 222 1001', 'fescobar@uach.cl', NOW(), NOW()),

-- Universidad de Valparaíso (id: 7)
(7, 'Dra. Claudia Ramírez Soto', 'Jefa de Acreditación', '+56 32 250 8000', 'claudia.ramirez@uv.cl', NOW(), NOW()),

-- Universidad Católica del Norte (id: 8)
(8, 'Dr. Jorge Morales Castro', 'Director de Calidad', '+56 55 235 5000', 'jmorales@ucn.cl', NOW(), NOW()),
(8, 'Dra. Pamela Silva Rojas', 'Analista de Procesos', '+56 55 235 5001', 'psilva@ucn.cl', NOW(), NOW()),

-- Universidad de La Frontera (id: 9)
(9, 'Dr. Cristian Herrera Fuentes', 'Coordinador de Acreditación', '+56 45 232 5000', 'cristian.herrera@ufrontera.cl', NOW(), NOW()),

-- Universidad de Talca (id: 10)
(10, 'Dra. Lorena Castro Bravo', 'Directora de Calidad Académica', '+56 71 220 0000', 'lcastro@utalca.cl', NOW(), NOW()),
(10, 'Dr. Rodrigo Vargas Pinto', 'Jefe de Evaluación Institucional', '+56 71 220 0001', 'rvargas@utalca.cl', NOW(), NOW()),

-- Universidad de Antofagasta (id: 11)
(11, 'Dra. Elena Gutiérrez Sáez', 'Coordinadora de Calidad', '+56 55 263 7000', 'egutierrez@uantof.cl', NOW(), NOW()),

-- Universidad Católica de Valparaíso (id: 12)
(12, 'Dr. Sebastián Flores Medina', 'Director de Aseguramiento de Calidad', '+56 32 227 3000', 'sebastian.flores@pucv.cl', NOW(), NOW()),
(12, 'Dra. Valentina Ortiz Lagos', 'Analista de Acreditación', '+56 32 227 3001', 'valentina.ortiz@pucv.cl', NOW(), NOW()),

-- Universidad de Tarapacá (id: 13)
(13, 'Dr. Manuel Cortés Yáñez', 'Jefe de Calidad Institucional', '+56 58 220 5000', 'mcortes@uta.cl', NOW(), NOW()),

-- Universidad del Bío-Bío (id: 14)
(14, 'Dra. Gabriela Navarro Vera', 'Directora de Evaluación', '+56 42 245 3000', 'gnavarro@ubiobio.cl', NOW(), NOW()),
(14, 'Dr. Ignacio Sepúlveda Rojas', 'Coordinador de Procesos', '+56 42 245 3001', 'isepulveda@ubiobio.cl', NOW(), NOW()),

-- Universidad de Magallanes (id: 15)
(15, 'Dra. Mónica Barrientos Cárdenas', 'Jefa de Acreditación', '+56 61 220 7000', 'monica.barrientos@umag.cl', NOW(), NOW());

-- Verificar inserción
SELECT 
    i.nombre as institucion,
    COUNT(c.id) as total_contactos
FROM instituciones i
LEFT JOIN contactos_institucion c ON i.id = c.institucion_id
GROUP BY i.id, i.nombre
ORDER BY i.nombre;
