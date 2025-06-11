<?php
require_once 'configuracion.php';

class Conexion {
    private static $instance = null;
    private $conexion;

    private function __construct() {
        try {
            $this->conexion = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_LOCAL_INFILE => true
                ]
            );
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Patrón Singleton para asegurar una única instancia
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Obtener la conexión
    public function getConexion() {
        return $this->conexion;
    }

    // Prevenir la clonación del objeto
    private function __clone() {}

    // Método para ejecutar consultas
    public function ejecutarConsulta($sql, $params = []) {
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    // Método para obtener el último ID insertado
    public function ultimoId() {
        return $this->conexion->lastInsertId();
    }
}
