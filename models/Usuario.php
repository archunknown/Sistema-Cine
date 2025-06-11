<?php
class Usuario extends Model {
    protected $tabla = 'usuarios';

    public function __construct() {
        parent::__construct();
    }

    // Autenticar usuario
    public function autenticar($email, $password) {
        $sql = "SELECT * FROM {$this->tabla} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Eliminar el password antes de devolver los datos
            unset($usuario['password']);
            return $usuario;
        }

        return false;
    }

    // Registrar nuevo usuario
    public function registrar($datos) {
        // Verificar si el email ya existe
        if ($this->emailExiste($datos['email'])) {
            return false;
        }

        $sql = "INSERT INTO {$this->tabla} (nombre, email, password, rol) 
                VALUES (:nombre, :email, :password, :rol)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $datos['nombre'],
            'email' => $datos['email'],
            'password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            'rol' => $datos['rol'] ?? 'cliente'
        ]);
    }

    // Verificar si existe el email
    public function emailExiste($email) {
        $sql = "SELECT COUNT(*) as total FROM {$this->tabla} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] > 0;
    }

    // Actualizar datos del usuario
    public function actualizarDatos($id, $datos) {
        $sql = "UPDATE {$this->tabla} 
                SET nombre = :nombre";
        
        $params = [
            'id' => $id,
            'nombre' => $datos['nombre']
        ];

        // Si se proporciona una nueva contraseña, actualizarla
        if (!empty($datos['password'])) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }

        // Si se proporciona un nuevo email, verificar que no exista
        if (!empty($datos['email']) && $datos['email'] !== $this->obtenerPorId($id)['email']) {
            if ($this->emailExiste($datos['email'])) {
                return false;
            }
            $sql .= ", email = :email";
            $params['email'] = $datos['email'];
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Cambiar rol de usuario (solo admin)
    public function cambiarRol($id, $rol) {
        $sql = "UPDATE {$this->tabla} SET rol = :rol WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'rol' => $rol
        ]);
    }

    // Obtener todos los usuarios (excepto el actual)
    public function obtenerTodosExcepto($id) {
        $sql = "SELECT id, nombre, email, rol, created_at FROM {$this->tabla} WHERE id != :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarPassword($id, $password) {
        $sql = "SELECT password FROM {$this->tabla} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            return password_verify($password, $usuario['password']);
        }
        return false;
    }

    public function actualizar($datos) {
        $sql = "UPDATE {$this->tabla} SET 
                nombre = :nombre, 
                email = :email, 
                telefono = :telefono,
                direccion = :direccion";

        $params = [
            'id' => $datos['id'],
            'nombre' => $datos['nombre'],
            'email' => $datos['email'],
            'telefono' => $datos['telefono'],
            'direccion' => $datos['direccion']
        ];

        // Si se proporciona una nueva contraseña, actualizarla
        if (isset($datos['password'])) {
            $sql .= ", password = :password";
            $params['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
