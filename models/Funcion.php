<?php
class Funcion extends Model {
    protected $tabla = 'funciones';

    public function __construct() {
        parent::__construct();
    }

    public function getDb() {
        return $this->db;
    }

    // Contar total de funciones
    public function contarFunciones() {
        $sql = "SELECT COUNT(*) as total FROM funciones WHERE estado = true";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetch()['total'];
    }

    // Obtener todas las funciones con detalles
    public function obtenerTodasConDetalles() {
        $sql = "SELECT f.*, 
                       s.nombre as sala_nombre, 
                       s.capacidad as sala_capacidad,
                       p.titulo as pelicula_titulo,
                       p.duracion as pelicula_duracion
                FROM funciones f
                JOIN salas s ON f.sala_id = s.id
                JOIN peliculas p ON f.pelicula_id = p.id
                WHERE f.estado = true
                ORDER BY f.fecha DESC, f.hora ASC";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll();
    }

    // Obtener detalles completos de una función
    public function obtenerDetallesCompletos($id) {
        $sql = "SELECT f.*, 
                       p.titulo as pelicula_titulo,
                       p.duracion as pelicula_duracion,
                       p.sinopsis as pelicula_sinopsis,
                       p.clasificacion as pelicula_clasificacion,
                       p.genero as pelicula_genero,
                       p.director as pelicula_director,
                       p.imagen_ruta as pelicula_imagen,
                       s.nombre as sala_nombre,
                       s.capacidad as sala_capacidad
                FROM {$this->tabla} f
                INNER JOIN peliculas p ON f.pelicula_id = p.id
                INNER JOIN salas s ON f.sala_id = s.id
                WHERE f.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener funciones por película (solo futuras)
    public function obtenerFuncionesPorPelicula($peliculaId) {
        $sql = "SELECT f.*, s.nombre as sala_nombre, s.capacidad 
                FROM {$this->tabla} f 
                INNER JOIN salas s ON f.sala_id = s.id 
                WHERE f.pelicula_id = :pelicula_id 
                AND UNIX_TIMESTAMP(CONCAT(f.fecha, ' ', f.hora)) > UNIX_TIMESTAMP()
                AND f.estado = true 
                ORDER BY f.fecha, f.hora";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pelicula_id' => $peliculaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar si una película tiene funciones asociadas (todas, independientemente del estado)
    public function peliculaTieneFunciones($peliculaId) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->tabla} 
                WHERE pelicula_id = :pelicula_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['pelicula_id' => $peliculaId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }

    // Crear nueva función
    public function crear($datos) {
        $sql = "INSERT INTO {$this->tabla} 
                (pelicula_id, sala_id, fecha, hora, precio) 
                VALUES 
                (:pelicula_id, :sala_id, :fecha, :hora, :precio)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'pelicula_id' => $datos['pelicula_id'],
            'sala_id' => $datos['sala_id'],
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'precio' => $datos['precio']
        ]);
    }

    // Actualizar función
    public function actualizar($id, $datos) {
        $sql = "UPDATE {$this->tabla} 
                SET pelicula_id = :pelicula_id,
                    sala_id = :sala_id,
                    fecha = :fecha,
                    hora = :hora,
                    precio = :precio
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'pelicula_id' => $datos['pelicula_id'],
            'sala_id' => $datos['sala_id'],
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'precio' => $datos['precio']
        ]);
    }

    // Verificar disponibilidad de sala
    public function verificarDisponibilidadSala($salaId, $fecha, $hora, $funcionId = null) {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->tabla} 
                WHERE sala_id = :sala_id 
                AND fecha = :fecha 
                AND hora = :hora 
                AND estado = true";
        
        if ($funcionId) {
            $sql .= " AND id != :funcion_id";
        }
        
        $stmt = $this->db->prepare($sql);
        $params = [
            'sala_id' => $salaId,
            'fecha' => $fecha,
            'hora' => $hora
        ];
        
        if ($funcionId) {
            $params['funcion_id'] = $funcionId;
        }
        
        $stmt->execute($params);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] == 0;
    }

    // Obtener funciones del día
    public function obtenerFuncionesDelDia($fecha = null) {
        $fecha = $fecha ?? date('Y-m-d');
        
        $sql = "SELECT f.*, 
                       p.titulo as pelicula_titulo,
                       p.duracion as pelicula_duracion,
                       s.nombre as sala_nombre,
                       s.capacidad as sala_capacidad
                FROM {$this->tabla} f 
                INNER JOIN peliculas p ON f.pelicula_id = p.id 
                INNER JOIN salas s ON f.sala_id = s.id 
                WHERE f.fecha = :fecha 
                AND f.estado = true 
                ORDER BY f.hora";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['fecha' => $fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener asientos ocupados
    public function obtenerAsientosOcupados($funcionId) {
        $sql = "SELECT asiento 
                FROM boletas 
                WHERE funcion_id = :funcion_id 
                AND estado = true";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['funcion_id' => $funcionId]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_column($resultados, 'asiento');
    }

    // Verificar si una función tiene boletas vendidas
    public function tieneBoletas($id) {
        $sql = "SELECT COUNT(*) as total 
                FROM boletas 
                WHERE funcion_id = :id 
                AND estado = true";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }

    // Obtener estadísticas de funciones
    public function obtenerEstadisticas($fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT 
                    COUNT(*) as total_funciones,
                    COUNT(DISTINCT pelicula_id) as total_peliculas,
                    COUNT(DISTINCT sala_id) as total_salas,
                    AVG(precio) as precio_promedio
                FROM {$this->tabla}
                WHERE estado = true";
        
        $params = [];
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND fecha BETWEEN :fecha_inicio AND :fecha_fin";
            $params['fecha_inicio'] = $fechaInicio;
            $params['fecha_fin'] = $fechaFin;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
