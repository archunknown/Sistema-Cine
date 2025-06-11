<?php
require_once MODELS_PATH . 'Pelicula.php';
require_once MODELS_PATH . 'Funcion.php';
require_once BASE_PATH . '/core/ImageUploader.php';

class PeliculaController extends Controller
{
    private $peliculaModel;
    private $funcionModel;
    private $imageUploader;

    public function __construct()
    {
        $this->peliculaModel = new Pelicula();
        $this->funcionModel = new Funcion();

        // Asegurarse de que el directorio de uploads existe en la raíz del proyecto
        $uploadDir = BASE_PATH . '/uploads/Peliculas';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $this->imageUploader = new ImageUploader($uploadDir);
    }

    // Listar todas las películas (vista admin)
    public function index()
    {
        $this->requireAdmin();

        $peliculas = $this->peliculaModel->obtenerTodos();

        $this->cargarVista('admin/peliculas/index', [
            'titulo' => 'Gestión de Películas',
            'peliculas' => $peliculas
        ]);
    }

    // Mostrar formulario para crear película
    public function crear()
    {
        $this->requireAdmin();

        if ($this->esPost()) {
            $datos = [
                'titulo' => $this->getPost('titulo'),
                'sinopsis' => $this->getPost('sinopsis'),
                'duracion' => $this->getPost('duracion'),
                'clasificacion' => $this->getPost('clasificacion'),
                'genero' => $this->getPost('genero'),
                'director' => $this->getPost('director')
            ];

            if (empty($datos['titulo']) || empty($datos['duracion'])) {
                $this->setError('El título y la duración son obligatorios');
                $this->cargarVista('admin/peliculas/crear', ['datos' => $datos]);
                return;
            }

            // Manejar la subida de imagen usando ImageUploader
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->imageUploader->upload($_FILES['imagen'], $datos['titulo']);
                if ($uploadResult === false) {
                    $this->setError(implode(', ', $this->imageUploader->getErrors()));
                    $this->cargarVista('admin/peliculas/crear', ['datos' => $datos]);
                    return;
                }
                // Asegurar que la ruta use 'Peliculas' con P mayúscula
                $datos['imagen_ruta'] = 'uploads/Peliculas/' . $uploadResult['fileName'];
                Debug::log("PeliculaController: Imagen guardada con ruta: " . $datos['imagen_ruta']);
            }

            if ($this->peliculaModel->crear($datos)) {
                $this->setExito('Película creada exitosamente');
                $this->redireccionar('pelicula');
                return;
            } else {
                $this->setError('Error al crear la película');
            }
        }

        $this->cargarVista('admin/peliculas/crear', [
            'titulo' => 'Nueva Película'
        ]);
    }

    // Mostrar formulario para editar película
    public function editar($id = null)
    {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('pelicula');
            return;
        }

        $pelicula = $this->peliculaModel->obtenerPorId($id);
        if (!$pelicula) {
            $this->setError('Película no encontrada');
            $this->redireccionar('pelicula');
            return;
        }

        if ($this->esPost()) {
            $datos = [
                'titulo' => $this->getPost('titulo'),
                'sinopsis' => $this->getPost('sinopsis'),
                'duracion' => $this->getPost('duracion'),
                'clasificacion' => $this->getPost('clasificacion'),
                'genero' => $this->getPost('genero'),
                'director' => $this->getPost('director')
            ];

            if (empty($datos['titulo']) || empty($datos['duracion'])) {
                $this->setError('El título y la duración son obligatorios');
                $this->cargarVista('admin/peliculas/editar', [
                    'pelicula' => array_merge($pelicula, $datos)
                ]);
                return;
            }

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->imageUploader->upload($_FILES['imagen'], $datos['titulo']);
                if ($uploadResult === false) {
                    $this->setError(implode(', ', $this->imageUploader->getErrors()));
                    $this->cargarVista('admin/peliculas/editar', [
                        'pelicula' => array_merge($pelicula, $datos)
                    ]);
                    return;
                }
                $datos['imagen_ruta'] = 'uploads/Peliculas/' . $uploadResult['fileName'];

                // Eliminar imagen anterior si existe
                $rutaAnterior = $this->peliculaModel->obtenerImagenRuta($id);
                if ($rutaAnterior) {
                    $rutaCompletaAnterior = PUBLIC_PATH . str_replace('/', DIRECTORY_SEPARATOR, $rutaAnterior);
                    if (file_exists($rutaCompletaAnterior)) {
                        unlink($rutaCompletaAnterior);
                        Debug::log("PeliculaController: Imagen anterior eliminada: " . $rutaCompletaAnterior);
                    }
                }
            }

            if ($this->peliculaModel->actualizar($id, $datos)) {
                $this->setExito('Película actualizada exitosamente');
                $this->redireccionar('pelicula');
                return;
            } else {
                $this->setError('Error al actualizar la película');
            }
        }

        $this->cargarVista('admin/peliculas/editar', [
            'titulo' => 'Editar Película',
            'pelicula' => $pelicula
        ]);
    }

    // Eliminar película
    public function eliminar($id = null)
    {
        try {
            $this->requireAdmin();

            if ($id === null) {
                $this->setError('ID de película no válido');
                $this->redireccionar('pelicula');
                return;
            }

            // Verificar si la película existe
            $pelicula = $this->peliculaModel->obtenerPorId($id);
            if (!$pelicula) {
                $this->setError('La película no existe');
                $this->redireccionar('pelicula');
                return;
            }

            // Verificar si la película tiene funciones asociadas
            if ($this->funcionModel->peliculaTieneFunciones($id)) {
                $this->setError('No se puede desactivar la película porque tiene funciones asociadas. Debe desactivar primero las funciones relacionadas.');
                $this->redireccionar('pelicula');
                return;
            }

            // Iniciar transacción
            $this->peliculaModel->getDb()->beginTransaction();

            // Eliminar la imagen si existe
            $rutaImagen = $this->peliculaModel->obtenerImagenRuta($id);
            if ($rutaImagen) {
                $rutaCompleta = PUBLIC_PATH . str_replace('/', DIRECTORY_SEPARATOR, $rutaImagen);
                if (file_exists($rutaCompleta)) {
                    if (!unlink($rutaCompleta)) {
                        throw new Exception('Error al eliminar la imagen de la película');
                    }
                    Debug::log("PeliculaController: Imagen eliminada: " . $rutaCompleta);
                }
            }

            // Desactivar la película en lugar de eliminarla
            if (!$this->peliculaModel->actualizarEstado($id, false)) {
                throw new Exception('Error al desactivar la película');
            }

            // Confirmar transacción
            $this->peliculaModel->getDb()->commit();
            $this->setExito('Película desactivada exitosamente');

        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            if ($this->peliculaModel->getDb()->inTransaction()) {
                $this->peliculaModel->getDb()->rollBack();
            }
            Debug::log("Error en PeliculaController::eliminar: " . $e->getMessage());
            $this->setError('Error al desactivar la película: ' . $e->getMessage());
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if ($this->peliculaModel->getDb()->inTransaction()) {
                $this->peliculaModel->getDb()->rollBack();
            }
            Debug::log("Error en PeliculaController::eliminar: " . $e->getMessage());
            $this->setError($e->getMessage());
        }

        $this->redireccionar('pelicula');
    }

    // Cambiar estado de la película
    public function cambiarEstado($id = null)
    {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('pelicula');
            return;
        }

        $pelicula = $this->peliculaModel->obtenerPorId($id);
        if ($pelicula) {
            $nuevoEstado = !$pelicula['estado'];
            if ($this->peliculaModel->actualizarEstado($id, $nuevoEstado)) {
                $this->setExito('Estado actualizado exitosamente');
            } else {
                $this->setError('Error al actualizar el estado');
            }
        } else {
            $this->setError('Película no encontrada');
        }

        $this->redireccionar('pelicula');
    }

    // Método para borrar imagen
    public function borrarImagen($id = null)
    {
        $this->requireAdmin();

        if ($id === null) {
            $this->redireccionar('pelicula');
            return;
        }

        $rutaImagen = $this->peliculaModel->obtenerImagenRuta($id);
        if ($rutaImagen) {
            $rutaCompleta = PUBLIC_PATH . str_replace('/', DIRECTORY_SEPARATOR, $rutaImagen);
            if (file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
                Debug::log("PeliculaController: Imagen eliminada: " . $rutaCompleta);
            }
        }

        if ($this->peliculaModel->borrarImagen($id)) {
            $this->setExito('Imagen eliminada exitosamente');
        } else {
            $this->setError('Error al eliminar la imagen');
        }

        $this->redireccionar("pelicula/editar/$id");
    }
}
