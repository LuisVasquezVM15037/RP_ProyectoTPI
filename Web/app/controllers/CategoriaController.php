<?php
class CategoriaController extends Controller {
    
    // Listar categorías
    public function index() {
        $categoriaModel = $this->model('Categoria');
        $categorias = $categoriaModel->obtenerConProductos();
        
        $data = [
            'titulo' => 'Categorías',
            'categorias' => $categorias
        ];
        
        $this->view('categoria/index', $data);
    }

    // Mostrar formulario de creación
    public function crear() {
        session_start();
        if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 1) {
            header('Location: ' . BASE_URL);
            exit();
        }
        
        $this->view('categoria/crear');
    }

    // Procesar creación
    public function guardar() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $categoriaModel = $this->model('Categoria');
            
            $categoriaModel->nombre_categoria = $_POST['nombre_categoria'] ?? '';
            
            if($categoriaModel->crear()) {
                header('Location: ' . BASE_URL . 'categoria');
                exit();
            }
        }
    }

    // Eliminar categoría
    public function eliminar($id) {
        session_start();
        if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 1) {
            header('Location: ' . BASE_URL);
            exit();
        }
        
        $categoriaModel = $this->model('Categoria');
        $categoriaModel->eliminar($id);
        
        header('Location: ' . BASE_URL . 'categoria');
        exit();
    }
}
?>
