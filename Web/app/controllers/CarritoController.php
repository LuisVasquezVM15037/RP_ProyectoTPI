<?php
class CarritoController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $carrito = $_SESSION['carrito'];

        // Calcular totales
        $subtotal = 0;
        foreach ($carrito as $item) {
            $subtotal += $item['subtotal'];
        }

        $iva = $subtotal * 0.13;
        $total = $subtotal + $iva;

        $_SESSION['total'] = $total;

        $this->view('carrito/index', [
            'titulo' => 'Mi Carrito - Tienda Verde',
            'carrito' => $carrito,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total
        ]);
    }

    // Agregar producto al carrito
    public function agregar($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $productoModel = $this->model('Producto');
        $producto = $productoModel->obtenerPorId($id);

        if (!$producto) {
            header("Location: " . BASE_URL);
            exit();
        }

        if (!isset($_SESSION['carrito']))
            $_SESSION['carrito'] = [];

        $cantidad = isset($_POST['cantidad']) ? max(1, (int) $_POST['cantidad']) : 1;
        if ($cantidad > (int) $producto['stock'])
            $cantidad = (int) $producto['stock'];

        // Buscar si ya existe
        $found = false;
        foreach ($_SESSION['carrito'] as &$it) {
            if ($it['id_producto'] == $producto['id_producto']) {
                $it['cantidad'] = min($it['cantidad'] + $cantidad, (int) $producto['stock']);
                $it['subtotal'] = round($it['cantidad'] * $it['precio_unitario'], 2);
                $found = true;
                break;
            }
        }
        unset($it);

        if (!$found) {
            $_SESSION['carrito'][] = [
                'id_producto' => (int) $producto['id_producto'],
                'nombre_producto' => $producto['nombre_producto'] ?? 'Producto',
                'precio_unitario' => (float) $producto['precio_unitario'],
                'cantidad' => $cantidad,
                'subtotal' => round((float) $producto['precio_unitario'] * $cantidad, 2),
                'imagen_url' => $producto['imagen_url'] ?? 'productos/default.jpg',
                'sku' => $producto['sku'] ?? ''
            ];
        }

        header("Location: " . BASE_URL . "carrito");
        exit();
    }


    // Eliminar producto del carrito
    public function eliminar($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $index => $item) {
                if ($item['id_producto'] == $id) {
                    unset($_SESSION['carrito'][$index]);
                    break;
                }
            }
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        }

        header("Location: " . BASE_URL . "carrito");
        exit();
    }

    // Vaciar carrito completo
    public function vaciar()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        unset($_SESSION['carrito']);
        header("Location: " . BASE_URL . "carrito");
        exit();
    }

    // Confirmar compra (puede ser invitado o usuario)
 // Mostrar vista de confirmación (GET). El form POST va a pedido/confirmarCompra
    public function confirmar()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $carritoSes = $_SESSION['carrito'] ?? [];
        if (empty($carritoSes)) {
            header('Location: ' . BASE_URL . 'carrito');
            exit();
        }

        // Reutilizar index logic para obtener items y totales
        $productoModel = $this->model('Producto');

        $items = [];
        $subtotal = 0.0;

        foreach ($carritoSes as $it) {
            $prod = $productoModel->obtenerPorId((int)($it['id_producto'] ?? 0));
            if (!$prod) continue;

            $cantidad = (int)($it['cantidad'] ?? 1);
            $precio = (float)($prod['precio_unitario'] ?? 0.0);
            $sub = round($precio * $cantidad, 2);

            $items[] = [
                'id_producto' => (int)$prod['id_producto'],
                'nombre_producto' => $prod['nombre_producto'] ?? ($it['nombre_producto'] ?? 'Producto'),
                'precio_unitario' => $precio,
                'cantidad' => $cantidad,
                'subtotal' => $sub,
                'imagen_url' => $prod['imagen_url'] ?? ($it['imagen_url'] ?? 'productos/default.jpg'),
                'sku' => $prod['sku'] ?? ($it['sku'] ?? '')
            ];

            $subtotal += $sub;
        }

        $iva = round($subtotal * 0.13, 2);
        $total = round($subtotal + $iva, 2);

        $usuario = null;
        if (!empty($_SESSION['usuario_id'])) {
            $usuarioModel = $this->model('Usuario');
            $usuario = $usuarioModel->obtenerPorId($_SESSION['usuario_id']);
        }

        $this->view('pedido/confirmar', [
            'carrito' => $items,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'usuario' => $usuario
        ]);
    }


    // public function confirmacion()
    // {
    //     $this->view('carrito/confirmacion', ['titulo' => 'Confirmación de compra']);
    // }

    // 🔹 Aumentar cantidad con AJAX
    public function aumentarAjax($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id_producto'] == $id) {
                $item['cantidad']++;
                $item['subtotal'] = $item['cantidad'] * $item['precio_unitario'];
                break;
            }
        }

        $productoModel = $this->model('Producto');
        $total = $productoModel->calcularTotalCarrito($_SESSION['carrito']);

        header('Content-Type: application/json');
        echo json_encode([
            'ok' => true,
            'carrito' => $_SESSION['carrito'],
            'total' => $total
        ]);
        exit;
    }

    // 🔹 Disminuir cantidad con AJAX
    public function disminuirAjax($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        foreach ($_SESSION['carrito'] as $index => &$item) {
            if ($item['id_producto'] == $id) {
                $item['cantidad']--;
                if ($item['cantidad'] <= 0) {
                    unset($_SESSION['carrito'][$index]);
                } else {
                    $item['subtotal'] = $item['cantidad'] * $item['precio_unitario'];
                }
                break;
            }
        }

        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        $productoModel = $this->model('Producto');
        $total = $productoModel->calcularTotalCarrito($_SESSION['carrito']);

        header('Content-Type: application/json');
        echo json_encode([
            'ok' => true,
            'carrito' => $_SESSION['carrito'],
            'total' => $total
        ]);
        exit;
    }

    // 🔹 Eliminar producto con AJAX
    public function eliminarAjax($id)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $index => $item) {
                if ($item['id_producto'] == $id) {
                    unset($_SESSION['carrito'][$index]);
                    break;
                }
            }
            $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        }

        // Recalcular total
        $productoModel = $this->model('Producto');
        $total = $productoModel->calcularTotalCarrito($_SESSION['carrito']);

        header('Content-Type: application/json');
        echo json_encode([
            'ok' => true,
            'carrito' => $_SESSION['carrito'],
            'total' => $total
        ]);
        exit;
    }

    // Muestra la página de pago
    public function pago()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $carrito = $_SESSION['carrito'] ?? [];
        if (empty($carrito)) {
            header('Location: ' . BASE_URL . 'carrito');
            exit();
        }

        // Validar stock y actualizar precios
        $productoModel = $this->model('Producto');
        $stockInvalido = false;
        $subtotal = 0;

        foreach ($carrito as &$item) {
            $producto = $productoModel->obtenerPorId($item['id_producto']);
            if (!$producto) {
                $stockInvalido = true;
                break;
            }

            // Validar stock
            if ($producto['stock'] < $item['cantidad']) {
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => 'Stock insuficiente para ' . $item['nombre_producto']
                ];
                $stockInvalido = true;
                break;
            }

            // Actualizar precio por si hubo cambios
            $item['precio_unitario'] = (float)$producto['precio_unitario'];
            $item['subtotal'] = round($item['precio_unitario'] * $item['cantidad'], 2);
            $subtotal += $item['subtotal'];
        }

        if ($stockInvalido) {
            header('Location: ' . BASE_URL . 'carrito');
            exit();
        }

        // Actualizar carrito con precios actualizados
        $_SESSION['carrito'] = $carrito;

        $iva = round($subtotal * 0.13, 2);
        $total = round($subtotal + $iva, 2);

        $_SESSION['total'] = $total; // Asegurarse de que el total esté en la sesión para PayPal

        $this->view('carrito/pago', [
            'total' => $total,
            'subtotal' => $subtotal,
            'iva' => $iva
        ]);
    }


}
?>