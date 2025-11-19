<?php
/**
 * Controlador: HomeController
 * Descripción: Controlador principal de la aplicación
 */

require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Evento.php';
require_once MODELS_PATH . '/Institucion.php';
require_once MODELS_PATH . '/Participante.php';

class HomeController {
    
    private $userModel;
    private $eventoModel;
    private $institucionModel;
    private $participanteModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->eventoModel = new Evento();
        $this->institucionModel = new Institucion();
        $this->participanteModel = new Participante();
    }
    
    /**
     * Acción por defecto: mostrar página principal
     */
    public function index() {
        $pageTitle = "Inicio";
        $users = $this->userModel->getAll();
        
        // Obtener estadísticas del dashboard
        $eventos = $this->eventoModel->getAll();
        $ahora = date('Y-m-d H:i:s');
        $eventosProximos = 0;
        $eventosRealizados = 0;
        
        foreach ($eventos as $evento) {
            if ($evento['fecha_termino'] >= $ahora) {
                $eventosProximos++;
            } else {
                $eventosRealizados++;
            }
        }
        
        $totalInstituciones = count($this->institucionModel->getAll());
        $totalParticipantes = count($this->participanteModel->getAll());
        
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
