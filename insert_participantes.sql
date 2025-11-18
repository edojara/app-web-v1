-- Insertar participantes de ejemplo para las instituciones
-- Distribución: 2-3 participantes por cada una de las 15 instituciones

INSERT INTO participantes (institucion_id, nombre_completo, rut, telefono) VALUES
-- Universidad de Chile (id: 1)
(1, 'María Fernanda González Pérez', '16.234.567-8', '+56 9 8765 4321'),
(1, 'Carlos Alberto Muñoz Silva', '18.456.789-0', '+56 9 7654 3210'),
(1, 'Ana Patricia Rojas Castro', '17.345.678-9', '+56 9 6543 2109'),

-- Pontificia Universidad Católica de Chile (id: 2)
(2, 'Jorge Luis Vargas Morales', '15.678.901-2', '+56 9 5432 1098'),
(2, 'Carmen Gloria Flores Torres', '19.234.567-3', '+56 9 4321 0987'),

-- Universidad de Santiago de Chile (id: 3)
(3, 'Ricardo Andrés Contreras Vega', '14.567.890-1', '+56 9 3210 9876'),
(3, 'Paulina Isabel Núñez Bravo', '20.123.456-4', '+56 9 2109 8765'),
(3, 'Fernando José Pinto Morales', '13.456.789-5', '+56 9 1098 7654'),

-- Universidad de Concepción (id: 4)
(4, 'Daniela Andrea Soto Ramírez', '16.789.012-6', '+56 9 9876 5432'),
(4, 'Miguel Ángel Herrera Díaz', '18.901.234-7', '+56 9 8765 4320'),

-- Universidad Austral de Chile (id: 5)
(5, 'Verónica Patricia Campos Gutiérrez', '15.234.567-8', '+56 9 7654 3219'),
(5, 'Roberto Carlos Medina Fuentes', '17.456.789-9', '+56 9 6543 2108'),
(5, 'Francisca Soledad Reyes Mendoza', '19.678.901-0', '+56 9 5432 1097'),

-- Universidad Técnica Federico Santa María (id: 6)
(6, 'Andrés Felipe Navarro Espinoza', '14.890.123-1', '+56 9 4321 0986'),
(6, 'Carolina Beatriz Figueroa Riquelme', '16.012.345-2', '+56 9 3210 9875'),

-- Universidad de Valparaíso (id: 7)
(7, 'Patricio Esteban Valdés Araya', '18.234.567-3', '+56 9 2109 8764'),
(7, 'Lorena Magdalena Cortés Salinas', '15.456.789-4', '+56 9 1098 7653'),

-- Universidad Católica de Valparaíso (id: 8)
(8, 'Gabriel Ignacio Sandoval Pérez', '17.678.901-5', '+56 9 9876 5431'),
(8, 'Marcela Alejandra Bustos González', '19.890.123-6', '+56 9 8765 4319'),
(8, 'Claudio Mauricio Peña Muñoz', '14.012.345-7', '+56 9 7654 3218'),

-- Universidad de Talca (id: 9)
(9, 'Valentina Andrea Silva Rojas', '16.234.567-8', '+56 9 6543 2107'),
(9, 'Diego Sebastián Ortiz Castro', '18.456.789-9', '+56 9 5432 1096'),

-- Universidad de La Serena (id: 10)
(10, 'Camila Fernanda Vásquez Torres', '15.678.901-0', '+56 9 4321 0985'),
(10, 'Rodrigo Antonio Moreno Vega', '17.890.123-1', '+56 9 3210 9874'),

-- Universidad de Antofagasta (id: 11)
(11, 'Bárbara Constanza Leiva Bravo', '19.012.345-2', '+56 9 2109 8763'),
(11, 'Héctor Alejandro Cruz Morales', '14.234.567-3', '+56 9 1098 7652'),
(11, 'Mónica Patricia Paredes Pinto', '16.456.789-4', '+56 9 9876 5430'),

-- Universidad Católica del Norte (id: 12)
(12, 'Esteban Francisco Vera Ramírez', '18.678.901-5', '+56 9 8765 4318'),
(12, 'Javiera Soledad Aguirre Díaz', '15.890.123-6', '+56 9 7654 3217'),

-- Universidad del Bío-Bío (id: 13)
(13, 'Matías Ignacio Robles Campos', '17.012.345-7', '+56 9 6543 2106'),
(13, 'Francisca Belén Santana Gutiérrez', '19.234.567-8', '+56 9 5432 1095'),

-- Universidad de Los Lagos (id: 14)
(14, 'Cristóbal Eduardo Lagos Fuentes', '14.456.789-9', '+56 9 4321 0984'),
(14, 'Natalia Carolina Cárdenas Mendoza', '16.678.901-0', '+56 9 3210 9873'),
(14, 'Sebastián Ignacio Alarcón Espinoza', '18.890.123-1', '+56 9 2109 8762'),

-- Universidad de Magallanes (id: 15)
(15, 'Isidora Macarena Riquelme Riquelme', '15.012.345-2', '+56 9 1098 7651'),
(15, 'Tomás Benjamín Salazar Araya', '17.234.567-3', '+56 9 9876 5429');

-- Verificar inserción
SELECT 'Total de participantes insertados:' as mensaje, COUNT(*) as total FROM participantes;

-- Mostrar resumen por institución
SELECT 
    i.nombre as institucion,
    COUNT(p.id) as cantidad_participantes
FROM instituciones i
LEFT JOIN participantes p ON i.id = p.institucion_id
GROUP BY i.id, i.nombre
ORDER BY i.nombre;
