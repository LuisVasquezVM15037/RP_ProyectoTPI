<?php
//clase hija de Controller
//Clase controladora de la comunicacion entre el modelo y la vista de los productos
class ProductoController extends Controller
{
    // Listar todos productos disponibles
    public function index()
    {
        //Cargamos el metodo model  de producto heredado de la clase Controller
        //Cargamos el modelo  de producto
        $productoModel = $this->model('Producto');
        //Se hace la llamada del metodo obtenerTodos del modelo Producto 
        //y se almacena el array retornado en la variable local productos
        $productos = $productoModel->obtenerTodos();
        //Se prepara un arreglo con con el titulo y los productos
        $data = [
            'titulo' => 'Productos',
            'productos' => $productos
        ];
        //Se carga la vista Index de los productos para mostrar la data recuperada de la BD
        $this->view('productos/index', $data);
    }

    //Metodo para mostrar los detalles de un producto específico junto con sus reseñas.
    public function ver($id = null)
    {
        // Validar que el ID sea un entero válido
        if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
            header('Location: ' . BASE_URL . 'producto');//redirecciona a la lista de productos
            exit();
        }
        $id = (int) $id; //asegura que $id es un entero
        //Cargamos el modelo  de producto
        $productoModel = $this->model('Producto');
        //Se hace la llamada del metodo obtenerTodos del modelo Producto 
        //y se almacena el array retornado en la variable local productos
        $producto = $productoModel->obtenerPorId($id);

        // Obtenemos reseñas del producto por lo cual instanciamos el modelo de Resenia
        $reseniaModel = $this->model('Resenia');
        //Se hace la llamada del metodo obtenerPorProducto del modelo reseña 
        //y se almacena el array retornado en la variable local reseña
        $resenias = $reseniaModel->obtenerPorProducto($id);
        //Se llama al metodo que calcula el promedio de la calificacion del producto
        $promedio = $reseniaModel->obtenerPromedioProducto($id);

        $data = [
            'titulo' => $producto['nombre_producto'],
            'producto' => $producto,
            'resenias' => $resenias,
            'promedio' => $promedio
        ];

        $this->view('productos/ver', $data);
    }

    //Metodo para mostrar el formulario de creación de un nuevo producto.
    public function crear()
    {
        if (!isset($_SESSION['usuario_rol']) || (int) $_SESSION['usuario_rol'] !== 1) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }
        $categoriaModel = $this->model('Categoria');
        $this->view('productos/crear', ['categorias' => $categoriaModel->obtenerTodos()]);
    }

    //Procesar el formulario de creación (guardar el nuevo producto en la base de datos).
    public function guardar()
    {
        // 🔒 Validación de acceso
        if (!isset($_SESSION['usuario_rol']) || (int) $_SESSION['usuario_rol'] !== 1) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Cargar modelo
            $m = $this->model('Producto');
            $m->id_categoria = (int) ($_POST['id_categoria'] ?? 0);
            $m->nombre_producto = trim($_POST['nombre_producto'] ?? '');
            $m->sku = trim($_POST['sku'] ?? '');
            $m->descripcion = trim($_POST['descripcion'] ?? '');
            $m->precio_unitario = (float) ($_POST['precio_unitario'] ?? 0);
            $m->stock = (int) ($_POST['stock'] ?? 0);
            $m->proveedor = trim($_POST['proveedor'] ?? '');

            // 🖼️ Manejo del archivo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/img/productos/'; // ruta física
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // crea la carpeta si no existe
                }

                // Extensión original (jpg, png, etc.)
                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $extension = strtolower($extension);

                // Nombre del archivo basado en el nombre del producto (sin espacios)
                $nombreLimpio = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $m->nombre_producto));
                $nombreArchivo = $nombreLimpio . '.' . $extension;

                $rutaDestino = $uploadDir . $nombreArchivo; // ruta absoluta
                $rutaRelativa = 'productos/' . $nombreArchivo; // ruta que guardamos en BD

                // Mover archivo temporal a la carpeta final
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $m->imagen_url = $rutaRelativa;
                } else {
                    $m->imagen_url = 'productos/default.jpg'; // imagen por defecto si falla
                }
            } else {
                // Si no se sube imagen, asignar una por defecto
                $m->imagen_url = 'productos/default.jpg';
            }

            // 💾 Guardar en la BD
            if ($m->crear()) {
                header('Location: ' . BASE_URL . 'admin'); // 🔁 Redirige al dashboard
                exit();
            } else {
                // Si falla, volver al formulario
                $categoriaModel = $this->model('Categoria');
                $this->view('productos/crear', [
                    'categorias' => $categoriaModel->obtenerTodos(),
                    'error' => 'No se pudo guardar el producto'
                ]);
            }
        }
    }


    //Mostrar el formulario de edición de un producto existente.
    public function editar($id = null)
    {
        // Validar el ID
        if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
            header('Location: ' . BASE_URL . 'admin');
            exit();
        }

        // Iniciar sesión solo si no está activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar permisos de administrador
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 1) {
            header('Location: ' . BASE_URL);
            exit();
        }

        // Cargar modelos
        $productoModel = $this->model('Producto');
        $categoriaModel = $this->model('Categoria');

        // Obtener datos del producto y categorías
        $producto = $productoModel->obtenerPorId((int) $id);
        $categorias = $categoriaModel->obtenerTodos();

        // Reutilizar la misma vista de creación
        $this->view('productos/crear', [
            'titulo' => 'Editar producto',
            'producto' => $producto,
            'categorias' => $categorias,
            'modo' => 'editar'
        ]);
    }


    //Procesar el formulario de edición y actualizar el producto en la base de datos.
    public function actualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $m = $this->model('Producto');
            $m->id_producto = (int) $_POST['id_producto'];
            $m->id_categoria = (int) $_POST['id_categoria'];
            $m->nombre_producto = trim($_POST['nombre_producto']);
            $m->sku = trim($_POST['sku']);
            $m->descripcion = trim($_POST['descripcion']);
            $m->precio_unitario = (float) $_POST['precio_unitario'];
            $m->stock = (int) $_POST['stock'];
            $m->proveedor = trim($_POST['proveedor']);

            // Verificar que el producto realmente exista
            $productoExistente = $m->obtenerPorId($m->id_producto);
            if (!$productoExistente) {
                die('Producto no encontrado.');
            }

            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/img/productos/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0777, true);

                $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $nombreLimpio = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $m->nombre_producto));
                $nombreArchivo = $nombreLimpio . '.' . $extension;
                $rutaDestino = $uploadDir . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $m->imagen_url = 'productos/' . $nombreArchivo;
                }
            } else {
                // Mantener imagen anterior
                $actual = $m->obtenerPorId($m->id_producto);
                $m->imagen_url = $actual['imagen_url'] ?? 'productos/default.jpg';
            }
            //Actualizar en BD
            if ($m->actualizar()) {
                header('Location: ' . BASE_URL . 'admin/productos');
                exit();
            }
        }
    }


    // Realizar una búsqueda de productos por nombre o descripción// app/controllers/ProductoController.php
    public function buscar()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';

        // Si no se ingresó término, redirigir al listado principal
        if ($termino === '') {
            header('Location: ' . BASE_URL . 'producto');
            exit;
        }

        $productoModel = $this->model('Producto');
        $categoriaModel = $this->model('Categoria'); // ✅ Para mostrar filtros, etc.

        $productos = $productoModel->buscar($termino);
        $categorias = $categoriaModel->obtenerTodos();

        $data = [
            'titulo' => 'Resultados de búsqueda: ' . htmlspecialchars($termino),
            'productos' => $productos,
            'categorias' => $categorias,
            'termino' => htmlspecialchars($termino)
        ];

        // ✅ Reutilizamos la vista de listado principal
        $this->view('productos/index', $data);
    }



    //Listar productos pertenecientes a una categoría específica.
    // Reemplaza el método listar actual por este (opcional)
    public function listar($categoria = null)
    {
        if (!isset($_SESSION['usuario_rol']) || (int) $_SESSION['usuario_rol'] !== 1) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }
        $pm = $this->model('Producto');
        $cm = $this->model('Categoria');
        if ($categoria && filter_var($categoria, FILTER_VALIDATE_INT)) {
            $productos = $pm->obtenerPorCategoria((int) $categoria);
            $sel = (int) $categoria;
        } else {
            $productos = $pm->obtenerTodos();
            $sel = null;
        }
        $this->view('productos/listar', [
            'productos' => $productos,
            'categorias' => $cm->obtenerTodos(),
            'categoriaSeleccionada' => $sel
        ]);
    }


    //Mostrar una vista dedicada a una categoría, con todos los productos y el nombre de la categoría.
    public function categoria($id_categoria = null)
    {
        // Validar que el ID de categoría sea un entero válido
        if (!$id_categoria || !filter_var($id_categoria, FILTER_VALIDATE_INT)) {
            header('Location: ' . BASE_URL . 'producto');//redirecciona a la lista de productos
            exit();
        }
        $id_categoria = (int) $id_categoria; //asegura que $id_categoria es un entero

        //Cargamos el modelo  de producto
        $productoModel = $this->model('Producto');
        //Se hace la llamada del metodo obtenerPorCategoria del modelo Producto 
        //y se almacena el array retornado en la variable local productos
        $productos = $productoModel->obtenerPorCategoria($id_categoria);

        // Obtener el nombre de la categoría (para título)
        $categoriaNombre = !empty($productos) ? $productos[0]['nombre_categoria'] : 'Categoría';

        $this->view('productos/categoria', [
            'titulo' => "Categoría - {$categoriaNombre}",
            'productos' => $productos,
            'categoriaNombre' => $categoriaNombre
        ]);
    }

    public function eliminar($id = null)
    {
        session_start();
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 1) {
            header('Location: ' . BASE_URL);
            exit();
        }

        if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
            header('Location: ' . BASE_URL . 'producto');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productoModel = $this->model('Producto');
            if ($productoModel->eliminar((int) $id)) {
                header('Location: ' . BASE_URL . 'producto');
                exit();
            } else {
                // Manejo error (puedes poner flash message)
                header('Location: ' . BASE_URL . 'producto');
                exit();
            }
        }
    }

    public function buscarAjax()
    {
        if (!isset($_GET['q'])) {
            echo ''; // no enviar nada si está vacío
            return;
        }

        $termino = trim($_GET['q']);
        if ($termino === '') {
            echo '';
            return;
        }

        $productoModel = $this->model('Producto');
        $productos = $productoModel->buscar($termino);

        // Reutilizar la vista parcial (sin header/footer)
        ob_start();
        include __DIR__ . '/../views/productos/_lista.php';
        $html = ob_get_clean();

        echo $html;
    }


}
?>