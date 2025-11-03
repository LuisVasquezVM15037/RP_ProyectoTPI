<?php
// Clase ENRUTADORA que gestiona la aplicación MVC. Intercepta e nterpreta las URL que llegan del navegador y decide qué controlador, método y parámetros deben ejecutarse.
class App
{
    // Controlador, método y parámetros por defecto. Estos atributos definen el flujo predeterminado del sistema
    protected $controller = 'HomeController'; //controlador por defecto
    protected $method = 'index'; //método o acción a ejecutar dentro del controlador
    protected $params = []; //parámetros adicionales que pueden venir en la URL (por ejemplo, /producto/detalle/5 → 5 sería un parámetro).

    //Constructor que inicializa la aplicación, interpreta la URL y ejecuta el controlador y método correspondientes.
    //funciona como un enrutador básico para dirigir las solicitudes entrantes a los controladores y métodos adecuados.
    public function __construct()
    {
        // Obtener la URL y dividirla en partes
        $url = $this->parseURL();

        // Verificar controlador 
        // Si el primer segmento de la URL corresponde a un controlador existente, usarlo
        if (!empty($url[0])) {
            //tomar el nombre del primer fragmento de la url y lo concatena con 'Controller' para formar el nombre del controlador
            $controllerName = ucfirst($url[0]) . 'Controller';
            //buesca la ruta del archivo del controlador
            $controllerPath = __DIR__ . '/../app/controllers/' . $controllerName . '.php';//ruta del controlador
            // Si el archivo del controlador existe, usarl
            if (file_exists($controllerPath)) {
                $this->controller = $controllerName; //controlador a usar
                unset($url[0]);
            } else {
                // Si el Controlador no es encontrado, usa HomeController
                $controllerPath = __DIR__ . '/../app/controllers/HomeController.php';
            }
        } else {
            // URL vacía tambien usa HomeController
            $controllerPath = __DIR__ . '/../app/controllers/HomeController.php';
        }

        // Se incluye el archivo del controlador y se crea un objeto/instancia de esa clase
        require_once $controllerPath;
        $this->controller = new $this->controller();

        // Detecta si hay un método específico en la URL
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            //Si el segundo segmento de la URL corresponde a un método existente en el controlador, usarlo
            $this->method = $url[1];
            unset($url[1]);
        }
        // Obtener y asignar los parámetros restantes de la URL (si los hay)
        $this->params = $url ? array_values($url) : [];

        // 🚨 PROTECCIÓN DE ACCESO SEGÚN ROL 🚨
        $this->verificarAcceso();

        // Ejecutar controlador y método con los parámetros incluidos en la URL
        call_user_func_array([$this->controller, $this->method], $this->params);


    }

    // Este método convierte la URL en un arreglo de segmentos para su procesamiento.
    private function parseURL()
    {
        //Detectar rutas vacias o con un URL
        if (isset($_GET['url'])) {
            // retorna un array con los segmentos de la URL
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        // Si no viene ?url=, obtener PATH_INFO (URLs limpias)
        if (isset($_SERVER['PATH_INFO'])) {
            return explode('/', filter_var(trim($_SERVER['PATH_INFO'], '/'), FILTER_SANITIZE_URL));
        }
        // No hay URL → retornar arreglo vacío
        return [];
    }

    /**
     * Verifica que solo los roles permitidos accedan a cada zona
     */
    private function verificarAcceso()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $rol = $_SESSION['usuario_rol'] ?? null;
        $ctrl = strtolower(str_replace('controller', '', get_class($this->controller)));
        $method = strtolower($this->method);

        // Acciones de inventario permitidas solo a admin en ProductoController
        $accionesInventario = ['crear', 'guardar', 'editar', 'actualizar', 'eliminar', 'listar'];

        // 1) Si NO es admin → bloquear todo lo del controlador admin
        if ($ctrl === 'admin' && $rol !== 1) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }

        // 2) Si ES admin → bloquear vistas públicas de tienda:
        //    - home, carrito, contacto, etc.
        //    - en producto SOLO bloquear métodos públicos
        $controladoresPublicos = ['home', 'carrito', 'tienda', 'contacto'];
        $metodosPublicosProducto = ['index', 'ver', 'categoria', 'buscar'];

        if ($rol === 1) {
            if (in_array($ctrl, $controladoresPublicos, true)) {
                header('Location: ' . BASE_URL . 'admin');
                exit();
            }

            if ($ctrl === 'producto' && in_array($method, $metodosPublicosProducto, true)) {
                // admin no ve la tienda de productos: redirige al listado admin
                header('Location: ' . BASE_URL . 'producto/listar');
                exit();
            }
        }

        // 3) Si NO es admin y está intentando usar acciones de inventario:
        if ($rol !== 1 && $ctrl === 'producto' && in_array($method, $accionesInventario, true)) {
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }
    }



}
?>