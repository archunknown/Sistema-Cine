<?php
require_once MODELS_PATH . 'Venta.php';
require_once MODELS_PATH . 'Boleta.php';
require_once MODELS_PATH . 'Funcion.php';
require_once MODELS_PATH . 'Pelicula.php';

class VentaController extends Controller {
    private $ventaModel;
    private $boletaModel;
    private $funcionModel;
    private $peliculaModel;

    public function __construct() {
        $this->ventaModel = new Venta();
        $this->boletaModel = new Boleta();
        $this->funcionModel = new Funcion();
        $this->peliculaModel = new Pelicula();
    }

    // Vista de administración de ventas
    public function index() {
        $this->requireAdmin();
        
        $ventas = $this->ventaModel->obtenerTodasConDetalles();
        
        $this->cargarVista('admin/ventas/index', [
            'titulo' => 'Gestión de Ventas',
            'ventas' => $ventas
        ]);
    }

    // Iniciar proceso de compra
    public function comprar($funcionId = null) {
        Debug::log("VentaController: Iniciando compra con ID: " . $funcionId);
        
        if ($funcionId === null) {
            Debug::log("VentaController: ID de función es null");
            $this->redireccionar('');
            return;
        }

        // Obtener detalles de la función
        Debug::log("VentaController: Buscando función con ID: " . $funcionId);
        $funcion = $this->funcionModel->obtenerDetallesCompletos($funcionId);
        
        if (!$funcion) {
            Debug::log("VentaController: Función no encontrada en la base de datos");
            $this->setError('Función no encontrada');
            $this->redireccionar('');
            return;
        }

        Debug::log("VentaController: Función encontrada: " . json_encode($funcion));

        // Verificar si la función está activa
        if (!$funcion['estado']) {
            $this->setError('Esta función no está disponible');
            $this->redireccionar('');
            return;
        }

        // Verificar si la función no ha pasado
        if (strtotime($funcion['fecha'] . ' ' . $funcion['hora']) < time()) {
            $this->setError('Esta función ya ha pasado');
            $this->redireccionar('');
            return;
        }

        // Obtener asientos ocupados
        $asientosOcupados = $this->funcionModel->obtenerAsientosOcupados($funcionId);

        $this->cargarVista('cliente/compra', [
            'titulo' => 'Comprar Entradas',
            'funcion' => $funcion,
            'asientosOcupados' => $asientosOcupados
        ]);
    }

    // Procesar la compra
    public function procesar() {
        if (!$this->esPost()) {
            $this->redireccionar('');
            return;
        }

        $funcionId = $this->getPost('funcion_id');
        $asientos = $this->getPost('asientos');
        $datos = [
            'cliente_nombre' => $this->getPost('cliente_nombre'),
            'cliente_email' => $this->getPost('cliente_email'),
            'cliente_dni' => $this->getPost('cliente_dni')
        ];

        // Validar campos requeridos
        if (empty($funcionId) || empty($asientos) || 
            empty($datos['cliente_nombre']) || 
            empty($datos['cliente_email']) || 
            empty($datos['cliente_dni'])) {
            $this->setError('Todos los campos son requeridos');
            $this->redireccionar("comprar/$funcionId");
            return;
        }

        // Validar formato de email
        if (!filter_var($datos['cliente_email'], FILTER_VALIDATE_EMAIL)) {
            $this->setError('El email no es válido');
            $this->redireccionar("comprar/$funcionId");
            return;
        }

        // Obtener función y calcular total
        $funcion = $this->funcionModel->obtenerPorId($funcionId);
        if (!$funcion) {
            $this->setError('Función no encontrada');
            $this->redireccionar('');
            return;
        }

        // Convertir string de asientos a array
        $asientosArray = explode(',', $asientos);
        $total = count($asientosArray) * $funcion['precio'];

        // Iniciar transacción
        $this->ventaModel->iniciarTransaccion();

        try {
            // Crear venta
            $datos['total'] = $total;
            $ventaId = $this->ventaModel->crear($datos);

            if (!$ventaId) {
                throw new Exception('Error al crear la venta');
            }

            // Crear boletas
            foreach ($asientosArray as $asiento) {
                if (!$this->boletaModel->crear([
                    'venta_id' => $ventaId,
                    'funcion_id' => $funcionId,
                    'asiento' => trim($asiento)
                ])) {
                    throw new Exception('Error al crear las boletas');
                }
            }

            // Completar venta
            if (!$this->ventaModel->completar($ventaId)) {
                throw new Exception('Error al completar la venta');
            }

            // Confirmar transacción
            $this->ventaModel->confirmarTransaccion();
            
            // Redirigir a la página de confirmación
            $this->redireccionar("venta/confirmacion/$ventaId");

        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->ventaModel->revertirTransaccion();
            $this->setError('Error al procesar la compra: ' . $e->getMessage());
            $this->redireccionar("comprar/$funcionId");
        }
    }

    // Mostrar confirmación de compra
    public function confirmacion($ventaId = null) {
        if ($ventaId === null) {
            $this->redireccionar('');
            return;
        }

        $venta = $this->ventaModel->obtenerDetallesCompletos($ventaId);
        if (!$venta) {
            $this->setError('Venta no encontrada');
            $this->redireccionar('');
            return;
        }

        $this->cargarVista('cliente/confirmacion', [
            'titulo' => 'Confirmación de Compra',
            'venta' => $venta
        ]);
    }

    // Ver detalles de una venta
    public function detalles($ventaId = null) {
        // Requerir que el usuario esté logueado
        if (!isset($_SESSION['usuario_id'])) {
            $this->setError('Debes iniciar sesión para ver los detalles');
            $this->redireccionar('auth/login');
            return;
        }

        if ($ventaId === null) {
            $this->redireccionar(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin' ? 'venta' : 'mis-compras');
            return;
        }

        $venta = $this->ventaModel->obtenerDetallesCompletos($ventaId);
        if (!$venta) {
            $this->setError('Venta no encontrada');
            $this->redireccionar(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin' ? 'venta' : 'mis-compras');
            return;
        }

        // Permitir acceso si es admin o dueño de la venta
        $esAdmin = isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin';
        $esDueno = isset($_SESSION['usuario_email']) && $venta['cliente_email'] === $_SESSION['usuario_email'];

        if (!$esAdmin && !$esDueno) {
            $this->setError('No tienes permiso para ver estos detalles');
            $this->redireccionar('mis-compras');
            return;
        }

        // Cargar la vista correspondiente según el rol
        $vista = $esAdmin ? 'admin/ventas/detalles' : 'cliente/detalles';
        $this->cargarVista($vista, [
            'titulo' => 'Detalles de Venta',
            'venta' => $venta
        ]);
    }

    // Descargar boletas de una venta
    public function descargarBoletas($ventaId = null) {
        // Redirigir a la vista de detalles
        $this->detalles($ventaId);
    }

    // Cancelar venta (admin)
    public function cancelar($ventaId = null) {
        $this->requireAdmin();

        if ($ventaId === null) {
            $this->redireccionar('venta');
            return;
        }

        if ($this->ventaModel->cancelar($ventaId)) {
            $this->setExito('Venta cancelada exitosamente');
        } else {
            $this->setError('Error al cancelar la venta');
        }

        $this->redireccionar('venta');
    }
}
