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
        
        // Obtener top 5 instituciones con más participantes
        $topInstituciones = $this->getTopInstituciones(5);
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/home/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Obtener instituciones con más participantes
     */
    private function getTopInstituciones($limit = 5) {
        global $conn;
        
        $sql = "SELECT i.id, i.nombre, COUNT(p.id) as total_participantes
                FROM instituciones i
                LEFT JOIN participantes p ON i.id = p.institucion_id
                GROUP BY i.id, i.nombre
                HAVING COUNT(p.id) > 0
                ORDER BY total_participantes DESC
                LIMIT ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $instituciones = [];
        while ($row = $result->fetch_assoc()) {
            $instituciones[] = $row;
        }
        
        return $instituciones;
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
