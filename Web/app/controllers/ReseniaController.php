<?php
class ReseniaController extends Controller {
    
    // Crear reseña
    public function crear($id_producto) {
        session_start();
        
        if(!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }
        
        $productoModel = $this->model('Producto');
        $producto = $productoModel->obtenerPorId($id_producto);
        
        $data = [
            'titulo' => 'Escribir Reseña',
            'producto' => $producto
        ];
        
        $this->view('resenia/crear', $data);
    }

    // Guardar reseña
    public function guardar() {
        session_start();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
            $reseniaModel = $this->model('Resenia');
            
            $id_producto = $_POST['id_producto'] ?? 0;
            
            // Verificar si ya dejó reseña
            if($reseniaModel->verificarReseniaExistente($_SESSION['usuario_id'], $id_producto)) {
                header('Location: ' . BASE_URL . 'producto/ver/' . $id_producto . '?error=ya_existe');
                exit();
            }
            
            $reseniaModel->id_usuario = $_SESSION['usuario_id'];
            $reseniaModel->id_producto = $id_producto;
            $reseniaModel->comentario = $_POST['comentario'] ?? '';
            $reseniaModel->calificacion = $_POST['calificacion'] ?? 5;
            $reseniaModel->fecha_resenia = date('Y-m-d');
            
            if($reseniaModel->crear()) {
                header('Location: ' . BASE_URL . 'producto/ver/' . $id_producto . '?resenia=ok');
                exit();
            }
        }
    }

    // Eliminar reseña
    public function eliminar($id) {
        session_start();
        
        if(!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL);
            exit();
        }
        
        $reseniaModel = $this->model('Resenia');
        $reseniaModel->eliminar($id);
        
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL);
        exit();
    }
}
?>