-- Script para normalizar las fechas de eventos a medianoche (00:00:00)
-- Esto asegura que todas las fechas sean consistentes y no incluyan horas específicas

-- Actualizar fecha_inicio para que tenga hora 00:00:00
UPDATE eventos 
SET fecha_inicio = DATE(fecha_inicio);

-- Actualizar fecha_termino para que tenga hora 00:00:00
UPDATE eventos 
SET fecha_termino = DATE(fecha_termino);

-- Verificar los cambios
SELECT id, nombre, fecha_inicio, fecha_termino FROM eventos;
