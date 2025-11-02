<?php
require_once __DIR__ . '/../../config/database.php';

class Resenia {
    private $conn;
    private $tabla = 'resenia';

    public $id_resenia;
    public $id_usuario;
    public $id_producto;
    public $comentario;
    public $calificacion;
    public $fecha_resenia;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Obtener reseñas por producto
    public function obtenerPorProducto($id_producto) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  WHERE r.id_producto = :id_producto
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener reseñas por usuario
    public function obtenerPorUsuario($id_usuario) {
        $query = "SELECT r.*, p.nombre_producto, p.imagen_url
                  FROM " . $this->tabla . " r
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE r.id_usuario = :id_usuario
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear reseña
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " 
                  (id_usuario, id_producto, comentario, calificacion, fecha_resenia) 
                  VALUES (:id_usuario, :id_producto, :comentario, :calificacion, :fecha)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id_usuario', $this->id_usuario);
        $stmt->bindParam(':id_producto', $this->id_producto);
        $stmt->bindParam(':comentario', $this->comentario);
        $stmt->bindParam(':calificacion', $this->calificacion);
        $stmt->bindParam(':fecha', $this->fecha_resenia);
        
        return $stmt->execute();
    }

    // Obtener promedio de calificación de un producto
    public function obtenerPromedioProducto($id_producto) {
        $query = "SELECT AVG(calificacion) as promedio, COUNT(*) as total_resenias
                  FROM " . $this->tabla . " 
                  WHERE id_producto = :id_producto";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Verificar si el usuario ya dejó reseña
    public function verificarReseniaExistente($id_usuario, $id_producto) {
        $query = "SELECT * FROM " . $this->tabla . " 
                  WHERE id_usuario = :id_usuario AND id_producto = :id_producto";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        return $stmt->fetch() ? true : false;
    }

    // Eliminar reseña
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->tabla . " WHERE id_resenia = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Actualizar reseña
    public function actualizar() {
        $query = "UPDATE " . $this->tabla . " 
                  SET comentario = :comentario,
                      calificacion = :calificacion,
                      fecha_resenia = :fecha
                  WHERE id_resenia = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':comentario', $this->comentario);
        $stmt->bindParam(':calificacion', $this->calificacion);
        $stmt->bindParam(':fecha', $this->fecha_resenia);
        $stmt->bindParam(':id', $this->id_resenia);
        
        return $stmt->execute();
    }

    // Obtener todas las reseñas
    public function obtenerTodos($id_usuario = null) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto";
        
        if($id_usuario) {
            $query .= " WHERE r.id_usuario = :id_usuario";
        }
        
        $query .= " ORDER BY r.fecha_resenia DESC"; 
        $stmt = $this->conn->prepare($query);
        if($id_usuario) {
            $stmt->bindParam(':id_usuario', $id_usuario);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // Obtener reseña por ID
    public function obtenerPorId($id_resenia) {
        $query = "SELECT * FROM " . $this->tabla . " 
                  WHERE id_resenia = :id_resenia";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_resenia', $id_resenia);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Contar total de reseñas
    public function contarTotalResenias($id_producto) {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->tabla . " 
                  WHERE id_producto = :id_producto";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Obtener reseñas recientes
    public function obtenerReseniasRecientes($limite = 5) { 
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  ORDER BY r.fecha_resenia DESC
                  LIMIT :limite";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar reseñas por palabra clave en el comentario
    public function buscarPorComentario($palabra_clave) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE r.comentario LIKE :palabra_clave
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $like_keyword = '%' . $palabra_clave . '%';
        $stmt->bindParam(':palabra_clave', $like_keyword);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener reseñas con calificación mínima
    public function obtenerPorCalificacionMinima($calificacion_minima) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE r.calificacion >= :calificacion_minima
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':calificacion_minima', $calificacion_minima);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener reseñas por rango de fechas
    public function obtenerPorRangoFechas($fecha_inicio, $fecha_fin) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE r.fecha_resenia BETWEEN :fecha_inicio AND :fecha_fin
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    // Obtener reseñas destacadas (con calificación de 5)
    public function obtenerReseniasDestacadas() {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE r.calificacion = 5
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener el total de reseñas en la base de datos
    public function obtenerTotalResenias() {
        $query = "SELECT COUNT(*) as total FROM " . $this->tabla;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }


    // Obtener reseñas ordenadas por calificación
    public function obtenerOrdenadasPorCalificacion($orden = 'DESC') {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  ORDER BY r.calificacion " . ($orden === 'ASC' ? 'ASC' : 'DESC');
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    // Obtener reseñas con comentarios cortos (menos de 50 caracteres)
    public function obtenerComentariosCortos($longitud_maxima = 50) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE LENGTH(r.comentario) < :longitud_maxima
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':longitud_maxima', $longitud_maxima, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener reseñas por producto y calificación mínima
    public function obtenerPorProductoYCalificacionMinima($id_producto, $calificacion_minima) {
        $query = "SELECT r.*, u.nombre_usuario, u.apellido_usuario
                  FROM " . $this->tabla . " r
                  INNER JOIN usuario u ON r.id_usuario = u.id_usuario
                  WHERE r.id_producto = :id_producto
                  AND r.calificacion >= :calificacion_minima
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':calificacion_minima', $calificacion_minima);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener reseñas por usuario y rango de calificaciones
    public function obtenerPorUsuarioYRangoCalificaciones($id_usuario, $calificacion_minima, $calificacion_maxima) {
        $query = "SELECT r.*, p.nombre_producto
                  FROM " . $this->tabla . " r
                  INNER JOIN producto p ON r.id_producto = p.id_producto
                  WHERE r.id_usuario = :id_usuario
                  AND r.calificacion BETWEEN :calificacion_minima AND :calificacion_maxima
                  ORDER BY r.fecha_resenia DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':calificacion_minima', $calificacion_minima);
        $stmt->bindParam(':calificacion_maxima', $calificacion_maxima);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>