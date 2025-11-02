<?php
require_once __DIR__ . '/../../core/Model.php';

class Usuario extends Model
{
    protected $tabla = 'usuario';

    public $id_usuario;
    public $nombre_usuario;
    public $apellido_usuario;
    public $email_usuario;
    public $contrasenia_usuario;
    public $direccion_usuario;
    public $telefono_usuario;
    public $rol_usuario;

    // 🔹 Obtener usuario por ID
    public function obtenerPorId($id)
    {
        $query = "SELECT * FROM {$this->tabla} WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔹 Registrar nuevo usuario (guarda contraseña en texto plano)
    public function registrar()
    {
        $query = "INSERT INTO {$this->tabla}
                  (nombre_usuario, apellido_usuario, email_usuario, contrasenia_usuario, 
                   direccion_usuario, telefono_usuario, rol_usuario) 
                  VALUES (:nombre, :apellido, :email, :contrasenia, :direccion, :telefono, :rol)";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':nombre', $this->nombre_usuario);
        $stmt->bindParam(':apellido', $this->apellido_usuario);
        $stmt->bindParam(':email', $this->email_usuario);
        $stmt->bindParam(':contrasenia', $this->contrasenia_usuario); // 👉 Guardamos texto plano
        $stmt->bindParam(':direccion', $this->direccion_usuario);
        $stmt->bindParam(':telefono', $this->telefono_usuario);
        $stmt->bindParam(':rol', $this->rol_usuario);

        return $stmt->execute();
    }

    // 🔹 Autenticar usuario (texto plano)
    public function autenticar($email, $password)
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE TRIM(LOWER(email_usuario)) = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', strtolower(trim($email)), PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return false; // Usuario no encontrado
        }

        // Comparar directamente contraseñas en texto plano
        if (trim($usuario['contrasenia_usuario']) === trim($password)) {
            return $usuario;
        }

        return false;
    }

    // 🔹 Obtener todos los usuarios
    public function obtenerTodos()
    {
        $query = "SELECT * FROM {$this->tabla}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Actualizar usuario existente
    public function actualizar()
{
    try {
        $sql = "UPDATE {$this->tabla}
                SET nombre_usuario = :nombre,
                    apellido_usuario = :apellido,
                    email_usuario = :email,
                    direccion_usuario = :direccion,
                    telefono_usuario = :telefono,
                    rol_usuario = :rol
                WHERE id_usuario = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $this->id_usuario, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', $this->nombre_usuario, PDO::PARAM_STR);
        $stmt->bindValue(':apellido', $this->apellido_usuario, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email_usuario, PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $this->direccion_usuario, PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $this->telefono_usuario, PDO::PARAM_STR);
        $stmt->bindValue(':rol', $this->rol_usuario, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error al actualizar usuario: " . $e->getMessage());
        return false;
    }
}


    // 🔹 Eliminar usuario
    public function eliminar($id)
    {
        try {
            // Protección opcional: evitar eliminar al admin principal (id=1)
            if ($id === 1) {
                return false;
            }

            $stmt = $this->db->prepare("DELETE FROM {$this->tabla} WHERE id_usuario = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

}
?>