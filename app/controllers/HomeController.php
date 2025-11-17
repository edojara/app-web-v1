<?php
/**
 * Controlador: HomeController
 * Descripción: Controlador principal de la aplicación
 */

require_once MODELS_PATH . '/User.php';

class HomeController {
    
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Acción por defecto: mostrar página principal
     */
    public function index() {
        $pageTitle = "Inicio";
        $users = $this->userModel->getAll();
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/home/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Página de acerca de
     */
    public function about() {
        $pageTitle = "Acerca de";
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/home/about.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
}
?>
