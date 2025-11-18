<?php
require_once __DIR__ . '/../models/Evento.php';
require_once __DIR__ . '/../models/AuditoriaApp.php';

class EventosController {
    private $eventoModel;
    private $auditoriaModel;

    public function __construct() {
        $this->eventoModel = new Evento();
        $this->auditoriaModel = new AuditoriaApp();
    }

    /**
     * Listar todos los eventos
     */
    public function index() {
        $eventos = $this->eventoModel->getAll();
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/eventos/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create() {
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/eventos/create.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Procesar creación de evento
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?url=eventos');
            exit;
        }

        // Validar campos requeridos
        if (empty($_POST['nombre']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_termino']) || empty($_POST['lugar'])) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header('Location: /?url=eventos/create');
            exit;
        }

        // Validar que fecha_termino sea posterior a fecha_inicio
        if (strtotime($_POST['fecha_termino']) < strtotime($_POST['fecha_inicio'])) {
            $_SESSION['error'] = 'La fecha de término debe ser posterior a la fecha de inicio';
            header('Location: /?url=eventos/create');
            exit;
        }

        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_termino' => $_POST['fecha_termino'],
            'lugar' => trim($_POST['lugar'])
        ];

        $eventoId = $this->eventoModel->create($data);

        if ($eventoId) {
            // Registrar en auditoría
            $this->auditoriaModel->log(
                'eventos',
                'crear',
                $eventoId,
                $data['nombre'],
                $data
            );

            $_SESSION['success'] = 'Evento creado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al crear el evento';
        }

        header('Location: /?url=eventos');
        exit;
    }

    /**
     * Mostrar detalle de un evento
     */
    public function view() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /?url=eventos');
            exit;
        }

        $evento = $this->eventoModel->getById($id);

        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            header('Location: /?url=eventos');
            exit;
        }

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/eventos/view.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /?url=eventos');
            exit;
        }

        $evento = $this->eventoModel->getById($id);

        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            header('Location: /?url=eventos');
            exit;
        }

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/eventos/edit.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Procesar actualización de evento
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?url=eventos');
            exit;
        }

        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Location: /?url=eventos');
            exit;
        }

        // Validar campos requeridos
        if (empty($_POST['nombre']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_termino']) || empty($_POST['lugar'])) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header('Location: /?url=eventos/edit&id=' . $id);
            exit;
        }

        // Validar que fecha_termino sea posterior a fecha_inicio
        if (strtotime($_POST['fecha_termino']) < strtotime($_POST['fecha_inicio'])) {
            $_SESSION['error'] = 'La fecha de término debe ser posterior a la fecha de inicio';
            header('Location: /?url=eventos/edit&id=' . $id);
            exit;
        }

        // Obtener datos anteriores para auditoría
        $eventoAnterior = $this->eventoModel->getById($id);

        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_termino' => $_POST['fecha_termino'],
            'lugar' => trim($_POST['lugar'])
        ];

        if ($this->eventoModel->update($id, $data)) {
            // Registrar en auditoría
            $this->auditoriaModel->log(
                'eventos',
                'editar',
                $id,
                $data['nombre'],
                [
                    'anterior' => $eventoAnterior,
                    'nuevo' => $data
                ]
            );

            $_SESSION['success'] = 'Evento actualizado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar el evento';
        }

        header('Location: /?url=eventos/view&id=' . $id);
        exit;
    }

    /**
     * Eliminar un evento
     */
    public function delete() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: /?url=eventos');
            exit;
        }

        $evento = $this->eventoModel->getById($id);

        if ($evento) {
            if ($this->eventoModel->delete($id)) {
                // Registrar en auditoría
                $this->auditoriaModel->log(
                    'eventos',
                    'eliminar',
                    $id,
                    $evento['nombre'],
                    $evento
                );

                $_SESSION['success'] = 'Evento eliminado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar el evento';
            }
        } else {
            $_SESSION['error'] = 'Evento no encontrado';
        }

        header('Location: /?url=eventos');
        exit;
    }
}
