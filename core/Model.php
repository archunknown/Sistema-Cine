<?php
require_once __DIR__ . '/../config/conexion.php';

abstract class Model {
    protected $db;
    protected $tabla;

    public function __construct() {
        $this->db = Conexion::getInstance()->getConexion();
    }

    // Método para ejecutar consultas preparadas con parámetros
    public function ejecutarConsulta($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Método para obtener todos los registros
    public function obtenerTodos() {
        $sql = "SELECT * FROM {$this->tabla}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un registro por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para eliminar un registro
    public function eliminar($id) {
        $sql = "DELETE FROM {$this->tabla} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Método para actualizar estado
    public function actualizarEstado($id, $estado) {
        $sql = "UPDATE {$this->tabla} SET estado = :estado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'estado' => $estado
        ]);
    }

    // Método para contar registros
    public function contarRegistros($condicion = "") {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} " . $condicion;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }
}
