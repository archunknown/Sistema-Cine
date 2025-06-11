<?php
require_once MODELS_PATH . 'Sala.php';

class SalaController extends Controller {
    private $salaModel;

    public function __construct() {
        $this->salaModel = new Sala();
    }

    // Listar todas las salas
    public function index() {
        $this->requireAdmin();

        $salas = $this->salaModel->obtenerTodas();
        
        $this->cargarVista('admin/salas/index', [
            'titulo' => 'Gestión de Salas',
            'salas' => $salas
        ]);
    }

    // Mostrar formulario para crear sala
    public function crear() {
        $this->requireAdmin();

        if ($this->esPost()) {
            $datos = [
                'nombre' => $this->getPost('nombre'),
                'capacidad' => $this->getPost('capacidad')
            ];

            // Validar campos requeridos
            if (empty($datos['nombre']) || empty($datos['capacidad'])) {
                $this->setError('Todos los campos son requeridos');
                $this->cargarVista('admin/salas/crear', ['datos' => $datos]);
                return;
            }

            // Validar capacidad
            if (!is_numeric($datos['capacidad']) || $datos['capacidad'] < 1) {
                $this->setError('La capacidad debe ser un número positivo');
                $this->cargarVista('admin/salas/crear', ['datos' => $datos]);
                return;
            }

            // Intentar crear la sala
            if ($this->salaModel->crear($datos)) {
                $this->setExito('Sala creada exitosamente');
                $this->redireccionar('sala');
                return;
            } else {
                $this->setError('Error al crear la sala');
            }
        }

        $this->cargarVista('admin/salas/crear', [
            'titulo' => 'Nueva Sala'
        ]);
    }

    // Mostrar formulario para editar sala
    public function editar($id = null) {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('sala');
            return;
        }

        $sala = $this->salaModel->obtenerPorId($id);
        if (!$sala) {
            $this->setError('Sala no encontrada');
            $this->redireccionar('sala');
            return;
        }

        if ($this->esPost()) {
            $datos = [
                'nombre' => $this->getPost('nombre'),
                'capacidad' => $this->getPost('capacidad')
            ];

            // Validar campos requeridos
            if (empty($datos['nombre']) || empty($datos['capacidad'])) {
                $this->setError('Todos los campos son requeridos');
                $this->cargarVista('admin/salas/editar', [
                    'sala' => array_merge($sala, $datos)
                ]);
                return;
            }

            // Validar capacidad
            if (!is_numeric($datos['capacidad']) || $datos['capacidad'] < 1) {
                $this->setError('La capacidad debe ser un número positivo');
                $this->cargarVista('admin/salas/editar', [
                    'sala' => array_merge($sala, $datos)
                ]);
                return;
            }

            // Intentar actualizar la sala
            if ($this->salaModel->actualizar($id, $datos)) {
                $this->setExito('Sala actualizada exitosamente');
                $this->redireccionar('sala');
                return;
            } else {
                $this->setError('Error al actualizar la sala');
            }
        }

        $this->cargarVista('admin/salas/editar', [
            'titulo' => 'Editar Sala',
            'sala' => $sala
        ]);
    }

    // Eliminar sala
    public function eliminar($id = null) {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('sala');
            return;
        }

        // Verificar si hay funciones asociadas
        if ($this->salaModel->tieneFunciones($id)) {
            $this->setError('No se puede eliminar la sala porque tiene funciones asociadas');
            $this->redireccionar('sala');
            return;
        }

        if ($this->salaModel->eliminar($id)) {
            $this->setExito('Sala eliminada exitosamente');
        } else {
            $this->setError('Error al eliminar la sala');
        }

        $this->redireccionar('sala');
    }

    // Cambiar estado de la sala
    public function cambiarEstado($id = null) {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('sala');
            return;
        }

        $sala = $this->salaModel->obtenerPorId($id);
        if ($sala) {
            $nuevoEstado = !$sala['estado'];
            if ($this->salaModel->actualizarEstado($id, $nuevoEstado)) {
                $this->setExito('Estado actualizado exitosamente');
            } else {
                $this->setError('Error al actualizar el estado');
            }
        } else {
            $this->setError('Sala no encontrada');
        }

        $this->redireccionar('sala');
    }

    // Verificar disponibilidad de sala
    public function verificarDisponibilidad() {
        $this->requireAdmin();

        if (!$this->esPost()) {
            $this->redireccionar('sala');
            return;
        }

        $salaId = $this->getPost('sala_id');
        $fecha = $this->getPost('fecha');
        $hora = $this->getPost('hora');

        $disponible = $this->salaModel->estaDisponible($salaId, $fecha, $hora);
        
        // Devolver respuesta JSON
        header('Content-Type: application/json');
        echo json_encode(['disponible' => $disponible]);
        exit;
    }
}
