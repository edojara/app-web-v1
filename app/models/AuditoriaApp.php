<?php
/**
 * Modelo: AuditoriaApp
 * Descripción: Modelo para auditoría de la aplicación (módulos funcionales)
 *              Separado de la auditoría de usuarios del sistema
 */

class AuditoriaApp {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    /**
     * Registrar una acción en el log de auditoría de la aplicación
     */
    public function log($modulo, $accion, $registroId = null, $registroNombre = null, $cambios = []) {
        try {
            $usuarioId = $_SESSION['user_id'] ?? null;
            $ipAddress = $this->getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            // Convertir los cambios a JSON
            $cambiosJson = !empty($cambios) ? json_encode($cambios, JSON_UNESCAPED_UNICODE) : null;
            
            $stmt = $this->db->prepare("
                INSERT INTO auditoria_app 
                (modulo, accion, registro_id, registro_nombre, usuario_id, cambios, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->bind_param(
                "ssisssss",
                $modulo,
                $accion,
                $registroId,
                $registroNombre,
                $usuarioId,
                $cambiosJson,
                $ipAddress,
                $userAgent
            );
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al registrar auditoría de app: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los logs con filtros opcionales
     */
    public function getAll($filtros = []) {
        try {
            $where = [];
            $params = [];
            $types = "";
            
            if (!empty($filtros['modulo'])) {
                $where[] = "a.modulo = ?";
                $params[] = $filtros['modulo'];
                $types .= "s";
            }
            
            if (!empty($filtros['accion'])) {
                $where[] = "a.accion = ?";
                $params[] = $filtros['accion'];
                $types .= "s";
            }
            
            if (!empty($filtros['usuario_id'])) {
                $where[] = "a.usuario_id = ?";
                $params[] = $filtros['usuario_id'];
                $types .= "i";
            }
            
            if (!empty($filtros['registro_id'])) {
                $where[] = "a.registro_id = ?";
                $params[] = $filtros['registro_id'];
                $types .= "i";
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
                    u.email as usuario_email
                FROM auditoria_app a
                LEFT JOIN users u ON a.usuario_id = u.id
                $whereClause
                ORDER BY a.created_at DESC
                LIMIT 1000
            ";
            
            $stmt = $this->db->prepare($sql);
            
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener logs de auditoría de app: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener logs de un módulo específico
     */
    public function getByModulo($modulo, $limit = 100) {
        return $this->getAll(['modulo' => $modulo]);
    }

    /**
     * Obtener logs de un registro específico
     */
    public function getByRegistro($modulo, $registroId) {
        return $this->getAll(['modulo' => $modulo, 'registro_id' => $registroId]);
    }

    /**
     * Obtener módulos disponibles
     */
    public function getModulosDisponibles() {
        return [
            'instituciones' => 'Instituciones',
            'contactos' => 'Contactos',
            'programas' => 'Programas',
            'criterios' => 'Criterios',
            'evaluaciones' => 'Evaluaciones',
            'documentos' => 'Documentos'
        ];
    }

    /**
     * Obtener acciones disponibles
     */
    public function getAccionesDisponibles() {
        return [
            'crear' => 'Crear',
            'editar' => 'Editar',
            'eliminar' => 'Eliminar',
            'activar' => 'Activar',
            'desactivar' => 'Desactivar',
            'agregar_contacto' => 'Agregar Contacto',
            'eliminar_contacto' => 'Eliminar Contacto'
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
                    COUNT(DISTINCT modulo) as total_modulos,
                    COUNT(DISTINCT usuario_id) as usuarios_activos,
                    MAX(created_at) as ultima_actividad
                FROM auditoria_app
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
