<?php
/**
 * Modelo: Institucion
 * Descripción: Modelo para gestionar instituciones académicas
 */

class Institucion {
    
    private $conn;
    private $table = 'instituciones';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Obtener todas las instituciones
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener instituciones activas
     */
    public function getActivas() {
        $sql = "SELECT * FROM {$this->table} WHERE estado = 'activa' ORDER BY nombre ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener una institución por ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Obtener institución con sus contactos
     */
    public function getWithContactos($id) {
        $institucion = $this->getById($id);
        if ($institucion) {
            $institucion['contactos'] = $this->getContactos($id);
        }
        return $institucion;
    }
    
    /**
     * Obtener contactos de una institución
     */
    public function getContactos($institucionId) {
        $stmt = $this->conn->prepare("
            SELECT * FROM contactos_institucion 
            WHERE institucion_id = ? 
            ORDER BY nombre_completo ASC
        ");
        $stmt->bind_param("i", $institucionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Crear una nueva institución
     */
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (nombre, direccion, ciudad, estado) 
            VALUES (?, ?, ?, ?)
        ");
        
        $estado = $data['estado'] ?? 'activa';
        
        $stmt->bind_param(
            "ssss",
            $data['nombre'],
            $data['direccion'],
            $data['ciudad'],
            $estado
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    /**
     * Actualizar una institución
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} 
            SET nombre = ?, direccion = ?, ciudad = ?, estado = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param(
            "ssssi",
            $data['nombre'],
            $data['direccion'],
            $data['ciudad'],
            $data['estado'],
            $id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar una institución
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Agregar contacto a una institución
     */
    public function addContacto($institucionId, $data) {
        $stmt = $this->conn->prepare("
            INSERT INTO contactos_institucion 
            (institucion_id, nombre_completo, ocupacion, telefono, email) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "issss",
            $institucionId,
            $data['nombre_completo'],
            $data['ocupacion'],
            $data['telefono'],
            $data['email']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    /**
     * Actualizar contacto
     */
    public function updateContacto($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE contactos_institucion 
            SET nombre_completo = ?, ocupacion = ?, telefono = ?, email = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param(
            "ssssi",
            $data['nombre_completo'],
            $data['ocupacion'],
            $data['telefono'],
            $data['email'],
            $id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar contacto
     */
    public function deleteContacto($id) {
        $stmt = $this->conn->prepare("DELETE FROM contactos_institucion WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Obtener contacto por ID
     */
    public function getContactoById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM contactos_institucion WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Contar instituciones
     */
    public function count() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM {$this->table}");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Buscar instituciones
     */
    public function search($term) {
        $stmt = $this->conn->prepare("
            SELECT * FROM {$this->table} 
            WHERE nombre LIKE ? OR ciudad LIKE ? OR direccion LIKE ?
            ORDER BY nombre ASC
        ");
        $searchTerm = "%{$term}%";
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
