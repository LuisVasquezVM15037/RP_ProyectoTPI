<?php
// Controladores/ReseniaController.php
class ReseniaController {
    private $conn;
    private $reseniaModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->reseniaModel = new ReseniaModel($conn);
    }

    public function agregarResenia($id_usuario, $id_producto, $comentario, $calificacion) {
        // Validar datos
        if (empty($comentario) || $calificacion < 1 || $calificacion > 5) {
            return ['success' => false, 'message' => 'Datos de reseña inválidos'];
        }

        // Verificar si el usuario ya hizo una reseña para este producto
        $reseniaExistente = $this->obtenerReseniaUsuario($id_usuario, $id_producto);
        
        if ($reseniaExistente) {
            return ['success' => false, 'message' => 'Ya has realizado una reseña para este producto'];
        }

        // Agregar la reseña
        $result = $this->reseniaModel->agregarResenia($id_usuario, $id_producto, $comentario, $calificacion);
        
        if ($result) {
            return ['success' => true, 'message' => 'Reseña agregada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al agregar la reseña'];
        }
    }

    public function obtenerReseniaUsuario($id_usuario, $id_producto) {
        return $this->reseniaModel->obtenerReseniaUsuario($id_usuario, $id_producto);
    }

    // NUEVO MÉTODO PARA ELIMINAR RESEÑA
    public function eliminarResenia($id_resenia, $id_usuario) {
        // Verificar que la reseña pertenece al usuario
        $resenia = $this->reseniaModel->obtenerReseniaPorId($id_resenia);
        
        if (!$resenia) {
            return ['success' => false, 'message' => 'Reseña no encontrada'];
        }
        
        if ($resenia['id_usuario'] != $id_usuario) {
            return ['success' => false, 'message' => 'No tienes permiso para eliminar esta reseña'];
        }
        
        // Eliminar la reseña
        $result = $this->reseniaModel->eliminarResenia($id_resenia);
        
        if ($result) {
            return ['success' => true, 'message' => 'Reseña eliminada correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar la reseña'];
        }
    }

    public function obtenerReseniasProducto($id_producto, $limit = 5) {
        return $this->reseniaModel->obtenerReseniasProducto($id_producto, $limit);
    }

    public function obtenerPromedioCalificaciones($id_producto) {
        return $this->reseniaModel->obtenerPromedioCalificaciones($id_producto);
    }
}
?>