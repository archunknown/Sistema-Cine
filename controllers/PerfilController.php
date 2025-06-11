<?php
require_once MODELS_PATH . 'Usuario.php';
require_once MODELS_PATH . 'Venta.php';

class PerfilController extends Controller {
    private $usuarioModel;
    private $ventaModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        $this->ventaModel = new Venta();
    }

    public function index() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            $this->redireccionar('auth/login');
            return;
        }

        // Obtener datos del usuario
        $usuario = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        
        if (!$usuario) {
            $this->redireccionar('');
            return;
        }

        // Cargar la vista del perfil según el rol
        if ($_SESSION['usuario_rol'] === 'admin') {
            $this->cargarVista('cliente/perfil', [
                'titulo' => 'Mi Perfil',
                'usuario' => $usuario
            ]);
        } else {
            // Para clientes, obtener sus últimas compras
            $ultimas_compras = $this->ventaModel->obtenerVentasPorCliente($usuario['email']);
            
            // Cargar vista perfil_cliente con las compras
            $this->cargarVista('cliente/perfil_cliente', [
                'titulo' => 'Mi Perfil',
                'usuario' => $usuario,
                'ultimas_compras' => $ultimas_compras
            ]);
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redireccionar('perfil');
            return;
        }

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario_id'])) {
            $this->redireccionar('auth/login');
            return;
        }

        // Verificar que el usuario exista
        $usuario_actual = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        if (!$usuario_actual) {
            $this->redireccionar('');
            return;
        }

        $datos = [
            'id' => $_SESSION['usuario_id'],
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? '')
        ];

        // Validar datos básicos
        if (empty($datos['nombre']) || empty($datos['email'])) {
            $_SESSION['error'] = 'El nombre y email son obligatorios';
            $this->redireccionar('perfil');
            return;
        }

        // Si el email ha cambiado, verificar que no exista
        $usuario_actual = $this->usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        if ($datos['email'] !== $usuario_actual['email']) {
            if ($this->usuarioModel->emailExiste($datos['email'])) {
                $_SESSION['error'] = 'El correo electrónico ya está registrado';
                $this->redireccionar('perfil');
                return;
            }
        }

        // Verificar si se está intentando cambiar la contraseña
        $password_actual = trim($_POST['password_actual'] ?? '');
        $password_nuevo = trim($_POST['password_nuevo'] ?? '');
        $password_confirmar = trim($_POST['password_confirmar'] ?? '');

        if (!empty($password_actual)) {
            // Validar que la contraseña actual sea correcta
            if (!$this->usuarioModel->verificarPassword($_SESSION['usuario_id'], $password_actual)) {
                $_SESSION['error'] = 'La contraseña actual es incorrecta';
                $this->redireccionar('perfil');
                return;
            }

            // Validar nueva contraseña
            if (empty($password_nuevo) || empty($password_confirmar)) {
                $_SESSION['error'] = 'Debe completar todos los campos de contraseña';
                $this->redireccionar('perfil');
                return;
            }

            if ($password_nuevo !== $password_confirmar) {
                $_SESSION['error'] = 'Las contraseñas no coinciden';
                $this->redireccionar('perfil');
                return;
            }

            if (strlen($password_nuevo) < 6) {
                $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
                $this->redireccionar('perfil');
                return;
            }

            $datos['password'] = $password_nuevo;
        }

        // Actualizar perfil
        if ($this->usuarioModel->actualizar($datos)) {
            $_SESSION['mensaje'] = 'Perfil actualizado correctamente';
            
            // Actualizar el nombre en la sesión
            $_SESSION['usuario_nombre'] = $datos['nombre'];
        } else {
            $_SESSION['error'] = 'Error al actualizar el perfil';
        }

        $this->redireccionar('perfil');
    }
}
