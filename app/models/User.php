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
            $query = "INSERT INTO " . $this->table . " (name, email, password, role_id, estado) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $estado = 'activo';
            $stmt->bind_param("sssss", $data['name'], $data['email'], password_hash($data['password'], PASSWORD_BCRYPT), $data['role_id'], $estado);
            
            return $stmt->execute();
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return false;
        }
    }
    
    /**
     * Obtener usuario con su rol
     */
    public function getWithRole($id) {
        try {
            $query = "SELECT u.*, r.nombre as role_nombre, r.descripcion as role_descripcion 
                     FROM " . $this->table . " u 
                     LEFT JOIN roles r ON u.role_id = r.id 
                     WHERE u.id = ?";
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
     * Obtener todos los usuarios con sus roles
     */
    public function getAllWithRoles() {
        try {
            $query = "SELECT u.*, r.nombre as role_nombre, r.descripcion as role_descripcion 
                     FROM " . $this->table . " u 
                     LEFT JOIN roles r ON u.role_id = r.id 
                     ORDER BY u.name ASC";
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
     * Actualizar usuario
     */
    public function update($id, $data) {
        try {
            $query = "UPDATE " . $this->table . " SET name = ?, email = ?, role_id = ?, estado = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $data['name'], $data['email'], $data['role_id'], $data['estado'], $id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return false;
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            
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
