<?php
class Venta extends Model {
    protected $tabla = 'ventas';

    public function __construct() {
        parent::__construct();
    }

    // Contar total de ventas
    public function contarVentas() {
        $sql = "SELECT COUNT(*) as total FROM ventas WHERE estado = 'completada'";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetch()['total'];
    }

    // Obtener todas las ventas con detalles
    public function obtenerTodasConDetalles() {
        $sql = "SELECT v.*, 
                       f.fecha as funcion_fecha, 
                       f.hora as funcion_hora, 
                       p.titulo as pelicula_titulo, 
                       s.nombre as sala_nombre,
                       COUNT(b.id) as cantidad_boletas
                FROM ventas v
                JOIN boletas b ON v.id = b.venta_id
                JOIN funciones f ON b.funcion_id = f.id
                JOIN peliculas p ON f.pelicula_id = p.id
                JOIN salas s ON f.sala_id = s.id
                GROUP BY v.id, f.fecha, f.hora, p.titulo, s.nombre
                ORDER BY v.created_at DESC";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll();
    }

    // Ventas del día
    public function ventasHoy() {
        $sql = "SELECT COUNT(*) as total FROM ventas WHERE DATE(created_at) = CURDATE() AND estado = 'completada'";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetch()['total'];
    }

    // Ingresos del mes
    public function ingresosMes() {
        $sql = "SELECT SUM(total) as total FROM ventas WHERE MONTH(created_at) = MONTH(CURDATE()) AND estado = 'completada'";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetch()['total'] ?? 0;
    }

    // Ventas por mes (para reportes)
    public function ventasPorMes() {
        $sql = "SELECT MONTH(created_at) as mes, COUNT(*) as total FROM ventas WHERE estado = 'completada' GROUP BY mes ORDER BY mes";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll();
    }

    // Películas más vendidas (para reportes)
    public function peliculasMasVendidas() {
        $sql = "SELECT p.titulo, COUNT(b.id) as total 
                FROM ventas v 
                JOIN boletas b ON v.id = b.venta_id
                JOIN funciones f ON b.funcion_id = f.id 
                JOIN peliculas p ON f.pelicula_id = p.id 
                WHERE v.estado = 'completada' 
                GROUP BY p.titulo 
                ORDER BY total DESC 
                LIMIT 5";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll();
    }

    // Ingresos por sala (para reportes)
    public function ingresosPorSala() {
        $sql = "SELECT s.nombre, SUM(v.total) as total 
                FROM ventas v 
                JOIN boletas b ON v.id = b.venta_id
                JOIN funciones f ON b.funcion_id = f.id 
                JOIN salas s ON f.sala_id = s.id 
                WHERE v.estado = 'completada' 
                GROUP BY s.nombre 
                ORDER BY total DESC";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll();
    }

    // Completar venta
    public function completar($id) {
        $sql = "UPDATE {$this->tabla} SET estado = 'completada' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Obtener detalles completos de una venta
    public function obtenerDetallesCompletos($id) {
        $sql = "SELECT v.*, 
                       f.fecha as funcion_fecha,
                       f.hora as funcion_hora,
                       f.precio as funcion_precio,
                       p.titulo as pelicula_titulo,
                       p.duracion as pelicula_duracion,
                       s.nombre as sala_nombre,
                       GROUP_CONCAT(b.asiento) as asientos
                FROM {$this->tabla} v
                INNER JOIN boletas b ON v.id = b.venta_id
                INNER JOIN funciones f ON b.funcion_id = f.id
                INNER JOIN peliculas p ON f.pelicula_id = p.id
                INNER JOIN salas s ON f.sala_id = s.id
                WHERE v.id = :id
                GROUP BY v.id, v.cliente_email, f.fecha, f.hora, f.precio, p.titulo, p.duracion, s.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nueva venta
    public function crear($datos) {
        $sql = "INSERT INTO {$this->tabla} 
                (cliente_nombre, cliente_email, cliente_dni, total, estado) 
                VALUES 
                (:cliente_nombre, :cliente_email, :cliente_dni, :total, 'pendiente')";
        
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([
            'cliente_nombre' => $datos['cliente_nombre'],
            'cliente_email' => $datos['cliente_email'],
            'cliente_dni' => $datos['cliente_dni'],
            'total' => $datos['total']
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Cancelar venta
    public function cancelar($id) {
        $sql = "UPDATE {$this->tabla} SET estado = 'cancelada' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Iniciar transacción
    public function iniciarTransaccion() {
        $this->db->beginTransaction();
    }

    // Confirmar transacción
    public function confirmarTransaccion() {
        $this->db->commit();
    }

    // Revertir transacción
    public function revertirTransaccion() {
        $this->db->rollBack();
    }

    // Obtener ventas por cliente
    public function obtenerVentasPorCliente($email) {
        $sql = "SELECT v.*, 
                       COUNT(b.id) as cantidad_boletas,
                       f.fecha as funcion_fecha,
                       f.hora as funcion_hora,
                       p.titulo as pelicula_titulo
                FROM {$this->tabla} v
                INNER JOIN boletas b ON v.id = b.venta_id
                INNER JOIN funciones f ON b.funcion_id = f.id
                INNER JOIN peliculas p ON f.pelicula_id = p.id
                WHERE v.cliente_email = :email
                GROUP BY v.id, f.fecha, f.hora, p.titulo
                ORDER BY v.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener estadísticas de ventas
    public function obtenerEstadisticas($fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT 
                    COUNT(*) as total_ventas,
                    SUM(total) as ingresos_totales,
                    COUNT(DISTINCT cliente_email) as clientes_unicos,
                    AVG(total) as promedio_venta
                FROM {$this->tabla}
                WHERE estado = 'completada'";
        
        $params = [];
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(created_at) BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $fechaInicio;
            $params['fecha_fin'] = $fechaFin;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
