<?php
require_once __DIR__ . '/../models/Participante.php';
require_once __DIR__ . '/../models/Institucion.php';
require_once __DIR__ . '/../models/AuditoriaApp.php';

class ParticipantesController {
    private $participanteModel;
    private $institucionModel;
    private $auditoriaModel;

    public function __construct() {
        $this->participanteModel = new Participante();
        $this->institucionModel = new Institucion();
        $this->auditoriaModel = new AuditoriaApp();
    }

    /**
     * Mostrar listado de participantes
     */
    public function index() {
        $participantes = $this->participanteModel->getAll();
        require_once __DIR__ . '/../views/participantes/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $instituciones = $this->institucionModel->getAll();
        require_once __DIR__ . '/../views/participantes/create.php';
    }

    /**
     * Procesar creación de participante
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?url=participantes');
            exit;
        }

        // Validar campos requeridos
        if (empty($_POST['nombre_completo']) || empty($_POST['rut']) || empty($_POST['institucion_id'])) {
            $_SESSION['error'] = 'El nombre completo, RUT e institución son obligatorios';
            header('Location: /?url=participantes/create');
            exit;
        }

        // Verificar si el RUT ya existe
        if ($this->participanteModel->rutExists($_POST['rut'])) {
            $_SESSION['error'] = 'El RUT ingresado ya está registrado';
            header('Location: /?url=participantes/create');
            exit;
        }

        $data = [
            'institucion_id' => $_POST['institucion_id'],
            'nombre_completo' => trim($_POST['nombre_completo']),
            'rut' => trim($_POST['rut']),
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        $participanteId = $this->participanteModel->create($data);

        if ($participanteId) {
            // Registrar en auditoría
            $this->auditoriaModel->registrar(
                'participantes',
                'crear_participante',
                $participanteId,
                $_SESSION['user_id'] ?? null,
                json_encode($data)
            );

            $_SESSION['success'] = 'Participante creado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al crear el participante';
        }

        header('Location: /?url=participantes');
        exit;
    }

    /**
     * Mostrar detalle de un participante
     */
    public function view() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /?url=participantes');
            exit;
        }

        $participante = $this->participanteModel->getById($id);
        
        if (!$participante) {
            $_SESSION['error'] = 'Participante no encontrado';
            header('Location: /?url=participantes');
            exit;
        }

        require_once __DIR__ . '/../views/participantes/view.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /?url=participantes');
            exit;
        }

        $participante = $this->participanteModel->getById($id);
        
        if (!$participante) {
            $_SESSION['error'] = 'Participante no encontrado';
            header('Location: /?url=participantes');
            exit;
        }

        $instituciones = $this->institucionModel->getAll();
        require_once __DIR__ . '/../views/participantes/edit.php';
    }

    /**
     * Procesar actualización de participante
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?url=participantes');
            exit;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /?url=participantes');
            exit;
        }

        // Validar campos requeridos
        if (empty($_POST['nombre_completo']) || empty($_POST['rut']) || empty($_POST['institucion_id'])) {
            $_SESSION['error'] = 'El nombre completo, RUT e institución son obligatorios';
            header('Location: /?url=participantes/edit&id=' . $id);
            exit;
        }

        // Verificar si el RUT ya existe (excluyendo el actual)
        if ($this->participanteModel->rutExists($_POST['rut'], $id)) {
            $_SESSION['error'] = 'El RUT ingresado ya está registrado por otro participante';
            header('Location: /?url=participantes/edit&id=' . $id);
            exit;
        }

        // Obtener datos anteriores para auditoría
        $participanteAnterior = $this->participanteModel->getById($id);

        $data = [
            'institucion_id' => $_POST['institucion_id'],
            'nombre_completo' => trim($_POST['nombre_completo']),
            'rut' => trim($_POST['rut']),
            'telefono' => trim($_POST['telefono'] ?? '')
        ];

        if ($this->participanteModel->update($id, $data)) {
            // Registrar en auditoría
            $this->auditoriaModel->registrar(
                'participantes',
                'editar_participante',
                $id,
                $_SESSION['user_id'] ?? null,
                json_encode([
                    'anterior' => $participanteAnterior,
                    'nuevo' => $data
                ])
            );

            $_SESSION['success'] = 'Participante actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el participante';
        }

        header('Location: /?url=participantes/view&id=' . $id);
        exit;
    }

    /**
     * Eliminar un participante
     */
    public function delete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: /?url=participantes');
            exit;
        }

        // Obtener datos del participante para auditoría
        $participante = $this->participanteModel->getById($id);

        if ($this->participanteModel->delete($id)) {
            // Registrar en auditoría
            $this->auditoriaModel->registrar(
                'participantes',
                'eliminar_participante',
                $id,
                $_SESSION['user_id'] ?? null,
                json_encode($participante)
            );

            $_SESSION['success'] = 'Participante eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el participante';
        }

        header('Location: /?url=participantes');
        exit;
    }
}
