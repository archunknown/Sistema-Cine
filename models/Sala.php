<?php
class Sala extends Model {
    // Contar total de salas
    public function contarSalas() {
        $sql = "SELECT COUNT(*) as total FROM salas WHERE estado = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Obtener todas las salas con conteo de funciones activas
    public function obtenerTodas() {
        $sql = "SELECT s.*, COUNT(DISTINCT CASE WHEN f.estado = true THEN f.id END) as funciones_activas 
                FROM salas s 
                LEFT JOIN funciones f ON s.id = f.sala_id 
                GROUP BY s.id 
                ORDER BY s.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected $tabla = 'salas';

    public function __construct() {
        parent::__construct();
    }

    // Obtener todas las salas activas
    public function obtenerSalasActivas() {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = true ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nueva sala
    public function crear($datos) {
        $sql = "INSERT INTO {$this->tabla} (nombre, capacidad) VALUES (:nombre, :capacidad)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $datos['nombre'],
            'capacidad' => $datos['capacidad']
        ]);
    }

    // Actualizar sala
    public function actualizar($id, $datos) {
        $sql = "UPDATE {$this->tabla} 
                SET nombre = :nombre, 
                    capacidad = :capacidad 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'nombre' => $datos['nombre'],
            'capacidad' => $datos['capacidad']
        ]);
    }

    // Verificar si la sala está disponible
    public function estaDisponible($id, $fecha, $hora) {
        $sql = "SELECT COUNT(*) as total 
                FROM funciones 
                WHERE sala_id = :sala_id 
                AND fecha = :fecha 
                AND hora = :hora 
                AND estado = true";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'sala_id' => $id,
            'fecha' => $fecha,
            'hora' => $hora
        ]);
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] == 0;
    }

    // Obtener capacidad de la sala
    public function obtenerCapacidad($id) {
        $sql = "SELECT capacidad FROM {$this->tabla} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['capacidad'] : 0;
    }

    // Verificar si la sala tiene funciones asociadas
    public function tieneFunciones($id) {
        $sql = "SELECT COUNT(*) as total FROM funciones WHERE sala_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }
}
