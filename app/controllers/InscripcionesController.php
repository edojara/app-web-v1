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
        $this->auditoriaModel->log(
            'inscripcion',
            'crear',
            $evento_id,
            $evento['nombre'],
            ['participantes_inscritos' => $resultado['success']]
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
            $this->auditoriaModel->log(
                'inscripcion',
                'cancelar',
                $id,
                "{$inscripcion['participante_nombre']} - {$inscripcion['evento_nombre']}"
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
            $this->auditoriaModel->log(
                'inscripcion',
                'eliminar',
                $id,
                "{$inscripcion['participante_nombre']} - {$inscripcion['evento_nombre']}"
            );
            
            $_SESSION['success'] = 'Inscripción eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la inscripción';
        }
        
        header("Location: ?url=inscripciones&evento_id=$evento_id");
        exit;
    }
    
    /**
     * Importar inscripciones desde CSV
     */
    public function importCSV() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=eventos');
            exit;
        }
        
        $evento_id = $_POST['evento_id'] ?? null;
        $observaciones = $_POST['observaciones'] ?? '';
        
        if (!$evento_id) {
            $_SESSION['error'] = 'Evento no especificado';
            header("Location: ?url=inscripciones&evento_id=$evento_id");
            exit;
        }
        
        // Validar que se haya subido un archivo
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No se pudo cargar el archivo CSV';
            header("Location: ?url=inscripciones&evento_id=$evento_id");
            exit;
        }
        
        $file = $_FILES['csv_file']['tmp_name'];
        $ruts = [];
        
        // Leer archivo línea por línea
        if (($handle = fopen($file, 'r')) !== false) {
            while (($line = fgets($handle)) !== false) {
                $rut = trim($line);
                if (!empty($rut)) {
                    $ruts[] = $rut;
                }
            }
            fclose($handle);
        }
        
        if (empty($ruts)) {
            $_SESSION['error'] = 'El archivo CSV está vacío';
            header("Location: ?url=inscripciones&evento_id=$evento_id");
            exit;
        }
        
        // Buscar participantes por RUT e inscribirlos
        $user_id = $_SESSION['user_id'];
        $participantes_encontrados = [];
        $participantes_no_encontrados = [];
        $participantes_duplicados = [];
        
        foreach ($ruts as $rut) {
            // Buscar participante por RUT
            $participante = $this->participanteModel->getByRut($rut);
            
            if ($participante) {
                // Verificar si ya está inscrito
                if ($this->inscripcionModel->exists($evento_id, $participante['id'])) {
                    $participantes_duplicados[] = $rut;
                } else {
                    $participantes_encontrados[] = $participante['id'];
                }
            } else {
                $participantes_no_encontrados[] = $rut;
            }
        }
        
        // Inscribir participantes encontrados
        $inscriptos = 0;
        if (!empty($participantes_encontrados)) {
            $resultado = $this->inscripcionModel->inscribirMultiples(
                $evento_id,
                $participantes_encontrados,
                $user_id,
                $observaciones
            );
            $inscriptos = $resultado['success'];
        }
        
        // Registrar en auditoría
        $evento = $this->eventoModel->getById($evento_id);
        $this->auditoriaModel->log(
            'inscripcion',
            'importar_csv',
            $evento_id,
            $evento['nombre'],
            ['ruts_procesados' => count($ruts), 'inscritos' => $inscriptos]
        );
        
        // Preparar mensajes
        $mensajes = [];
        
        if ($inscriptos > 0) {
            $_SESSION['success'] = "✅ Se inscribieron {$inscriptos} participante(s) exitosamente";
        }
        
        if (!empty($participantes_duplicados)) {
            $mensajes[] = "⚠️ " . count($participantes_duplicados) . " participante(s) ya estaban inscritos: " . implode(', ', array_slice($participantes_duplicados, 0, 5)) . (count($participantes_duplicados) > 5 ? '...' : '');
        }
        
        if (!empty($participantes_no_encontrados)) {
            $mensajes[] = "❌ " . count($participantes_no_encontrados) . " RUT(s) no encontrados en el sistema: " . implode(', ', array_slice($participantes_no_encontrados, 0, 5)) . (count($participantes_no_encontrados) > 5 ? '...' : '');
        }
        
        if (!empty($mensajes)) {
            $_SESSION['warning'] = implode('<br>', $mensajes);
        }
        
        header("Location: ?url=inscripciones&evento_id=$evento_id");
        exit;
    }
}
