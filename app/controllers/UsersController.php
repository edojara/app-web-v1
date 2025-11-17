<?php
/**
 * Controlador: UsersController
 * Descripción: Controlador para gestión de usuarios y roles
 */

require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Role.php';

class UsersController {

    private $userModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    /**
     * Verificar si el usuario actual es administrador
     */
    private function isAdmin() {
        return isset($_SESSION['user_role_id']) && $_SESSION['user_role_id'] == 1; // 1 = administrador
    }

    /**
     * Verificar acceso de administrador
     */
    private function checkAdminAccess() {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "Acceso denegado. Solo administradores pueden realizar esta acción.";
            exit;
        }
    }
    
    /**
     * Listar todos los usuarios
     */
    public function index() {
        $pageTitle = "Gestión de Usuarios";
        $users = $this->userModel->getAllWithRoles();
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/users/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Ver detalle de usuario
     */
    public function view($id = null) {
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (!$id) {
            header('Location: ' . APP_URL . '/public/index.php?url=users');
            exit;
        }
        
        $pageTitle = "Detalle de Usuario";
        $user = $this->userModel->getWithRole($id);
        
        if (!$user) {
            header('HTTP/1.0 404 Not Found');
            echo "Usuario no encontrado";
            exit;
        }
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/users/view.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Crear nuevo usuario
     */
    public function create() {
        $this->checkAdminAccess();

        $pageTitle = "Crear Usuario";
        $roles = $this->roleModel->getAll();
        $mensaje = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authType = $_POST['auth_type'] ?? 'local';
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $roleId = $_POST['role_id'] ?? null;
            
            // Validar campos requeridos
            if (empty($name) || empty($email)) {
                $mensaje = '<div class="alerta alerta-error">Nombre y email son requeridos</div>';
            } elseif ($authType === 'local' && empty($password)) {
                $mensaje = '<div class="alerta alerta-error">La contraseña es requerida para cuentas locales</div>';
            } else {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $authType === 'local' ? $password : 'google_oauth_' . uniqid(),
                    'role_id' => $roleId,
                    'auth_type' => $authType
                ];
                
                if ($this->userModel->create($data)) {
                    // Registrar en auditoría
                    $this->userModel->logAudit(
                        $_SESSION['user_id'],
                        $_SESSION['user_name'],
                        0, // Se obtiene el ID del usuario creado
                        $name,
                        'create',
                        ['auth_type' => $authType, 'email' => $email, 'role_id' => $roleId]
                    );
                    $mensaje = '<div class="alerta alerta-exito">Usuario creado exitosamente</div>';
                    header('Location: ' . APP_URL . '/?url=users');
                    exit;
                } else {
                    $mensaje = '<div class="alerta alerta-error">Error al crear el usuario</div>';
                }
            }
        }
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/users/create.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Editar usuario
     */
    public function edit() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . APP_URL . '/?url=users');
            exit;
        }
        
        $user = $this->userModel->getWithRole($id);
        
        if (!$user) {
            header('HTTP/1.0 404 Not Found');
            echo "Usuario no encontrado";
            exit;
        }
        
        $pageTitle = "Editar Usuario";
        $roles = $this->roleModel->getAll();
        $mensaje = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'role_id' => $_POST['role_id'] ?? null,
                'estado' => $_POST['estado'] ?? 'activo'
            ];
            
            if (empty($data['name']) || empty($data['email'])) {
                $mensaje = '<div class="alerta alerta-error">Nombre y email son requeridos</div>';
            } elseif ($this->userModel->update($id, $data)) {
                // Registrar en auditoría
                $changes = array_diff_assoc($data, [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'estado' => $user['estado']
                ]);
                if (!empty($changes)) {
                    $this->userModel->logAudit(
                        $_SESSION['user_id'],
                        $_SESSION['user_name'],
                        $id,
                        $user['name'],
                        'update',
                        $changes
                    );
                }
                $mensaje = '<div class="alerta alerta-exito">Usuario actualizado exitosamente</div>';
                header('Location: ' . APP_URL . '/?url=users');
                exit;
            } else {
                $mensaje = '<div class="alerta alerta-error">Error al actualizar el usuario</div>';
            }
        }
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/users/edit.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Eliminar usuario
     */
    public function delete() {
        $this->checkAdminAccess();

        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . APP_URL . '/?url=users');
            exit;
        }

        $user = $this->userModel->getById($id);

        if ($this->userModel->delete($id)) {
            // Registrar en auditoría
            $this->userModel->logAudit(
                $_SESSION['user_id'],
                $_SESSION['user_name'],
                $id,
                $user['name'] ?? 'Desconocido',
                'delete',
                ['email' => $user['email'] ?? null]
            );
            $_SESSION['mensaje'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        
        header('Location: ' . APP_URL . '/?url=users');
        exit;
    }
}
?>
