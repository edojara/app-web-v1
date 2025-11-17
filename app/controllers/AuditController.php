<?php
/**
 * Controlador: AuditController
 * Descripción: Controlador para gestión del log de auditoría
 */

require_once MODELS_PATH . '/AuditLog.php';
require_once MODELS_PATH . '/User.php';

class AuditController {

    private $auditLog;
    private $userModel;

    public function __construct() {
        $this->auditLog = new AuditLog();
        $this->userModel = new User();
    }

    /**
     * Verificar si el usuario actual es administrador
     */
    private function isAdmin() {
        return isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == 1;
    }

    /**
     * Verificar acceso de administrador
     */
    private function checkAdminAccess() {
        if (!$this->isAdmin()) {
            header('Location: ' . APP_URL . '/?url=home');
            exit;
        }
    }

    /**
     * Listar todos los logs de auditoría
     */
    public function index() {
        $this->checkAdminAccess();
        
        $pageTitle = "Historial de Auditoría";
        
        // Obtener filtros de la URL
        $filtros = [
            'usuario_id' => $_GET['usuario_id'] ?? null,
            'usuario_afectado_id' => $_GET['usuario_afectado_id'] ?? null,
            'accion' => $_GET['accion'] ?? null,
            'fecha_desde' => $_GET['fecha_desde'] ?? null,
            'fecha_hasta' => $_GET['fecha_hasta'] ?? null
        ];
        
        // Eliminar filtros vacíos
        $filtros = array_filter($filtros);
        
        // Obtener logs
        $logs = $this->auditLog->getAll($filtros);
        
        // Obtener todos los usuarios para el filtro
        $usuarios = $this->userModel->getAll();
        
        // Obtener acciones disponibles
        $acciones = $this->auditLog->getAccionesDisponibles();
        
        // Obtener estadísticas
        $estadisticas = $this->auditLog->getEstadisticas();
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/audit/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Ver logs de un usuario específico
     */
    public function user() {
        $this->checkAdminAccess();
        
        $userId = $_GET['id'] ?? null;
        
        if (!$userId) {
            header('Location: ' . APP_URL . '/?url=audit');
            exit;
        }
        
        $pageTitle = "Historial de Usuario";
        $user = $this->userModel->getById($userId);
        $logs = $this->auditLog->getByUserId($userId);
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/audit/user.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
}
