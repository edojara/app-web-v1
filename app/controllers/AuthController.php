<?php
/**
 * Controlador: AuthController
 * Descripción: Manejo de login y logout
 */

require_once MODELS_PATH . '/User.php';

class AuthController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $pageTitle = 'Login';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if ($email === '' || $password === '') {
                $error = 'Por favor ingrese correo y contraseña.';
            } else {
                $user = $this->userModel->getByEmail($email);
                if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                    if (isset($user['estado']) && $user['estado'] !== 'activo') {
                        $error = 'Usuario inactivo. Contacte al administrador.';
                    } else {
                        // Autenticación exitosa
                        if (session_status() === PHP_SESSION_NONE) session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_role_id'] = $user['role_id'];

                        header('Location: ' . APP_URL);
                        exit;
                    }
                } else {
                    $error = 'Credenciales inválidas.';
                }
            }
        }

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/auth/login.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header('Location: ' . APP_URL . '/?url=auth/login');
        exit;
    }
}

?>
