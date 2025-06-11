<?php
require_once MODELS_PATH . 'Pelicula.php';
require_once MODELS_PATH . 'Funcion.php';
require_once MODELS_PATH . 'Venta.php';
require_once MODELS_PATH . 'Sala.php';

class AdminController extends Controller {
    private $peliculaModel;
    private $funcionModel;
    private $ventaModel;
    private $salaModel;

    public function __construct() {
        // Verificar que el usuario sea administrador
        $this->requireLogin();
        $this->requireAdmin();
        
        $this->peliculaModel = new Pelicula();
        $this->funcionModel = new Funcion();
        $this->ventaModel = new Venta();
        $this->salaModel = new Sala();
    }

    // Dashboard principal del administrador
    public function index() {
        // Obtener estadísticas para el dashboard
        $datos = [
            'titulo' => 'Panel de Administración',
            'total_peliculas' => $this->peliculaModel->contarPeliculas(),
            'total_funciones' => $this->funcionModel->contarFunciones(),
            'total_ventas' => $this->ventaModel->contarVentas(),
            'total_salas' => $this->salaModel->contarSalas(),
            'ventas_hoy' => $this->ventaModel->ventasHoy(),
            'ingresos_mes' => $this->ventaModel->ingresosMes()
        ];

        $this->cargarVista('admin/dashboard', $datos);
    }

    // Gestión de películas
    public function peliculas() {
        $peliculas = $this->peliculaModel->obtenerTodas();
        
        $datos = [
            'titulo' => 'Gestión de Películas',
            'peliculas' => $peliculas
        ];

        $this->cargarVista('admin/peliculas/index', $datos);
    }

    // Gestión de funciones
    public function funciones() {
        $funciones = $this->funcionModel->obtenerTodasConDetalles();
        
        $datos = [
            'titulo' => 'Gestión de Funciones',
            'funciones' => $funciones
        ];

        $this->cargarVista('admin/funciones/index', $datos);
    }

    // Gestión de salas
    public function salas() {
        $salas = $this->salaModel->obtenerTodas();
        
        $datos = [
            'titulo' => 'Gestión de Salas',
            'salas' => $salas
        ];

        $this->cargarVista('admin/salas/index', $datos);
    }

    // Gestión de ventas
    public function ventas() {
        $ventas = $this->ventaModel->obtenerTodasConDetalles();
        
        $datos = [
            'titulo' => 'Gestión de Ventas',
            'ventas' => $ventas
        ];

        $this->cargarVista('admin/ventas/index', $datos);
    }

    // Reportes
    public function reportes() {
        $datos = [
            'titulo' => 'Reportes',
            'ventas_por_mes' => $this->ventaModel->ventasPorMes(),
            'peliculas_mas_vendidas' => $this->ventaModel->peliculasMasVendidas(),
            'ingresos_por_sala' => $this->ventaModel->ingresosPorSala()
        ];

        $this->cargarVista('admin/reportes', $datos);
    }
}
