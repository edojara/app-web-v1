<?php

require_once MODELS_PATH . '/Inscripcion.php';
require_once MODELS_PATH . '/Evento.php';
require_once MODELS_PATH . '/Participante.php';
require_once MODELS_PATH . '/AuditoriaApp.php';

class InscripcionesController {
    private $inscripcionModel;
    private $eventoModel;
    private $participanteModel;
    private $auditoriaModel;
    
    public function __construct() {
        $this->inscripcionModel = new Inscripcion();
        $this->eventoModel = new Evento();
        $this->participanteModel = new Participante();
        $this->auditoriaModel = new AuditoriaApp();
    }
    
    /**
     * Listar inscripciones por evento
     */
    public function index() {
        if (!isset($_GET['evento_id'])) {
            header('Location: ?url=eventos');
            exit;
        }
        
        $evento_id = $_GET['evento_id'];
        $evento = $this->eventoModel->getById($evento_id);
        
        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            header('Location: ?url=eventos');
            exit;
        }
        
        $inscripciones = $this->inscripcionModel->getByEvento($evento_id);
        $totalInscritos = $this->inscripcionModel->countByEvento($evento_id);
        
        // Obtener participantes disponibles para inscribir
        $todosParticipantes = $this->participanteModel->getAll();
        $participantesDisponibles = array_filter($todosParticipantes, function($p) use ($inscripciones) {
            foreach ($inscripciones as $insc) {
                if ($insc['participante_id'] == $p['id']) {
                    return false;
                }
            }
            return true;
        });
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/inscripciones/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Inscribir participantes
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=eventos');
            exit;
        }
        
        $evento_id = $_POST['evento_id'] ?? null;
        $participante_ids = $_POST['participante_ids'] ?? [];
        $observaciones = $_POST['observaciones'] ?? '';
        
        if (!$evento_id || empty($participante_ids)) {
            $_SESSION['error'] = 'Debe seleccionar al menos un participante';
            header("Location: ?url=inscripciones&evento_id=$evento_id");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        
        $resultado = $this->inscripcionModel->inscribirMultiples(
            $evento_id, 
            $participante_ids, 
            $user_id, 
            $observaciones
        );
        
        // Registrar en auditoría
        $evento = $this->eventoModel->getById($evento_id);
        $this->auditoriaModel->registrar(
            $user_id,
            'inscripcion',
            'crear',
            "Inscribió {$resultado['success']} participante(s) en el evento: {$evento['nombre']}"
        );
        
        if ($resultado['success'] > 0) {
            $_SESSION['success'] = "Se inscribieron {$resultado['success']} participante(s) exitosamente";
        }
        
        if (!empty($resultado['errors'])) {
            $_SESSION['warning'] = implode('. ', $resultado['errors']);
        }
        
        header("Location: ?url=inscripciones&evento_id=$evento_id");
        exit;
    }
    
    /**
     * Cancelar inscripción
     */
    public function cancel() {
        if (!isset($_GET['id']) || !isset($_GET['evento_id'])) {
            header('Location: ?url=eventos');
            exit;
        }
        
        $id = $_GET['id'];
        $evento_id = $_GET['evento_id'];
        
        $inscripcion = $this->inscripcionModel->getById($id);
        
        if (!$inscripcion) {
            $_SESSION['error'] = 'Inscripción no encontrada';
            header("Location: ?url=inscripciones&evento_id=$evento_id");
            exit;
        }
        
        if ($this->inscripcionModel->cancel($id)) {
            // Registrar en auditoría
            $user_id = $_SESSION['user_id'];
            $this->auditoriaModel->registrar(
                $user_id,
                'inscripcion',
                'cancelar',
                "Canceló inscripción de {$inscripcion['participante_nombre']} en evento {$inscripcion['evento_nombre']}"
            );
            
            $_SESSION['success'] = 'Inscripción cancelada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al cancelar la inscripción';
        }
        
        header("Location: ?url=inscripciones&evento_id=$evento_id");
        exit;
    }
    
    /**
     * Eliminar inscripción
     */
    public function delete() {
        if (!isset($_GET['id']) || !isset($_GET['evento_id'])) {
            header('Location: ?url=eventos');
            exit;
        }
        
        $id = $_GET['id'];
        $evento_id = $_GET['evento_id'];
        
        $inscripcion = $this->inscripcionModel->getById($id);
        
        if (!$inscripcion) {
            $_SESSION['error'] = 'Inscripción no encontrada';
            header("Location: ?url=inscripciones&evento_id=$evento_id");
            exit;
        }
        
        if ($this->inscripcionModel->delete($id)) {
            // Registrar en auditoría
            $user_id = $_SESSION['user_id'];
            $this->auditoriaModel->registrar(
                $user_id,
                'inscripcion',
                'eliminar',
                "Eliminó inscripción de {$inscripcion['participante_nombre']} en evento {$inscripcion['evento_nombre']}"
            );
            
            $_SESSION['success'] = 'Inscripción eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la inscripción';
        }
        
        header("Location: ?url=inscripciones&evento_id=$evento_id");
        exit;
    }
}
