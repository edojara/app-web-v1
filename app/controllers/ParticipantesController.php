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
        $instituciones = $this->institucionModel->getAll();
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/participantes/index.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $instituciones = $this->institucionModel->getAll();
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/participantes/create.php';
        require_once VIEWS_PATH . '/layout/footer.php';
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
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];

        $participanteId = $this->participanteModel->create($data);

        if ($participanteId) {
            // Registrar en auditoría
            $this->auditoriaModel->log(
                'participantes',
                'crear',
                $participanteId,
                $data['nombre_completo'],
                $data
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

        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/participantes/view.php';
        require_once VIEWS_PATH . '/layout/footer.php';
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
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/participantes/edit.php';
        require_once VIEWS_PATH . '/layout/footer.php';
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
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? '')
        ];

        if ($this->participanteModel->update($id, $data)) {
            // Registrar en auditoría
            $this->auditoriaModel->log(
                'participantes',
                'editar',
                $id,
                $data['nombre_completo'],
                [
                    'anterior' => $participanteAnterior,
                    'nuevo' => $data
                ]
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
            $this->auditoriaModel->log(
                'participantes',
                'eliminar',
                $id,
                $participante['nombre_completo'],
                $participante
            );

            $_SESSION['success'] = 'Participante eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el participante';
        }

        header('Location: /?url=participantes');
        exit;
    }

    /**
     * Exportar participantes a CSV
     */
    public function export() {
        $participantes = $this->participanteModel->getAll();
        
        // Configurar headers para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="participantes_' . date('Y-m-d_His') . '.csv"');
        
        // Crear salida CSV
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, ['Nombre Completo', 'RUT', 'Teléfono', 'Institución']);
        
        // Datos
        foreach ($participantes as $participante) {
            fputcsv($output, [
                $participante['nombre_completo'],
                $participante['rut'],
                $participante['telefono'] ?? '',
                $participante['institucion_nombre'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }

    /**
     * Mostrar formulario de importación
     */
    public function import() {
        $instituciones = $this->institucionModel->getAll();
        require_once VIEWS_PATH . '/layout/header.php';
        require_once VIEWS_PATH . '/participantes/import.php';
        require_once VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Procesar importación de CSV
     */
    public function processImport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?url=participantes/import');
            exit;
        }

        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Error al subir el archivo CSV';
            header('Location: /?url=participantes/import');
            exit;
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');
        
        if (!$handle) {
            $_SESSION['error'] = 'No se pudo abrir el archivo CSV';
            header('Location: /?url=participantes/import');
            exit;
        }

        // Leer BOM si existe
        $bom = fread($handle, 3);
        if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            rewind($handle);
        }

        $importados = 0;
        $errores = [];
        $linea = 0;

        // Saltar encabezado
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            $linea++;
            
            // Validar que tenga al menos 4 columnas (nombre, rut, telefono, institucion)
            if (count($data) < 4) {
                $errores[] = "Línea $linea: Datos incompletos";
                continue;
            }

            $nombre_completo = trim($data[0]);
            $rut = trim($data[1]);
            $telefono = trim($data[2] ?? '');
            $institucion_nombre = trim($data[3]);

            // Validar campos requeridos
            if (empty($nombre_completo) || empty($rut) || empty($institucion_nombre)) {
                $errores[] = "Línea $linea: Nombre, RUT e Institución son obligatorios";
                continue;
            }

            // Buscar institución por nombre
            $institucion = $this->institucionModel->getByNombre($institucion_nombre);
            if (!$institucion) {
                $errores[] = "Línea $linea: Institución '$institucion_nombre' no encontrada o inactiva";
                continue;
            }

            // Verificar si el RUT ya existe
            if ($this->participanteModel->rutExists($rut)) {
                $errores[] = "Línea $linea: El RUT $rut ya existe";
                continue;
            }

            // Crear participante
            $participanteData = [
                'institucion_id' => $institucion['id'],
                'nombre_completo' => $nombre_completo,
                'rut' => $rut,
                'telefono' => $telefono
            ];

            $participanteId = $this->participanteModel->create($participanteData);

            if ($participanteId) {
                $importados++;
                
                // Registrar en auditoría
                $this->auditoriaModel->log(
                    'participantes',
                    'importar',
                    $participanteId,
                    $participanteData['nombre_completo'],
                    $participanteData
                );
            } else {
                $errores[] = "Línea $linea: Error al importar participante";
            }
        }

        fclose($handle);

        // Mensaje de resultado
        if ($importados > 0) {
            $_SESSION['success'] = "Se importaron $importados participantes exitosamente";
        }

        if (!empty($errores)) {
            $_SESSION['error'] = "Errores encontrados: " . implode('; ', $errores);
        }

        header('Location: /?url=participantes');
        exit;
    }
}
