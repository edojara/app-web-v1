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
     * Obtener todas las instituciones con el conteo de contactos y participantes
     */
    public function getAll() {
        $sql = "SELECT i.*, 
                COUNT(DISTINCT c.id) as total_contactos,
                COUNT(DISTINCT p.id) as total_participantes
                FROM {$this->table} i 
                LEFT JOIN contactos_institucion c ON i.id = c.institucion_id 
                LEFT JOIN participantes p ON i.id = p.institucion_id
                GROUP BY i.id 
                ORDER BY i.nombre ASC";
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
     * Obtener una institución por nombre exacto
     */
    public function getByNombre($nombre) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE nombre = ? AND estado = 'activa' LIMIT 1");
        $stmt->bind_param("s", $nombre);
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
    
    /**
     * Obtener participantes de una institución
     */
    public function getParticipantes($institucionId) {
        $stmt = $this->conn->prepare("
            SELECT * FROM participantes 
            WHERE institucion_id = ? 
            ORDER BY nombre_completo ASC
        ");
        $stmt->bind_param("i", $institucionId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Agregar participante
     */
    public function addParticipante($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO participantes (institucion_id, nombre_completo, rut, telefono) 
            VALUES (?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "isss",
            $data['institucion_id'],
            $data['nombre_completo'],
            $data['rut'],
            $data['telefono']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    /**
     * Actualizar participante
     */
    public function updateParticipante($id, $data) {
        $stmt = $this->conn->prepare("
            UPDATE participantes 
            SET nombre_completo = ?, rut = ?, telefono = ?
            WHERE id = ?
        ");
        
        $stmt->bind_param(
            "sssi",
            $data['nombre_completo'],
            $data['rut'],
            $data['telefono'],
            $id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Eliminar participante
     */
    public function deleteParticipante($id) {
        $stmt = $this->conn->prepare("DELETE FROM participantes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Obtener participante por ID
     */
    public function getParticipanteById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM participantes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
