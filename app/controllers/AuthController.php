<?php
/**
 * Controlador: AuthController
 * Descripción: Manejo de login, logout y OAuth2 con Google
 */

require_once MODELS_PATH . '/User.php';
require_once dirname(dirname(dirname(__FILE__))) . '/config/google-oauth.php';

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
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['auth_type'] = 'local';

                        header('Location: ' . APP_URL);
                        exit;
                    }
                } else {
                    $error = 'Credenciales inválidas.';
                }
            }
        }

        $pageTitle = 'Iniciar sesión';
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/auth/login.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    public function googleLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Generar state token para CSRF protection
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        // Parámetros de Google OAuth
        $params = [
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
        ];

        $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
        header('Location: ' . $authUrl);
        exit;
    }

    public function googleCallback() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $error = '';

        // Verificar state token (CSRF protection)
        if (!isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
            $error = 'Token de seguridad inválido. Intente de nuevo.';
        } elseif (!isset($_GET['code'])) {
            $error = 'No se recibió código de autorización.';
        } else {
            // Intercambiar código por token
            $code = $_GET['code'];
            $tokenResponse = $this->getGoogleAccessToken($code);

            if (isset($tokenResponse['error'])) {
                $error = 'Error al obtener token: ' . $tokenResponse['error'];
            } else {
                $accessToken = $tokenResponse['access_token'];
                $userInfo = $this->getGoogleUserInfo($accessToken);

                if (isset($userInfo['error'])) {
                    $error = 'Error al obtener información del usuario.';
                } else {
                    // Buscar o crear usuario
                    $user = $this->userModel->getByGoogleId($userInfo['sub']);

                    if (!$user) {
                        // Crear nuevo usuario con Google OAuth
                        $userData = [
                            'name' => $userInfo['name'],
                            'email' => $userInfo['email'],
                            'google_id' => $userInfo['sub'],
                        ];
                        if ($this->userModel->createWithGoogle($userData)) {
                            $user = $this->userModel->getByGoogleId($userInfo['sub']);
                        } else {
                            $error = 'No se pudo crear la cuenta.';
                        }
                    }

                    if ($user && !$error) {
                        // Verificar estado
                        if ($user['estado'] !== 'activo') {
                            $error = 'Usuario inactivo. Contacte al administrador.';
                        } else {
                            // Iniciar sesión
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_name'] = $user['name'];
                            $_SESSION['user_role_id'] = $user['role_id'];
                            $_SESSION['user_email'] = $user['email'];
                            $_SESSION['auth_type'] = 'google';

                            header('Location: ' . APP_URL);
                            exit;
                        }
                    }
                }
            }
        }

        // Si hay error, mostrar página de login con mensaje
        $pageTitle = 'Iniciar sesión';
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/auth/login.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    private function getGoogleAccessToken($code) {
        $url = 'https://oauth2.googleapis.com/token';
        $data = [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
                'timeout' => 5,
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return ['error' => 'No se pudo conectar con Google'];
        }

        return json_decode($response, true);
    }

    private function getGoogleUserInfo($accessToken) {
        $url = 'https://openidconnect.googleapis.com/v1/userinfo?access_token=' . urlencode($accessToken);

        $options = [
            'http' => [
                'timeout' => 5,
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            return ['error' => 'No se pudo obtener información del usuario'];
        }

        return json_decode($response, true);
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
