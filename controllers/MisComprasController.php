<?php
require_once MODELS_PATH . 'Venta.php';
require_once MODELS_PATH . 'Boleta.php';
require_once __DIR__ . '/../core/Debug.php';

class MisComprasController extends Controller {
    private $ventaModel;
    private $boletaModel;

    public function __construct() {
        $this->ventaModel = new Venta();
        $this->boletaModel = new Boleta();
    }

    public function index() {
        Debug::log("MisComprasController: Iniciando método index");
        
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            Debug::log("MisComprasController: Usuario no autenticado, redirigiendo a login");
            $this->redireccionar('auth/login');
            return;
        }

        Debug::log("MisComprasController: Usuario autenticado - ID: " . $_SESSION['usuario_id']);

        // Verificar que no sea administrador
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin') {
            Debug::log("MisComprasController: Usuario es admin, redirigiendo a inicio");
            $this->redireccionar('');
            return;
        }

        Debug::log("MisComprasController: Usuario es cliente, obteniendo compras");

        // Obtener las compras del usuario
        $email = $_SESSION['usuario_email'] ?? '';
        Debug::log("MisComprasController: Email del usuario: " . $email);
        
        $compras = $this->ventaModel->obtenerVentasPorCliente($email);
        Debug::log("MisComprasController: Compras encontradas: " . count($compras));
        Debug::log("MisComprasController: SQL Debug - Email usado para búsqueda: " . $email);
        
        if (empty($compras)) {
            Debug::log("MisComprasController: No se encontraron compras para el email: " . $email);
            // Verificar si hay ventas en la base de datos
            $sql = "SELECT COUNT(*) as total FROM ventas";
            $resultado = $this->ventaModel->ejecutarConsulta($sql);
            $totalVentas = $resultado->fetch(PDO::FETCH_ASSOC)['total'];
            Debug::log("MisComprasController: Total de ventas en la base de datos: " . $totalVentas);
        }
        
        // Para cada compra, obtener sus boletas
        foreach ($compras as &$compra) {
            $compra['boletas'] = $this->boletaModel->obtenerBoletasPorVenta($compra['id']);
        }

        Debug::log("MisComprasController: Cargando vista cliente/mis-compras");
        $this->cargarVista('cliente/mis-compras', [
            'titulo' => 'Mis Compras',
            'compras' => $compras
        ]);
    }
}
