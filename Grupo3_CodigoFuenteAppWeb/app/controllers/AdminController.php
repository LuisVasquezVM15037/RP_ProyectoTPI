<?php
// Controlador principal hijo para el panel de administración
class AdminController extends Controller
{
    //Al instanciar el controlador se ejecuta automáticamente una validacion de sesion y usuario
    public function __construct()
    {
        //Validar si la sesión aún no ha sido iniciada.
        if (session_status() === PHP_SESSION_NONE)
            session_start(); //Si no se encuentra iniciada, se inicia

        //Verifica si el usuario no tiene sesión o no tiene el rol de administrador (rol 1)
        if (!isset($_SESSION['usuario_rol']) || (int) $_SESSION['usuario_rol'] !== 1) {
            // Si no cumple las condiciones, lo redirige al login
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }
    }//fin constructor

    //Funcion principal que muestra el panel de administración
    public function index()
    {
        //Carga de los modelos necesarios para manejo de datos
        $productoModel = $this->model('Producto'); //Producto model
        $usuarioModel = $this->model('Usuario'); //usuario model
        $pedidoModel = $this->model('Pedido'); //Pedido model

        //Conteo de total de registros de productos y usuarios para mostrarlos en el dashboard
        $totalProductos = count($productoModel->obtenerTodos() ?? []); // operador null coalescing (??) por si la función devuelve null
        $totalUsuarios = count($usuarioModel->obtenerTodos() ?? []);

        //Obtiene estadísticas de ventas y pedidos
        $stats = $pedidoModel->obtenerEstadisticas();
        $ventasHoy = $pedidoModel->obtenerVentasHoy();

        //Arreglo con totales para mostrar en el dashboard
        $totales = [
            'productos' => $totalProductos,
            'usuarios' => $totalUsuarios,
            'ventas_hoy' => $ventasHoy,
            'pedidos' => (int) ($stats['total_pedidos'] ?? 0)
        ];
        //Obtiene los últimos 5 pedidos (ventas recientes)
        $ultimasVentas = $pedidoModel->obtenerUltimosPedidos(5);

        //Envía los datos a la vista 'admin/dashboard'
        $this->view('admin/dashboard', [
            'usuarioNombre' => $_SESSION['usuario_nombre'] ?? 'Administrador',
            'totales' => $totales,
            'ultimasVentas' => $ultimasVentas
        ]);
    }

    //Mostrar la lista de usuarios registrados
    public function usuarios()
    {
        //Carga el modelo de usuario y obtiene todos los registros
        $usuarios = $this->model('Usuario')->obtenerTodos();
        //Envía los datos a la vista correspondiente
        $this->view('admin/usuarios', ['usuarios' => $usuarios]);
    }

    //Mostrar la lista de productos y sus categorías
    public function productos()
    {
        //Carga el modelo de producto y categoria
        $productoModel = $this->model('Producto');
        $categoriaModel = $this->model('Categoria');

        //Envía los datos combinados a la vista
        $this->view('admin/listar', [
            'productos' => $productoModel->obtenerTodos(),
            'categorias' => $categoriaModel->obtenerTodos()
        ]);
    }

    //aun esta pendiente de implementar, solamente se muestran datos para hacer la vista
public function ventas()
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['usuario_rol']) || (int)$_SESSION['usuario_rol'] !== 1) {
        header('Location: ' . BASE_URL . 'usuario/login'); exit();
    }

    $pedido = $this->model('Pedido');
    $detalle = $this->model('DetallePedido');
    $pago = $this->model('Pago');

    $resumen = [
        'hoy'     => $pedido->obtenerVentasHoy(),
        'mes'     => $detalle->ingresosPorRangoFechas(date('Y-m-01'), date('Y-m-t')),
        'ordenes' => $pedido->obtenerEstadisticas()['total_pedidos'] ?? 0
    ];

    // Puedes mostrar pagos recientes como “últimas ventas”
    $ultimas = $pedido->obtenerUltimosPedidos(20);

    $this->view('admin/ventas', compact('resumen','ultimas'));
}

}
