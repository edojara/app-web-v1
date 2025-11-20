<?php

require_once MODELS_PATH . '/Checkin.php';
require_once MODELS_PATH . '/Inscripcion.php';
require_once MODELS_PATH . '/Evento.php';
require_once MODELS_PATH . '/Participante.php';
require_once MODELS_PATH . '/AuditoriaApp.php';

class CheckinController {
    private $checkinModel;
    private $inscripcionModel;
    private $eventoModel;
    private $participanteModel;
    private $auditoriaModel;
    
    public function __construct() {
        $this->checkinModel = new Checkin();
        $this->inscripcionModel = new Inscripcion();
        $this->eventoModel = new Evento();
        $this->participanteModel = new Participante();
        $this->auditoriaModel = new AuditoriaApp();
    }
    
    /**
     * Mostrar panel de check-in para un evento
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
        
        // Si no se especifica fecha, redirigir con la primera fecha del evento
        if (!isset($_GET['fecha'])) {
            header('Location: ?url=checkin&evento_id=' . $evento_id . '&fecha=' . $evento['fecha_inicio']);
            exit;
        }
        
        // Fecha seleccionada
        $fecha_seleccionada = $_GET['fecha'];
        
        // Validar que la fecha esté dentro del rango del evento
        if ($fecha_seleccionada < $evento['fecha_inicio'] || $fecha_seleccionada > $evento['fecha_termino']) {
            // Si la fecha está fuera del rango, redirigir a la primera fecha del evento
            header('Location: ?url=checkin&evento_id=' . $evento_id . '&fecha=' . $evento['fecha_inicio']);
            exit;
        }
        
        // Obtener todas las inscripciones del evento
        $inscripciones = $this->inscripcionModel->getByEvento($evento_id);
        
        // Verificar cuáles tienen check-in hoy
        foreach ($inscripciones as &$inscripcion) {
            $inscripcion['tiene_checkin_hoy'] = $this->checkinModel->existeCheckin($inscripcion['id'], $fecha_seleccionada);
            $inscripcion['total_checkins'] = count($this->checkinModel->getByInscripcion($inscripcion['id']));
        }
        
        // Contar check-ins del día
        $checkins_hoy = $this->checkinModel->contarByEventoYFecha($evento_id, $fecha_seleccionada);
        $total_inscritos = count($inscripciones);
        
        // Generar array de fechas del evento
        $fecha_inicio = new DateTime($evento['fecha_inicio']);
        $fecha_termino = new DateTime($evento['fecha_termino']);
        $fechas_evento = [];
        for ($fecha = clone $fecha_inicio; $fecha <= $fecha_termino; $fecha->modify('+1 day')) {
            $fechas_evento[] = $fecha->format('Y-m-d');
        }
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/checkin/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Registrar check-in
     */
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=eventos');
            exit;
        }
        
        $inscripcion_id = $_POST['inscripcion_id'] ?? null;
        $evento_id = $_POST['evento_id'] ?? null;
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        
        if (!$inscripcion_id || !$evento_id) {
            $_SESSION['error'] = 'Datos incompletos';
            header("Location: ?url=checkin&evento_id=$evento_id&fecha=$fecha");
            exit;
        }
        
        // Verificar que la inscripción existe
        $inscripcion = $this->inscripcionModel->getById($inscripcion_id);
        if (!$inscripcion) {
            $_SESSION['error'] = 'Inscripción no encontrada';
            header("Location: ?url=checkin&evento_id=$evento_id&fecha=$fecha");
            exit;
        }
        
        // Verificar si ya tiene check-in en esta fecha
        if ($this->checkinModel->existeCheckin($inscripcion_id, $fecha)) {
            $_SESSION['error'] = 'El participante ya tiene check-in registrado para esta fecha';
            header("Location: ?url=checkin&evento_id=$evento_id&fecha=$fecha");
            exit;
        }
        
        // Registrar check-in
        if ($this->checkinModel->registrar($inscripcion_id, $fecha)) {
            // Obtener datos del participante
            $participante = $this->participanteModel->getById($inscripcion['participante_id']);
            
            // Registrar en auditoría
            $this->auditoriaModel->log(
                'checkin',
                'registrar',
                $inscripcion_id,
                $participante['nombre_completo'],
                ['fecha' => $fecha, 'hora' => date('H:i:s')]
            );
            
            $_SESSION['success'] = "✅ Check-in registrado para {$participante['nombre_completo']}";
        } else {
            $_SESSION['error'] = 'Error al registrar el check-in';
        }
        
        header("Location: ?url=checkin&evento_id=$evento_id&fecha=$fecha");
        exit;
    }
    
    /**
     * Buscar participante por RUT para check-in rápido
     */
    public function buscarPorRut() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }
        
        $rut = $_POST['rut'] ?? '';
        $evento_id = $_POST['evento_id'] ?? null;
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        
        if (!$rut || !$evento_id) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }
        
        // Buscar participante por RUT
        $participante = $this->participanteModel->getByRut($rut);
        
        if (!$participante) {
            echo json_encode(['success' => false, 'message' => 'Participante no encontrado']);
            exit;
        }
        
        // Verificar que esté inscrito en el evento
        $inscripciones = $this->inscripcionModel->getByEvento($evento_id);
        $inscripcion = null;
        
        foreach ($inscripciones as $insc) {
            if ($insc['participante_id'] == $participante['id']) {
                $inscripcion = $insc;
                break;
            }
        }
        
        if (!$inscripcion) {
            echo json_encode(['success' => false, 'message' => 'El participante no está inscrito en este evento']);
            exit;
        }
        
        // Verificar si ya tiene check-in
        if ($this->checkinModel->existeCheckin($inscripcion['id'], $fecha)) {
            echo json_encode([
                'success' => false, 
                'message' => 'El participante ya tiene check-in registrado para esta fecha',
                'participante' => $participante
            ]);
            exit;
        }
        
        // Registrar check-in automáticamente
        if ($this->checkinModel->registrar($inscripcion['id'], $fecha)) {
            // Registrar en auditoría
            $this->auditoriaModel->log(
                'checkin',
                'registrar',
                $inscripcion['id'],
                $participante['nombre_completo'],
                ['fecha' => $fecha, 'hora' => date('H:i:s'), 'metodo' => 'RUT']
            );
            
            echo json_encode([
                'success' => true,
                'message' => "✅ Check-in registrado exitosamente",
                'participante' => $participante,
                'hora' => date('H:i:s')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar el check-in']);
        }
        exit;
    }
}
