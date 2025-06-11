<?php
/**
 * Archivo de configuración general del sistema
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_cine');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuración de la aplicación
define('APP_NAME', 'ArchView');

// Detectar URL base dinámicamente
$protocol = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
$host = $_SERVER['HTTP_HOST'];
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$baseUrl = $protocol . '://' . $host . (strlen($scriptDir) > 1 ? $scriptDir : '');
define('APP_URL', rtrim($baseUrl, '/'));

// Configuración de rutas
define('BASE_PATH', dirname(__DIR__));
define('CONTROLLERS_PATH', BASE_PATH . '/controllers/');
define('MODELS_PATH', BASE_PATH . '/models/');
define('VIEWS_PATH', BASE_PATH . '/views/');
define('PUBLIC_PATH', BASE_PATH . '/public/');

// Configuración de sesión
ini_set('session.cookie_lifetime', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Zona horaria
date_default_timezone_set('America/Lima');

// Configuración de errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LIBS_PATH', BASE_PATH . '/libs/');
