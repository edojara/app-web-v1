<?php
/**
 * Modelo: Role
 * Descripción: Modelo para gestionar roles/perfiles
 */

class Role {
    
    private $conn;
    private $table = 'roles';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Obtener todos los roles
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY nombre ASC";
            $result = $this->conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            return [];
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return [];
        }
    }
    
    /**
     * Obtener rol por ID
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return null;
        }
    }
    
    /**
     * Obtener rol por nombre
     */
    public function getByName($nombre) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE nombre = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return null;
        }
    }
}
?>
