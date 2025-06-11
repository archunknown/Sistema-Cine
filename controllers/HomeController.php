<?php
require_once MODELS_PATH . 'Pelicula.php';
require_once MODELS_PATH . 'Funcion.php';

class HomeController extends Controller {
    private $peliculaModel;
    private $funcionModel;
    private $promocionModel;

    public function __construct() {
        $this->peliculaModel = new Pelicula();
        $this->funcionModel = new Funcion();
        require_once MODELS_PATH . 'Promocion.php';
        $this->promocionModel = new Promocion();
    }

    public function index() {
        // Obtener películas activas con sus funciones
        $peliculas = $this->peliculaModel->obtenerPeliculasActivas();
        
        // Cargar la vista principal
        $this->cargarVista('cliente/inicio', [
            'titulo' => 'Cartelera',
            'peliculas' => $peliculas
        ]);
    }

    public function pelicula($id = null) {
        if ($id === null) {
            $this->redireccionar('');
            return;
        }

        // Obtener detalles de la película y sus funciones
        $pelicula = $this->peliculaModel->obtenerPorId($id);
        if (!$pelicula) {
            $this->redireccionar('error/404');
            return;
        }

        $funciones = $this->funcionModel->obtenerFuncionesPorPelicula($id);

        // Cargar la vista de detalles de película
        $this->cargarVista('cliente/pelicula', [
            'titulo' => $pelicula['titulo'],
            'pelicula' => $pelicula,
            'funciones' => $funciones
        ]);
    }

    public function error404() {
        $this->cargarVista('error/404', [
            'titulo' => 'Página no encontrada',
            'mensaje' => 'Lo sentimos, la página que buscas no existe.'
        ]);
    }

    public function error403() {
        $this->cargarVista('error/403', [
            'titulo' => 'Acceso denegado',
            'mensaje' => 'No tienes permiso para acceder a esta página.'
        ]);
    }
}
