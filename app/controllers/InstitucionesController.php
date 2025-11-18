<?php
/**
 * Controlador: InstitucionesController
 * Descripción: Controlador para gestión de instituciones académicas y sus contactos
 */

require_once MODELS_PATH . '/Institucion.php';
require_once MODELS_PATH . '/AuditoriaApp.php';

class InstitucionesController {

    private $institucionModel;
    private $auditoriaApp;

    public function __construct() {
        $this->institucionModel = new Institucion();
        $this->auditoriaApp = new AuditoriaApp();
    }

    /**
     * Listar todas las instituciones
     */
    public function index() {
        $pageTitle = "Instituciones Académicas";
        $instituciones = $this->institucionModel->getAll();
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/instituciones/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Ver detalles de una institución con sus contactos
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $institucion = $this->institucionModel->getById($id);
        
        if (!$institucion) {
            header('HTTP/1.0 404 Not Found');
            echo "Institución no encontrada";
            exit;
        }
        
        // Obtener contactos de la institución
        $contactos = $this->institucionModel->getContactos($id);
        
        // Obtener participantes de la institución
        $participantes = $this->institucionModel->getParticipantes($id);
        
        $pageTitle = "Detalles de Institución";
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/instituciones/view.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Crear nueva institución
     */
    public function create() {
        $pageTitle = "Nueva Institución";
        $mensaje = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $ciudad = $_POST['ciudad'] ?? '';
            $estado = $_POST['estado'] ?? 'activa';
            
            if (empty($nombre) || empty($direccion) || empty($ciudad)) {
                $mensaje = '<div class="alerta alerta-error">Todos los campos son requeridos</div>';
            } else {
                $data = [
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'ciudad' => $ciudad,
                    'estado' => $estado
                ];
                
                $institucionId = $this->institucionModel->create($data);
                
                if ($institucionId) {
                    // Registrar en auditoría
                    $this->auditoriaApp->log(
                        'instituciones',
                        'crear',
                        $institucionId,
                        $nombre,
                        ['despues' => $data]
                    );
                    
                    header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $institucionId);
                    exit;
                } else {
                    $mensaje = '<div class="alerta alerta-error">Error al crear la institución</div>';
                }
            }
        }
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/instituciones/create.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Editar institución
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $institucion = $this->institucionModel->getById($id);
        
        if (!$institucion) {
            header('HTTP/1.0 404 Not Found');
            echo "Institución no encontrada";
            exit;
        }
        
        $pageTitle = "Editar Institución";
        $mensaje = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre' => $_POST['nombre'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'ciudad' => $_POST['ciudad'] ?? '',
                'estado' => $_POST['estado'] ?? 'activa'
            ];
            
            if (empty($data['nombre']) || empty($data['direccion']) || empty($data['ciudad'])) {
                $mensaje = '<div class="alerta alerta-error">Todos los campos son requeridos</div>';
            } elseif ($this->institucionModel->update($id, $data)) {
                // Registrar en auditoría
                $this->auditoriaApp->log(
                    'instituciones',
                    'editar',
                    $id,
                    $data['nombre'],
                    [
                        'antes' => $institucion,
                        'despues' => $data
                    ]
                );
                
                header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $id);
                exit;
            } else {
                $mensaje = '<div class="alerta alerta-error">Error al actualizar la institución</div>';
            }
        }
        
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/instituciones/edit.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }
    
    /**
     * Eliminar institución
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $institucion = $this->institucionModel->getById($id);
        
        if ($this->institucionModel->delete($id)) {
            // Registrar en auditoría
            $this->auditoriaApp->log(
                'instituciones',
                'eliminar',
                $id,
                $institucion['nombre'] ?? 'Desconocida',
                ['antes' => $institucion]
            );
            
            $_SESSION['mensaje'] = 'Institución eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la institución';
        }
        
        header('Location: ' . APP_URL . '/?url=instituciones');
        exit;
    }
    
    /**
     * Agregar contacto a institución
     */
    public function addContacto() {
        $institucionId = $_POST['institucion_id'] ?? null;
        
        if (!$institucionId) {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'ocupacion' => $_POST['ocupacion'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        
        if ($contactoId = $this->institucionModel->addContacto($institucionId, $data)) {
            $institucion = $this->institucionModel->getById($institucionId);
            
            // Registrar en auditoría
            $this->auditoriaApp->log(
                'contactos',
                'agregar_contacto',
                $contactoId,
                $data['nombre_completo'],
                [
                    'institucion' => $institucion['nombre'],
                    'contacto' => $data
                ]
            );
            
            $_SESSION['mensaje'] = 'Contacto agregado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al agregar contacto';
        }
        
        header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $institucionId);
        exit;
    }
    
    /**
     * Eliminar contacto
     */
    public function deleteContacto() {
        $id = $_GET['id'] ?? null;
        $institucionId = $_GET['institucion_id'] ?? null;
        
        if (!$id || !$institucionId) {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $contacto = $this->institucionModel->getContactoById($id);
        
        if ($this->institucionModel->deleteContacto($id)) {
            // Registrar en auditoría
            $this->auditoriaApp->log(
                'contactos',
                'eliminar_contacto',
                $id,
                $contacto['nombre_completo'] ?? 'Desconocido',
                ['antes' => $contacto]
            );
            
            $_SESSION['mensaje'] = 'Contacto eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar contacto';
        }
        
        header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $institucionId);
        exit;
    }
    
    /**
     * Agregar participante
     */
    public function addParticipante() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $institucionId = $_POST['institucion_id'] ?? null;
        $nombreCompleto = $_POST['nombre_completo'] ?? '';
        $rut = $_POST['rut'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        
        if (!$institucionId || empty($nombreCompleto) || empty($rut)) {
            $_SESSION['error'] = 'Nombre completo y RUT son requeridos';
            header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $institucionId);
            exit;
        }
        
        $data = [
            'institucion_id' => $institucionId,
            'nombre_completo' => $nombreCompleto,
            'rut' => $rut,
            'telefono' => $telefono
        ];
        
        $participanteId = $this->institucionModel->addParticipante($data);
        
        if ($participanteId) {
            // Registrar en auditoría
            $this->auditoriaApp->log(
                'participantes',
                'agregar_participante',
                $participanteId,
                $nombreCompleto,
                [
                    'institucion_id' => $institucionId,
                    'participante' => $data
                ]
            );
            
            $_SESSION['mensaje'] = 'Participante agregado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al agregar participante (el RUT puede estar duplicado)';
        }
        
        header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $institucionId);
        exit;
    }
    
    /**
     * Eliminar participante
     */
    public function deleteParticipante() {
        $id = $_GET['id'] ?? null;
        $institucionId = $_GET['institucion_id'] ?? null;
        
        if (!$id || !$institucionId) {
            header('Location: ' . APP_URL . '/?url=instituciones');
            exit;
        }
        
        $participante = $this->institucionModel->getParticipanteById($id);
        
        if ($this->institucionModel->deleteParticipante($id)) {
            // Registrar en auditoría
            $this->auditoriaApp->log(
                'participantes',
                'eliminar_participante',
                $id,
                $participante['nombre_completo'] ?? 'Desconocido',
                ['antes' => $participante]
            );
            
            $_SESSION['mensaje'] = 'Participante eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar participante';
        }
        
        header('Location: ' . APP_URL . '/?url=instituciones/view&id=' . $institucionId);
        exit;
    }
}
