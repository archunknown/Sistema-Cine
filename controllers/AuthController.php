<?php
require_once MODELS_PATH . 'Usuario.php';

class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    // Mostrar formulario de login
    public function login() {
        // Si ya está autenticado, redirigir según rol
        if (isset($_SESSION['usuario_id'])) {
            $this->redireccionar($_SESSION['usuario_rol'] === 'admin' ? 'admin' : '');
            return;
        }

        // Si es una petición POST, procesar el login
        if ($this->esPost()) {
            $email = $this->getPost('email');
            $password = $this->getPost('password');

            // Validar campos requeridos
            if (empty($email) || empty($password)) {
                $this->setError('Todos los campos son requeridos');
                $this->cargarVista('auth/login');
                return;
            }

            // Intentar autenticar
            $usuario = $this->usuarioModel->autenticar($email, $password);

            if ($usuario) {
                // Guardar datos en sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['usuario_email'] = $usuario['email'];

                // Redirigir según rol
                $this->redireccionar($usuario['rol'] === 'admin' ? 'admin' : '');
                return;
            } else {
                $this->setError('Email o contraseña incorrectos');
            }
        }

        $this->cargarVista('auth/login');
    }

    // Mostrar formulario de registro
    public function registro() {
        // Si ya está autenticado, redirigir según rol
        if (isset($_SESSION['usuario_id'])) {
            $this->redireccionar($_SESSION['usuario_rol'] === 'admin' ? 'admin' : '');
            return;
        }

        // Si es una petición POST, procesar el registro
        if ($this->esPost()) {
            $datos = [
                'nombre' => $this->getPost('nombre'),
                'email' => $this->getPost('email'),
                'password' => $this->getPost('password'),
                'confirmar_password' => $this->getPost('confirmar_password')
            ];

            // Validar campos requeridos
            foreach ($datos as $campo => $valor) {
                if (empty($valor)) {
                    $this->setError('Todos los campos son requeridos');
                    $this->cargarVista('auth/registro');
                    return;
                }
            }

            // Validar que las contraseñas coincidan
            if ($datos['password'] !== $datos['confirmar_password']) {
                $this->setError('Las contraseñas no coinciden');
                $this->cargarVista('auth/registro');
                return;
            }

            // Validar longitud mínima de contraseña
            if (strlen($datos['password']) < 6) {
                $this->setError('La contraseña debe tener al menos 6 caracteres');
                $this->cargarVista('auth/registro');
                return;
            }

            // Intentar registrar
            if ($this->usuarioModel->registrar($datos)) {
                $this->setExito('Registro exitoso. Por favor, inicia sesión.');
                $this->redireccionar('auth/login');
                return;
            } else {
                $this->setError('El email ya está registrado');
            }
        }

        $this->cargarVista('auth/registro');
    }

    // Cerrar sesión
    public function logout() {
        // Destruir todas las variables de sesión
        session_unset();
        session_destroy();

        // Redirigir al login
        $this->redireccionar('auth/login');
    }

    // Cambiar contraseña
    public function cambiarPassword() {
        // Verificar si está autenticado
        $this->requireLogin();

        if ($this->esPost()) {
            $passwordActual = $this->getPost('password_actual');
            $passwordNuevo = $this->getPost('password_nuevo');
            $passwordConfirmar = $this->getPost('password_confirmar');

            // Validar campos requeridos
            if (empty($passwordActual) || empty($passwordNuevo) || empty($passwordConfirmar)) {
                $this->setError('Todos los campos son requeridos');
                $this->cargarVista('auth/cambiar-password');
                return;
            }

            // Validar que las contraseñas nuevas coincidan
            if ($passwordNuevo !== $passwordConfirmar) {
                $this->setError('Las contraseñas nuevas no coinciden');
                $this->cargarVista('auth/cambiar-password');
                return;
            }

            // Validar longitud mínima
            if (strlen($passwordNuevo) < 6) {
                $this->setError('La contraseña debe tener al menos 6 caracteres');
                $this->cargarVista('auth/cambiar-password');
                return;
            }

            // Actualizar contraseña
            if ($this->usuarioModel->actualizarDatos($_SESSION['usuario_id'], [
                'password' => $passwordNuevo
            ])) {
                $this->setExito('Contraseña actualizada correctamente');
                $this->redireccionar('');
                return;
            } else {
                $this->setError('No se pudo actualizar la contraseña');
            }
        }

        $this->cargarVista('auth/cambiar-password');
    }
}