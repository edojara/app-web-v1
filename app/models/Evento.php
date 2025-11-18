<?php
class Evento {
    private $conn;
    private $table = 'eventos';

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Obtener todos los eventos
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY fecha_inicio DESC";
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Obtener un evento por ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Crear un nuevo evento
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " 
                                       (nombre, descripcion, fecha_inicio, fecha_termino, lugar) 
                                       VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssss", 
            $data['nombre'],
            $data['descripcion'],
            $data['fecha_inicio'],
            $data['fecha_termino'],
            $data['lugar']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    /**
     * Actualizar un evento
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " 
                                       SET nombre = ?, 
                                           descripcion = ?,
                                           fecha_inicio = ?, 
                                           fecha_termino = ?, 
                                           lugar = ?
                                       WHERE id = ?");
        
        $stmt->bind_param("sssssi",
            $data['nombre'],
            $data['descripcion'],
            $data['fecha_inicio'],
            $data['fecha_termino'],
            $data['lugar'],
            $id
        );
        
        return $stmt->execute();
    }

    /**
     * Eliminar un evento
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Verificar si un evento ya existe por nombre
     */
    public function nombreExists($nombre, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE nombre = ? AND id != ?");
            $stmt->bind_param("si", $nombre, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM " . $this->table . " WHERE nombre = ?");
            $stmt->bind_param("s", $nombre);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
