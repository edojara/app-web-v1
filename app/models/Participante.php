<?php
class Participante {
    private $conn;
    private $table = 'participantes';

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Obtener todos los participantes con información de institución
     */
    public function getAll() {
        $query = "SELECT p.*, i.nombre as institucion_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN instituciones i ON p.institucion_id = i.id
                  ORDER BY p.created_at DESC";
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Obtener un participante por ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT p.*, i.nombre as institucion_nombre 
                                       FROM " . $this->table . " p
                                       LEFT JOIN instituciones i ON p.institucion_id = i.id
                                       WHERE p.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Crear un nuevo participante
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " 
                                       (institucion_id, nombre_completo, rut, telefono, email) 
                                       VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param("issss", 
            $data['institucion_id'],
            $data['nombre_completo'],
            $data['rut'],
            $data['telefono'],
            $data['email']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    /**
     * Actualizar un participante
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " 
                                       SET institucion_id = ?, 
                                           nombre_completo = ?, 
                                           rut = ?, 
                                           telefono = ?,
                                           email = ?
                                       WHERE id = ?");
        
        $stmt->bind_param("issssi",
            $data['institucion_id'],
            $data['nombre_completo'],
            $data['rut'],
            $data['telefono'],
            $data['email'],
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Eliminar un participante
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Verificar si un RUT ya existe (excluyendo un ID específico)
     */
    public function rutExists($rut, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE rut = ? AND id != ?");
            $stmt->bind_param("si", $rut, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE rut = ?");
            $stmt->bind_param("s", $rut);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    /**
     * Obtener todos los participantes de una institución específica
     */
    public function getByInstitucion($institucionId) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " 
                                       WHERE institucion_id = ? 
                                       ORDER BY nombre_completo ASC");
        $stmt->bind_param("i", $institucionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
