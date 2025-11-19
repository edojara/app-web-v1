<?php

class Inscripcion {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Obtener todas las inscripciones
     */
    public function getAll() {
        $sql = "SELECT i.*, 
                       e.nombre as evento_nombre,
                       p.nombre_completo as participante_nombre,
                       u.username as usuario_nombre
                FROM inscripciones i
                LEFT JOIN eventos e ON i.evento_id = e.id
                LEFT JOIN participantes p ON i.participante_id = p.id
                LEFT JOIN users u ON i.user_id = u.id
                ORDER BY i.fecha_inscripcion DESC";
        
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener inscripciones por evento
     */
    public function getByEvento($evento_id) {
        $sql = "SELECT i.*, 
                       p.nombre_completo, p.rut, p.email, p.telefono,
                       inst.nombre as institucion_nombre,
                       u.username as usuario_nombre
                FROM inscripciones i
                LEFT JOIN participantes p ON i.participante_id = p.id
                LEFT JOIN instituciones inst ON p.institucion_id = inst.id
                LEFT JOIN users u ON i.user_id = u.id
                WHERE i.evento_id = ? AND i.estado = 'inscrito'
                ORDER BY i.fecha_inscripcion DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $evento_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener inscripciones por participante
     */
    public function getByParticipante($participante_id) {
        $sql = "SELECT i.*, 
                       e.nombre as evento_nombre,
                       e.fecha_inicio, e.fecha_termino, e.lugar,
                       u.username as usuario_nombre
                FROM inscripciones i
                LEFT JOIN eventos e ON i.evento_id = e.id
                LEFT JOIN users u ON i.user_id = u.id
                WHERE i.participante_id = ? AND i.estado = 'inscrito'
                ORDER BY e.fecha_inicio DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $participante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener una inscripción por ID
     */
    public function getById($id) {
        $sql = "SELECT i.*, 
                       e.nombre as evento_nombre,
                       p.nombre_completo as participante_nombre,
                       u.username as usuario_nombre
                FROM inscripciones i
                LEFT JOIN eventos e ON i.evento_id = e.id
                LEFT JOIN participantes p ON i.participante_id = p.id
                LEFT JOIN users u ON i.user_id = u.id
                WHERE i.id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Crear nueva inscripción
     */
    public function create($data) {
        $sql = "INSERT INTO inscripciones (evento_id, participante_id, user_id, observaciones) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiis", 
            $data['evento_id'],
            $data['participante_id'],
            $data['user_id'],
            $data['observaciones']
        );
        
        return $stmt->execute();
    }
    
    /**
     * Verificar si existe inscripción
     */
    public function exists($evento_id, $participante_id) {
        $sql = "SELECT id FROM inscripciones 
                WHERE evento_id = ? AND participante_id = ? AND estado = 'inscrito'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $evento_id, $participante_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    /**
     * Cancelar inscripción
     */
    public function cancel($id) {
        $sql = "UPDATE inscripciones SET estado = 'cancelado' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Eliminar inscripción
     */
    public function delete($id) {
        $sql = "DELETE FROM inscripciones WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Contar inscripciones por evento
     */
    public function countByEvento($evento_id) {
        $sql = "SELECT COUNT(*) as total FROM inscripciones 
                WHERE evento_id = ? AND estado = 'inscrito'";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $evento_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Inscribir múltiples participantes
     */
    public function inscribirMultiples($evento_id, $participante_ids, $user_id, $observaciones = '') {
        $success = 0;
        $errors = [];
        
        foreach ($participante_ids as $participante_id) {
            // Verificar si ya está inscrito
            if ($this->exists($evento_id, $participante_id)) {
                $errors[] = "El participante ID $participante_id ya está inscrito en este evento";
                continue;
            }
            
            $data = [
                'evento_id' => $evento_id,
                'participante_id' => $participante_id,
                'user_id' => $user_id,
                'observaciones' => $observaciones
            ];
            
            if ($this->create($data)) {
                $success++;
            } else {
                $errors[] = "Error al inscribir participante ID $participante_id";
            }
        }
        
        return [
            'success' => $success,
            'errors' => $errors
        ];
    }
}
