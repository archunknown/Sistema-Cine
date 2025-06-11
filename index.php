<?php
// Cargar configuración
require_once 'config/configuracion.php';

// Cargar clases base
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Router.php';

// Iniciar el router
$router = new Router();
