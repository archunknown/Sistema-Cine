<?php
class Promocion extends Model {
    protected $tabla = 'promociones';

    public function __construct() {
        parent::__construct();
    }

    // Obtener promociones activas
    public function obtenerPromocionesActivas() {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = 'activa' ORDER BY fecha_inicio DESC";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll(PDO::FETCH_ASSOC);
    }
}
