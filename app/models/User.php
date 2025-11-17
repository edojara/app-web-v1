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
     * Obtener usuario por email
     */
    public function getByEmail($email) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
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
     * Obtener usuario por Google ID
     */
    public function getByGoogleId($googleId) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE google_id = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $googleId);
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
            $authType = $data['auth_type'] ?? 'local';
            $query = "INSERT INTO " . $this->table . " (name, email, password, role_id, estado, auth_type) VALUES (?, ?, ?, ?, 'activo', ?)";
            $stmt = $this->conn->prepare($query);
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bind_param("sssss", $data['name'], $data['email'], $hashedPassword, $data['role_id'], $authType);
            
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

    /**
     * Actualizar último login del usuario
     */
    public function updateLastLogin($id) {
        try {
            $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = ?";
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

    /**
     * Registrar cambio en auditoría
     */
    public function logAudit($adminId, $adminName, $targetUserId, $targetUserName, $action, $changes = []) {
        try {
            $query = "INSERT INTO user_audit_logs (admin_user_id, admin_user_name, target_user_id, target_user_name, action, changes) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $changesJson = json_encode($changes);
            $stmt->bind_param("isisss", $adminId, $adminName, $targetUserId, $targetUserName, $action, $changesJson);
            
            return $stmt->execute();
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error: " . $e->getMessage();
            }
            return false;
        }
    }

    /**
     * Obtener logs de auditoría
     */
    public function getAuditLogs($limit = 50) {
        try {
            $query = "SELECT * FROM user_audit_logs ORDER BY created_at DESC LIMIT ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
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
}
?>
