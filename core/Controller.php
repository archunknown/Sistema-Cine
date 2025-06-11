<?php
abstract class Controller {
    // Método para cargar una vista
    protected function cargarVista($vista, $datos = []) {
        // Extraer los datos para que estén disponibles en la vista
        if (!empty($datos)) {
            extract($datos);
        }

        // Ruta completa de la vista
        $rutaVista = VIEWS_PATH . $vista . '.php';

        // Verificar si existe la vista
        if (file_exists($rutaVista)) {
            // Iniciar el buffer de salida
            ob_start();
            
            // Incluir header si existe y no se ha especificado lo contrario
            if (!isset($datos['sin_header'])) {
                include_once VIEWS_PATH . 'layouts/header.php';
            }

            // Incluir la vista
            include_once $rutaVista;

            // Incluir footer si existe y no se ha especificado lo contrario
            if (!isset($datos['sin_footer'])) {
                include_once VIEWS_PATH . 'layouts/footer.php';
            }

            // Obtener el contenido del buffer y limpiarlo
            $contenido = ob_get_clean();
            
            // Mostrar el contenido
            echo $contenido;
        } else {
            // Si la vista no existe, mostrar error
            die("Error: La vista {$vista} no existe");
        }
    }

    // Método para redireccionar
    protected function redireccionar($url) {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }

    // Método para validar si es una petición POST
    protected function esPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Método para obtener datos POST
    protected function getPost($campo = null) {
        if ($campo === null) {
            return $_POST;
        }
        return isset($_POST[$campo]) ? $_POST[$campo] : null;
    }

    // Método para obtener datos GET
    protected function getGet($campo = null) {
        if ($campo === null) {
            return $_GET;
        }
        return isset($_GET[$campo]) ? $_GET[$campo] : null;
    }

    // Método para validar si el usuario está autenticado
    protected function requireLogin() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redireccionar('auth/login');
            exit;
        }
    }

    // Método para validar si el usuario es administrador
    protected function requireAdmin() {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            $this->redireccionar('error/403');
            exit;
        }
    }

    // Método para enviar respuesta JSON
    protected function jsonResponse($data, $codigo = 200) {
        header('Content-Type: application/json');
        http_response_code($codigo);
        echo json_encode($data);
        exit;
    }

    // Método para mostrar mensajes de error
    protected function setError($mensaje) {
        $_SESSION['error'] = $mensaje;
    }

    // Método para mostrar mensajes de éxito
    protected function setExito($mensaje) {
        $_SESSION['exito'] = $mensaje;
    }

    // Método para obtener mensajes flash
    protected function getMensajes() {
        $mensajes = [
            'error' => isset($_SESSION['error']) ? $_SESSION['error'] : null,
            'exito' => isset($_SESSION['exito']) ? $_SESSION['exito'] : null
        ];

        // Limpiar mensajes
        unset($_SESSION['error'], $_SESSION['exito']);

        return $mensajes;
    }
}
