<?php
class Pelicula extends Model {
    protected $tabla = 'peliculas';

    public function __construct() {
        parent::__construct();
    }

    public function getDb() {
        return $this->db;
    }

    // Contar total de películas
    public function contarPeliculas() {
        $sql = "SELECT COUNT(*) as total FROM peliculas WHERE estado = true";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetch()['total'];
    }

    // Obtener todas las películas
    public function obtenerTodas() {
        $sql = "SELECT * FROM peliculas WHERE estado = true ORDER BY id DESC";
        $resultado = $this->ejecutarConsulta($sql);
        return $resultado->fetchAll();
    }

    // Obtener todas las películas activas
    public function obtenerPeliculasActivas() {
        $sql = "SELECT * FROM {$this->tabla} WHERE estado = true ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nueva película
    public function crear($datos) {
        $sql = "INSERT INTO {$this->tabla} (titulo, sinopsis, duracion, clasificacion, genero, director, imagen_ruta) 
                VALUES (:titulo, :sinopsis, :duracion, :clasificacion, :genero, :director, :imagen_ruta)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':titulo', $datos['titulo']);
        $stmt->bindParam(':sinopsis', $datos['sinopsis']);
        $stmt->bindParam(':duracion', $datos['duracion']);
        $stmt->bindParam(':clasificacion', $datos['clasificacion']);
        $stmt->bindParam(':genero', $datos['genero']);
        $stmt->bindParam(':director', $datos['director']);
        $stmt->bindParam(':imagen_ruta', $datos['imagen_ruta']);
        
        return $stmt->execute();
    }

    // Actualizar película
    public function actualizar($id, $datos) {
        $sql = "UPDATE {$this->tabla} 
                SET titulo = :titulo, 
                    sinopsis = :sinopsis, 
                    duracion = :duracion, 
                    clasificacion = :clasificacion, 
                    genero = :genero, 
                    director = :director";

        // Agregar campo imagen_ruta si se proporciona
        if (isset($datos['imagen_ruta'])) {
            $sql .= ", imagen_ruta = :imagen_ruta";
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':titulo', $datos['titulo']);
        $stmt->bindParam(':sinopsis', $datos['sinopsis']);
        $stmt->bindParam(':duracion', $datos['duracion']);
        $stmt->bindParam(':clasificacion', $datos['clasificacion']);
        $stmt->bindParam(':genero', $datos['genero']);
        $stmt->bindParam(':director', $datos['director']);

        if (isset($datos['imagen_ruta'])) {
            $stmt->bindParam(':imagen_ruta', $datos['imagen_ruta']);
        }

        return $stmt->execute();
    }

    // Obtener ruta de imagen de una película
    public function obtenerImagenRuta($id) {
        $sql = "SELECT imagen_ruta FROM {$this->tabla} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['imagen_ruta'] : null;
    }

    // Borrar imagen de una película
    public function borrarImagen($id) {
        $sql = "UPDATE {$this->tabla} SET imagen_ruta = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Buscar películas
    public function buscar($termino) {
        $sql = "SELECT * FROM {$this->tabla} 
                WHERE (titulo LIKE :termino 
                OR genero LIKE :termino 
                OR director LIKE :termino) 
                AND estado = true";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['termino' => "%$termino%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
