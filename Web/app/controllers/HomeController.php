<?php
class HomeController extends Controller {

    public function index() {
        // Cargar modelo
        $productoModel = $this->model('Producto');
        $categoriaModel = $this->model('Categoria');

        // Obtener datos de DB
        $productos = $productoModel->obtenerTodos();
        $categorias = $categoriaModel->obtenerTodos();

        // Enviar datos a la vista
        $data = [
            'titulo' => 'Tienda Verde - Jardinería Online',
            'productos' => $productos,
            'categorias' => $categorias
        ];

        $this->view('home/index', $data);
    }
}
?>
