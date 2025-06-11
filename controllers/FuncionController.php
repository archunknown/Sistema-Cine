<?php
require_once MODELS_PATH . 'Funcion.php';
require_once MODELS_PATH . 'Pelicula.php';
require_once MODELS_PATH . 'Sala.php';

class FuncionController extends Controller {
    private $funcionModel;
    private $peliculaModel;
    private $salaModel;

    public function __construct() {
        $this->funcionModel = new Funcion();
        $this->peliculaModel = new Pelicula();
        $this->salaModel = new Sala();
    }

    // Listar todas las funciones (vista admin)
    public function index() {
        $this->requireAdmin();

        $funciones = $this->funcionModel->obtenerTodasConDetalles();
        
        $this->cargarVista('admin/funciones/index', [
            'titulo' => 'Gestión de Funciones',
            'funciones' => $funciones
        ]);
    }

    // Mostrar formulario para crear función
    public function crear() {
        $this->requireAdmin();

        // Obtener películas y salas para el formulario
        $peliculas = $this->peliculaModel->obtenerPeliculasActivas();
        $salas = $this->salaModel->obtenerSalasActivas();

        if ($this->esPost()) {
            $datos = [
                'pelicula_id' => $this->getPost('pelicula_id'),
                'sala_id' => $this->getPost('sala_id'),
                'fecha' => $this->getPost('fecha'),
                'hora' => $this->getPost('hora'),
                'precio' => $this->getPost('precio')
            ];

            // Validar campos requeridos
            if (empty($datos['pelicula_id']) || empty($datos['sala_id']) || 
                empty($datos['fecha']) || empty($datos['hora']) || empty($datos['precio'])) {
                $this->setError('Todos los campos son requeridos');
                $this->cargarVista('admin/funciones/crear', [
                    'peliculas' => $peliculas,
                    'salas' => $salas,
                    'datos' => $datos
                ]);
                return;
            }

            // Validar que la fecha no sea anterior a hoy
            if (strtotime($datos['fecha']) < strtotime(date('Y-m-d'))) {
                $this->setError('La fecha no puede ser anterior a hoy');
                $this->cargarVista('admin/funciones/crear', [
                    'peliculas' => $peliculas,
                    'salas' => $salas,
                    'datos' => $datos
                ]);
                return;
            }

            // Verificar disponibilidad de sala
            if (!$this->funcionModel->verificarDisponibilidadSala($datos['sala_id'], $datos['fecha'], $datos['hora'])) {
                $this->setError('La sala no está disponible en ese horario');
                $this->cargarVista('admin/funciones/crear', [
                    'peliculas' => $peliculas,
                    'salas' => $salas,
                    'datos' => $datos
                ]);
                return;
            }

            // Intentar crear la función
            if ($this->funcionModel->crear($datos)) {
                $this->setExito('Función creada exitosamente');
                $this->redireccionar('funcion');
                return;
            } else {
                $this->setError('Error al crear la función');
            }
        }

        $this->cargarVista('admin/funciones/crear', [
            'titulo' => 'Nueva Función',
            'peliculas' => $peliculas,
            'salas' => $salas
        ]);
    }

    // Mostrar formulario para editar función
    public function editar($id = null) {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('funcion');
            return;
        }

        $funcion = $this->funcionModel->obtenerPorId($id);
        if (!$funcion) {
            $this->setError('Función no encontrada');
            $this->redireccionar('funcion');
            return;
        }

        // Obtener películas y salas para el formulario
        $peliculas = $this->peliculaModel->obtenerPeliculasActivas();
        $salas = $this->salaModel->obtenerSalasActivas();

        if ($this->esPost()) {
            $datos = [
                'pelicula_id' => $this->getPost('pelicula_id'),
                'sala_id' => $this->getPost('sala_id'),
                'fecha' => $this->getPost('fecha'),
                'hora' => $this->getPost('hora'),
                'precio' => $this->getPost('precio')
            ];

            // Validar campos requeridos
            if (empty($datos['pelicula_id']) || empty($datos['sala_id']) || 
                empty($datos['fecha']) || empty($datos['hora']) || empty($datos['precio'])) {
                $this->setError('Todos los campos son requeridos');
                $this->cargarVista('admin/funciones/editar', [
                    'funcion' => array_merge($funcion, $datos),
                    'peliculas' => $peliculas,
                    'salas' => $salas
                ]);
                return;
            }

            // Verificar disponibilidad de sala (excluyendo la función actual)
            if (!$this->funcionModel->verificarDisponibilidadSala(
                $datos['sala_id'], 
                $datos['fecha'], 
                $datos['hora'],
                $id
            )) {
                $this->setError('La sala no está disponible en ese horario');
                $this->cargarVista('admin/funciones/editar', [
                    'funcion' => array_merge($funcion, $datos),
                    'peliculas' => $peliculas,
                    'salas' => $salas
                ]);
                return;
            }

            // Intentar actualizar la función
            if ($this->funcionModel->actualizar($id, $datos)) {
                $this->setExito('Función actualizada exitosamente');
                $this->redireccionar('funcion');
                return;
            } else {
                $this->setError('Error al actualizar la función');
            }
        }

        $this->cargarVista('admin/funciones/editar', [
            'titulo' => 'Editar Función',
            'funcion' => $funcion,
            'peliculas' => $peliculas,
            'salas' => $salas
        ]);
    }

    // Eliminar función
    public function eliminar($id = null) {
        try {
            $this->requireAdmin();

            if ($id === null) {
                $this->setError('ID de función no válido');
                $this->redireccionar('funcion');
                return;
            }

            // Verificar si la función existe
            $funcion = $this->funcionModel->obtenerPorId($id);
            if (!$funcion) {
                $this->setError('La función no existe');
                $this->redireccionar('funcion');
                return;
            }

            // Verificar si hay boletas vendidas para esta función
            if ($this->funcionModel->tieneBoletas($id)) {
                $this->setError('No se puede eliminar la función porque tiene boletas vendidas. Debe cancelar las boletas primero.');
                $this->redireccionar('funcion');
                return;
            }

            // Iniciar transacción
            $this->funcionModel->getDb()->beginTransaction();

            // Desactivar la función en lugar de eliminarla
            if (!$this->funcionModel->actualizarEstado($id, false)) {
                throw new Exception('Error al desactivar la función');
            }

            // Confirmar transacción
            $this->funcionModel->getDb()->commit();
            $this->setExito('Función desactivada exitosamente');

        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            if ($this->funcionModel->getDb()->inTransaction()) {
                $this->funcionModel->getDb()->rollBack();
            }
            Debug::log("Error en FuncionController::eliminar: " . $e->getMessage());
            $this->setError('Error al desactivar la función: ' . $e->getMessage());
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if ($this->funcionModel->getDb()->inTransaction()) {
                $this->funcionModel->getDb()->rollBack();
            }
            Debug::log("Error en FuncionController::eliminar: " . $e->getMessage());
            $this->setError($e->getMessage());
        }

        $this->redireccionar('funcion');
    }

    // Cambiar estado de la función
    public function cambiarEstado($id = null) {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('funcion');
            return;
        }

        $funcion = $this->funcionModel->obtenerPorId($id);
        if ($funcion) {
            $nuevoEstado = !$funcion['estado'];
            if ($this->funcionModel->actualizarEstado($id, $nuevoEstado)) {
                $this->setExito('Estado actualizado exitosamente');
            } else {
                $this->setError('Error al actualizar el estado');
            }
        } else {
            $this->setError('Función no encontrada');
        }

        $this->redireccionar('funcion');
    }
}
