-- Migración: Agregar campo cargo a tabla participantes
-- 21 de noviembre de 2025

ALTER TABLE participantes 
ADD COLUMN cargo VARCHAR(100) AFTER email;

-- Verificar cambio
DESCRIBE participantes;
