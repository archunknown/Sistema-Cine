<?php
class Boleta extends Model {
    protected $tabla = 'boletas';

    public function __construct() {
        parent::__construct();
    }

    // Crear nueva boleta
    public function crear($datos) {
        // Generar código QR único
        $codigoQR = $this->generarCodigoQR($datos);
        
        $sql = "INSERT INTO {$this->tabla} 
                (venta_id, funcion_id, asiento, codigo_qr) 
                VALUES 
                (:venta_id, :funcion_id, :asiento, :codigo_qr)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'venta_id' => $datos['venta_id'],
            'funcion_id' => $datos['funcion_id'],
            'asiento' => $datos['asiento'],
            'codigo_qr' => $codigoQR
        ]);
    }

    // Obtener boletas por venta
    public function obtenerBoletasPorVenta($ventaId) {
        $sql = "SELECT b.*, 
                       f.fecha as funcion_fecha,
                       f.hora as funcion_hora,
                       p.titulo as pelicula_titulo,
                       s.nombre as sala_nombre
                FROM {$this->tabla} b
                INNER JOIN funciones f ON b.funcion_id = f.id
                INNER JOIN peliculas p ON f.pelicula_id = p.id
                INNER JOIN salas s ON f.sala_id = s.id
                WHERE b.venta_id = :venta_id
                ORDER BY b.asiento";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['venta_id' => $ventaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar boleta
    public function verificarBoleta($codigoQR) {
        $sql = "SELECT b.*, 
                       v.cliente_nombre,
                       v.cliente_dni,
                       f.fecha as funcion_fecha,
                       f.hora as funcion_hora,
                       p.titulo as pelicula_titulo,
                       s.nombre as sala_nombre
                FROM {$this->tabla} b
                INNER JOIN ventas v ON b.venta_id = v.id
                INNER JOIN funciones f ON b.funcion_id = f.id
                INNER JOIN peliculas p ON f.pelicula_id = p.id
                INNER JOIN salas s ON f.sala_id = s.id
                WHERE b.codigo_qr = :codigo_qr";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['codigo_qr' => $codigoQR]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Marcar boleta como usada
    public function marcarComoUsada($id) {
        $sql = "UPDATE {$this->tabla} SET estado = false WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Cancelar boletas de una venta
    public function cancelarBoletasDeVenta($ventaId) {
        $sql = "UPDATE {$this->tabla} SET estado = false WHERE venta_id = :venta_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['venta_id' => $ventaId]);
    }

    // Verificar disponibilidad de asiento
    public function verificarDisponibilidadAsiento($funcionId, $asiento) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->tabla} 
                WHERE funcion_id = :funcion_id 
                AND asiento = :asiento 
                AND estado = true";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'funcion_id' => $funcionId,
            'asiento' => $asiento
        ]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] == 0;
    }

    // Generar código QR único
    private function generarCodigoQR($datos) {
        // Generar un string único basado en los datos de la boleta
        $uniqueString = uniqid() . '_' . $datos['funcion_id'] . '_' . $datos['asiento'];
        
        // Codificar en base64 para tener un string más corto y seguro
        $encodedString = base64_encode($uniqueString);
        
        // Tomar solo los primeros 32 caracteres para tener un código más manejable
        return substr($encodedString, 0, 32);
    }

    // Obtener estadísticas de boletas
    public function obtenerEstadisticas($funcionId = null) {
        $sql = "SELECT 
                    COUNT(*) as total_boletas,
                    COUNT(CASE WHEN estado = true THEN 1 END) as boletas_activas,
                    COUNT(CASE WHEN estado = false THEN 1 END) as boletas_usadas
                FROM {$this->tabla}";
        
        $params = [];
        if ($funcionId) {
            $sql .= " WHERE funcion_id = :funcion_id";
            $params['funcion_id'] = $funcionId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
