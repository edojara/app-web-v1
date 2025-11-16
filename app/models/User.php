<?php
/**
 * Modelo: User
 * Descripción: Modelo para gestionar usuarios
 */

class User {
    
    private $conn;
    private $table = 'users';
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    /**
     * Obtener todos los usuarios
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table;
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
     * Obtener usuario por ID
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
     * Crear nuevo usuario
     */
    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table . " (name, email, password) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sss", $data['name'], $data['email'], password_hash($data['password'], PASSWORD_BCRYPT));
            
            return $stmt->execute();
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return false;
        }
    }
}
?>
