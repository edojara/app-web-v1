<?php
/**
 * Controlador: ProfileController
 * Descripción: Gestión del perfil de usuario
 */

require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Role.php';

class ProfileController {

    private $userModel;
    private $roleModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

    public function index() {
        $pageTitle = 'Mi Perfil';

        // Obtener datos del usuario autenticado
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/?url=auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getWithRole($userId);

        if (!$user) {
            http_response_code(404);
            echo "Usuario no encontrado";
            exit;
        }

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/profile/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    public function edit() {
        $pageTitle = 'Editar Perfil';

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/?url=auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getWithRole($userId);
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';

            if (empty($name)) {
                $error = 'El nombre es requerido.';
            } else {
                $data = [
                    'name' => $name,
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'estado' => $user['estado'],
                ];

                if ($this->userModel->update($userId, $data)) {
                    $_SESSION['user_name'] = $name;
                    $success = 'Perfil actualizado exitosamente.';
                    $user = $this->userModel->getWithRole($userId);
                } else {
                    $error = 'Error al actualizar el perfil.';
                }
            }
        }

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/profile/edit.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    public function change_password() {
        $pageTitle = 'Cambiar Contraseña';

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/?url=auth/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        $error = '';
        $success = '';

        // Verificar si el usuario es de autenticación local
        if ($user['auth_type'] !== 'local') {
            $error = 'No puedes cambiar la contraseña de una cuenta OAuth.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
            $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'Todos los campos son requeridos.';
            } elseif (!password_verify($currentPassword, $user['password'])) {
                $error = 'La contraseña actual es incorrecta.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Las nuevas contraseñas no coinciden.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $data = [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'estado' => $user['estado'],
                ];

                // Actualizar contraseña directamente en BD
                global $conn;
                $query = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $hashedPassword, $userId);

                if ($stmt->execute()) {
                    $success = 'Contraseña actualizada exitosamente.';
                } else {
                    $error = 'Error al actualizar la contraseña.';
                }
            }
        }

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/profile/change_password.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
}

?>
