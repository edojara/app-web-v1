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

                        // Actualizar último login
                        $this->userModel->updateLastLogin($user['id']);

                        header('Location: ' . APP_URL);
                        exit;
                    }
                } else {
                    $error = 'Credenciales inválidas.';
                }
            }
        }

        $pageTitle = 'Iniciar sesión';
        require_once VIEWS_PATH . '/auth/login.php';
    }

    public function googleLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Verificar que las credenciales de Google estén configuradas
        if (!defined('GOOGLE_CLIENT_ID') || GOOGLE_CLIENT_ID === '') {
            $_SESSION['error'] = 'Google OAuth no está configurado. CLIENT_ID: ' . (defined('GOOGLE_CLIENT_ID') ? GOOGLE_CLIENT_ID : 'NO DEFINIDO');
            header('Location: ?url=auth/login');
            exit;
        }

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
                    // Buscar usuario por Google ID
                    $user = $this->userModel->getByGoogleId($userInfo['sub']);

                    // Si no existe por Google ID, buscar por email
                    if (!$user) {
                        $user = $this->userModel->getByEmail($userInfo['email']);
                        
                        // Si existe por email pero no tiene Google ID, vincular la cuenta
                        if ($user && $user['auth_type'] === 'local') {
                            // El usuario existe como local pero está intentando login con Google
                            $error = 'Tu cuenta está registrada como cuenta local. Por favor, usa tu correo y contraseña.';
                        } elseif (!$user) {
                            // Usuario no existe en absoluto
                            $error = 'Tu cuenta no está registrada en la plataforma. Por favor, solicita inscripción al administrador.';
                        }
                    }

                    if ($user && !$error) {
                        // Verificar estado del usuario
                        if ($user['estado'] !== 'activo') {
                            $error = 'Tu cuenta está inactiva. Contacta al administrador.';
                        } else {
                            // Actualizar Google ID si no lo tenía
                            if (!$user['google_id']) {
                                $updateQuery = "UPDATE users SET google_id = ?, auth_type = 'google' WHERE id = ?";
                                global $conn;
                                $stmt = $conn->prepare($updateQuery);
                                $stmt->bind_param("si", $userInfo['sub'], $user['id']);
                                $stmt->execute();
                            }

                            // Iniciar sesión
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_name'] = $user['name'];
                            $_SESSION['user_role_id'] = $user['role_id'];
                            $_SESSION['user_email'] = $user['email'];
                            $_SESSION['auth_type'] = 'google';

                            // Actualizar último login
                            $this->userModel->updateLastLogin($user['id']);

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

    // Aliases para que el router encuentre los métodos con guiones
    public function google_login() {
        $this->googleLogin();
    }

    public function google_callback() {
        $this->googleCallback();
    }
}

?>
