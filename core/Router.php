<?php
require_once 'Debug.php';

class Router {
    private $controlador = 'HomeController';
    private $metodo = 'index';
    private $parametros = [];

    public function __construct() {
        $url = $this->getUrl();
        
        // Verificar y cargar el controlador
        if (isset($url[0])) {
            // Convertir mis-compras a MisCompras
            $controllerName = str_replace(' ', '', ucwords(str_replace('-', ' ', $url[0])));
            Debug::log("Router: Intentando cargar controlador: " . $controllerName . "Controller");
            
            if (file_exists(CONTROLLERS_PATH . $controllerName . 'Controller.php')) {
                $this->controlador = $controllerName . 'Controller';
                Debug::log("Router: Controlador encontrado: " . $this->controlador);
                unset($url[0]);
            } else {
                Debug::log("Router: Controlador no encontrado: " . $controllerName . "Controller");
            }
        }

        // Incluir el controlador
        require_once CONTROLLERS_PATH . $this->controlador . '.php';
        $this->controlador = new $this->controlador;

        // Verificar y cargar el método
        if (isset($url[1])) {
            if (method_exists($this->controlador, $url[1])) {
                $this->metodo = $url[1];
                unset($url[1]);
            }
        }

        // Obtener parámetros
        $this->parametros = $url ? array_values($url) : [];

        // Llamar al método del controlador con los parámetros
        Debug::log("Router: Ejecutando método " . $this->metodo . " del controlador " . get_class($this->controlador));
        call_user_func_array([$this->controlador, $this->metodo], $this->parametros);
    }

    // Obtener la URL y procesarla
    private function getUrl() {
        if (isset($_GET['url'])) {
            // Limpiar la URL
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
