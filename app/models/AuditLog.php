<?php

class AuditLog {
    private $db;

    public function __construct() {
        $this->db = getDBConnection();
    }

    /**
     * Registrar una acción en el log de auditoría
     */
    public function log($accion, $usuarioAfectadoId = null, $cambios = [], $tablaAfectada = 'users') {
        try {
            $usuarioId = $_SESSION['user_id'] ?? null;
            $ipAddress = $this->getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            // Convertir los cambios a JSON
            $cambiosJson = !empty($cambios) ? json_encode($cambios, JSON_UNESCAPED_UNICODE) : null;
            
            $stmt = $this->db->prepare("
                INSERT INTO audit_logs 
                (usuario_id, usuario_afectado_id, accion, tabla_afectada, cambios, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->bind_param(
                "iisssss",
                $usuarioId,
                $usuarioAfectadoId,
                $accion,
                $tablaAfectada,
                $cambiosJson,
                $ipAddress,
                $userAgent
            );
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al registrar auditoría: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los logs de auditoría con filtros opcionales
     */
    public function getAll($filtros = []) {
        try {
            $where = [];
            $params = [];
            $types = "";
            
            if (!empty($filtros['usuario_id'])) {
                $where[] = "a.usuario_id = ?";
                $params[] = $filtros['usuario_id'];
                $types .= "i";
            }
            
            if (!empty($filtros['usuario_afectado_id'])) {
                $where[] = "a.usuario_afectado_id = ?";
                $params[] = $filtros['usuario_afectado_id'];
                $types .= "i";
            }
            
            if (!empty($filtros['accion'])) {
                $where[] = "a.accion = ?";
                $params[] = $filtros['accion'];
                $types .= "s";
            }
            
            if (!empty($filtros['fecha_desde'])) {
                $where[] = "DATE(a.created_at) >= ?";
                $params[] = $filtros['fecha_desde'];
                $types .= "s";
            }
            
            if (!empty($filtros['fecha_hasta'])) {
                $where[] = "DATE(a.created_at) <= ?";
                $params[] = $filtros['fecha_hasta'];
                $types .= "s";
            }
            
            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
            
            $sql = "
                SELECT 
                    a.*,
                    u.name as usuario_nombre,
                    u.email as usuario_email,
                    ua.name as usuario_afectado_nombre,
                    ua.email as usuario_afectado_email
                FROM audit_logs a
                LEFT JOIN users u ON a.usuario_id = u.id
                LEFT JOIN users ua ON a.usuario_afectado_id = ua.id
                $whereClause
                ORDER BY a.created_at DESC
                LIMIT 500
            ";
            
            $stmt = $this->db->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener logs de auditoría: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener logs de un usuario específico
     */
    public function getByUserId($userId, $limit = 50) {
        return $this->getAll(['usuario_afectado_id' => $userId]);
    }

    /**
     * Obtener las acciones disponibles
     */
    public function getAccionesDisponibles() {
        return [
            'crear_usuario' => 'Usuario Creado',
            'editar_usuario' => 'Usuario Editado',
            'eliminar_usuario' => 'Usuario Eliminado',
            'cambiar_password' => 'Contraseña Cambiada',
            'cambiar_rol' => 'Rol Cambiado',
            'cambiar_estado' => 'Estado Cambiado',
            'login' => 'Inicio de Sesión',
            'logout' => 'Cierre de Sesión'
        ];
    }

    /**
     * Obtener la IP real del cliente
     */
    private function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /**
     * Obtener estadísticas de auditoría
     */
    public function getEstadisticas() {
        try {
            $sql = "
                SELECT 
                    COUNT(*) as total_logs,
                    COUNT(DISTINCT usuario_id) as usuarios_activos,
                    COUNT(DISTINCT DATE(created_at)) as dias_activos,
                    MAX(created_at) as ultima_actividad
                FROM audit_logs
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ";
            
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [];
        }
    }
}
