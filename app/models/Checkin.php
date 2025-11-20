<?php

class Checkin {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Registrar un check-in
     */
    public function registrar($inscripcion_id, $fecha_checkin = null) {
        $fecha = $fecha_checkin ?? date('Y-m-d');
        $hora = date('H:i:s');
        
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO checkins (inscripcion_id, fecha_checkin, hora_checkin)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iss", $inscripcion_id, $fecha, $hora);
            return $stmt->execute();
        } catch (Exception $e) {
            // Si ya existe un check-in para esta fecha, retornar false
            return false;
        }
    }
    
    /**
     * Verificar si ya existe check-in para una inscripción en una fecha
     */
    public function existeCheckin($inscripcion_id, $fecha = null) {
        $fecha = $fecha ?? date('Y-m-d');
        
        $stmt = $this->conn->prepare("
            SELECT id FROM checkins 
            WHERE inscripcion_id = ? AND fecha_checkin = ?
        ");
        $stmt->bind_param("is", $inscripcion_id, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    /**
     * Obtener todos los check-ins de una inscripción
     */
    public function getByInscripcion($inscripcion_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM checkins 
            WHERE inscripcion_id = ?
            ORDER BY fecha_checkin ASC
        ");
        $stmt->bind_param("i", $inscripcion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener check-ins de un evento por fecha
     */
    public function getByEventoYFecha($evento_id, $fecha = null) {
        $fecha = $fecha ?? date('Y-m-d');
        
        $stmt = $this->conn->prepare("
            SELECT c.*, i.participante_id, i.evento_id,
                   p.nombre_completo, p.rut, p.email,
                   inst.nombre as institucion_nombre
            FROM checkins c
            INNER JOIN inscripciones i ON c.inscripcion_id = i.id
            INNER JOIN participantes p ON i.participante_id = p.id
            LEFT JOIN instituciones inst ON p.institucion_id = inst.id
            WHERE i.evento_id = ? AND c.fecha_checkin = ?
            ORDER BY c.hora_checkin DESC
        ");
        $stmt->bind_param("is", $evento_id, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Contar check-ins de un evento por fecha
     */
    public function contarByEventoYFecha($evento_id, $fecha = null) {
        $fecha = $fecha ?? date('Y-m-d');
        
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM checkins c
            INNER JOIN inscripciones i ON c.inscripcion_id = i.id
            WHERE i.evento_id = ? AND c.fecha_checkin = ?
        ");
        $stmt->bind_param("is", $evento_id, $fecha);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Obtener resumen de check-ins por participante en un evento
     */
    public function getResumenByEvento($evento_id) {
        $stmt = $this->conn->prepare("
            SELECT i.id as inscripcion_id, i.participante_id,
                   p.nombre_completo, p.rut,
                   COUNT(c.id) as total_checkins,
                   GROUP_CONCAT(c.fecha_checkin ORDER BY c.fecha_checkin ASC SEPARATOR ', ') as fechas_checkin
            FROM inscripciones i
            INNER JOIN participantes p ON i.participante_id = p.id
            LEFT JOIN checkins c ON i.id = c.inscripcion_id
            WHERE i.evento_id = ?
            GROUP BY i.id, i.participante_id, p.nombre_completo, p.rut
            ORDER BY p.nombre_completo ASC
        ");
        $stmt->bind_param("i", $evento_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Eliminar check-in
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM checkins WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
