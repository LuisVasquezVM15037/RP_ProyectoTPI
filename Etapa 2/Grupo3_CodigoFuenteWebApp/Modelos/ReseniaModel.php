<?php
// Controladores/ReseniaModel.php
class ReseniaModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function agregarResenia($id_usuario, $id_producto, $comentario, $calificacion) {
        $sql = "INSERT INTO resenia (id_usuario, id_producto, comentario, calificacion, fecha_resenia) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisi", $id_usuario, $id_producto, $comentario, $calificacion);
        
        return $stmt->execute();
    }

    public function obtenerReseniaUsuario($id_usuario, $id_producto) {
        $sql = "SELECT * FROM resenia WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // NUEVO MÉTODO PARA OBTENER RESEÑA POR ID
    public function obtenerReseniaPorId($id_resenia) {
        $sql = "SELECT * FROM resenia WHERE id_resenia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_resenia);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // NUEVO MÉTODO PARA ELIMINAR RESEÑA
    public function eliminarResenia($id_resenia) {
        $sql = "DELETE FROM resenia WHERE id_resenia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_resenia);
        
        return $stmt->execute();
    }

    public function obtenerReseniasProducto($id_producto, $limit = 5) {
        $sql = "SELECT r.*, u.nombre_usuario, u.apellido_usuario 
                FROM resenia r 
                INNER JOIN usuario u ON r.id_usuario = u.id_usuario 
                WHERE r.id_producto = ? 
                ORDER BY r.fecha_resenia DESC 
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_producto, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPromedioCalificaciones($id_producto) {
        $sql = "SELECT AVG(calificacion) as promedio, COUNT(*) as total 
                FROM resenia 
                WHERE id_producto = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}
?>