<?php
require_once __DIR__ . '/../helpers/Logger.php';
class PedidoController extends Controller
{
    // Obtiene un token de acceso de PayPal
    private function _get_paypal_token()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . '/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        $json = json_decode($result);
        return $json->access_token ?? null;
    }

    // Crea una orden en PayPal y devuelve el ID
    public function crearOrdenPaypal()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $total = $_SESSION['total'] ?? 0;
        if ($total <= 0) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Invalid total']);
            exit();
        }

        $token = $this->_get_paypal_token();
        if (!$token) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to get PayPal token']);
            exit();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . '/v2/checkout/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Necesitamos las cabeceras de respuesta para obtener Paypal-Debug-Id
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($total, 2, '.', '')
                ]
            ]]
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $raw = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            header('HTTP/1.1 500 Internal Server Error');
            // Devolvemos el error específico de cURL para depuración
            echo json_encode(['error' => 'Error de cURL al contactar con PayPal', 'details' => $error_msg]);
            exit();
        }
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($raw, 0, $header_size);
        $body = substr($raw, $header_size);
        curl_close($ch);

        // Log de headers y body para poder correlacionar en PayPal Dashboard
        Logger::info('crearOrdenPaypal - PayPal response headers', $header_text);
        Logger::info('crearOrdenPaypal - PayPal response body', $body);

        header('Content-Type: application/json');
        echo $body;
        exit();
    }

    // Captura el pago de una orden de PayPal
    public function capturarOrdenPaypal()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $data = json_decode(file_get_contents('php://input'), true);
        // Log request payload para depuración
        Logger::info('capturarOrdenPaypal - request payload', $data);
        $orderID = $data['orderID'] ?? null;

        if (!$orderID) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['success' => false, 'error' => 'No Order ID provided']);
            exit();
        }

        $token = $this->_get_paypal_token();
        if (!$token) {
            header('HTTP/1.1 500 Internal Server Error');
            Logger::error('capturarOrdenPaypal - token failure');
            echo json_encode(['success' => false, 'error' => 'PayPal token failure']);
            exit();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . '/v2/checkout/orders/' . $orderID . '/capture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Capturar headers para extraer Paypal-Debug-Id
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $raw = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            Logger::error('capturarOrdenPaypal - cURL error', $err);
            echo json_encode(['success' => false, 'error' => 'cURL error', 'details' => $err]);
            exit();
        }
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_text = substr($raw, 0, $header_size);
        $body = substr($raw, $header_size);
        curl_close($ch);

        // Intentar extraer Paypal-Debug-Id desde headers
        $debugId = null;
        if (preg_match('/Paypal-Debug-Id:\s*(\S+)/i', $header_text, $m)) {
            $debugId = trim($m[1]);
        }

        $response = json_decode($body, true);
        // Log PayPal response headers/body y debug id
        Logger::info('capturarOrdenPaypal - PayPal response headers', $header_text);
        Logger::info('capturarOrdenPaypal - PayPal response body', $body);
        if ($debugId) {
            Logger::info('capturarOrdenPaypal - PayPal Debug ID', $debugId);
        }
        // Log PayPal response
        Logger::info('capturarOrdenPaypal - PayPal capture response', ['http_status' => $http_status, 'response' => $response]);

        if ($http_status == 201 && $response['status'] == 'COMPLETED') {
            // Guardar pedido en la base de datos
            $pedidoModel = $this->model('Pedido');
            Logger::info('capturarOrdenPaypal - creando pedido desde carrito', ['usuario_id' => $_SESSION['usuario_id'] ?? null, 'carrito' => $_SESSION['carrito'] ?? []]);
            $pedidoId = $pedidoModel->crearPedidoDesdeCarritoConPago(
                $_SESSION['carrito'] ?? [],
                $_SESSION['usuario_id'] ?? null,
                'Dirección de PayPal', // O obtenerla de $response si está disponible
                3, // ID para método de pago PayPal (3 = PayPal, 4 = Crédito)
                1,
                $response['payer']['email_address'] ?? null
            );

            if ($pedidoId) {
                Logger::info('capturarOrdenPaypal - pedido creado', ['pedidoId' => $pedidoId]);
                unset($_SESSION['carrito']);
                unset($_SESSION['total']);
                // Marcar pedido como completado porque la captura en PayPal fue exitosa
                try {
                    $pedidoModel->actualizarEstado($pedidoId, 2); // 2 = completado
                    Logger::info('capturarOrdenPaypal - pedido marcado como COMPLETADO', ['pedidoId' => $pedidoId]);
                } catch (Exception $e) {
                    Logger::error('capturarOrdenPaypal - fallo al actualizar estado', $e->getMessage());
                }

                echo json_encode(['success' => true, 'pedidoId' => $pedidoId]);
                exit();
            } else {
                Logger::error('capturarOrdenPaypal - failed to save order', ['response' => $response]);
                echo json_encode(['success' => false, 'error' => 'Failed to save order']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'PayPal payment not completed', 'details' => $response]);
            exit();
        }
    }
    
    // Endpoint de prueba: crea una orden y devuelve sólo el id (útil para debugging)
    public function testPaypal()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $token = $this->_get_paypal_token();
        if (!$token) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to get PayPal token']);
            exit();
        }

        $total = $_SESSION['total'] ?? 1.00;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . '/v2/checkout/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($total, 2, '.', '')
                ]
            ]]
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        $raw = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            Logger::error('testPaypal - cURL error', $err);
            echo json_encode(['error' => 'cURL error', 'details' => $err]);
            exit();
        }
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = $raw;
        curl_close($ch);

        $resp = json_decode($body, true);
        if (isset($resp['id'])) {
            echo json_encode(['id' => $resp['id']]);
            exit();
        }
        echo json_encode(['error' => 'No id returned', 'raw' => $body]);
        exit();
    }

    // Health endpoint: comprueba si el servidor puede obtener token de PayPal
    public function health()
    {
        $token = $this->_get_paypal_token();
        if ($token) {
            echo json_encode(['ok' => true]);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['ok' => false]);
        }
        exit();
    }

    // Endpoint sencillo para verificar que el logger puede escribir en logs/app.log
    public function pingLog()
    {
        Logger::info('pingLog - test entry', ['time' => date('c')]);
        echo json_encode(['ok' => true, 'msg' => 'ping logged']);
        exit();
    }
    
    // Procesar pagos no-PayPal
    public function procesarPago()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');
        
        // Verificar si hay carrito y total (evitar empty() en total para valores 0.00)
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito']) || count($_SESSION['carrito']) === 0
            || !isset($_SESSION['total']) || (float)$_SESSION['total'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Carrito vacío o total inválido']);
            exit();
        }

        $metodoPago = $_POST['metodoPago'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        
        if (empty($metodoPago) || empty($direccion)) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
            exit();
        }

        // Validar stock antes de procesar
        $productoModel = $this->model('Producto');
        $carrito = $_SESSION['carrito'];
        $stockInvalido = false;
        $mensajeError = '';

        foreach ($carrito as $item) {
            $producto = $productoModel->obtenerPorId($item['id_producto']);
            if (!$producto) {
                $stockInvalido = true;
                $mensajeError = 'Producto no encontrado';
                break;
            }
            if ($producto['stock'] < $item['cantidad']) {
                $stockInvalido = true;
                $mensajeError = 'Stock insuficiente para ' . $item['nombre_producto'];
                break;
            }
        }

        if ($stockInvalido) {
            echo json_encode(['success' => false, 'message' => $mensajeError]);
            exit();
        }

        $pedidoModel = $this->model('Pedido');
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        // Contado (0,1,2,3) = Pagado; Crédito (4) = Pendiente solo si es a plazos (>1 cuotas)
        $cuotas = 1; // en este flujo no-crédito lo fijamos en 1
        $estadoInicial = (((string)$metodoPago === '4') && $cuotas > 1) ? 1 : 2;
        
        try {
            $pedidoId = $pedidoModel->crearPedidoDesdeCarritoConPago(
                $carrito,
                $usuario_id,
                $direccion,
                (int)$metodoPago,
                $cuotas, // cuotas = 1 para pagos que no son a crédito
                null // email_anonimo
            );

            if ($pedidoId) {
                // Actualizar estado del pedido (Pagado para contado; Pendiente solo si crédito)
                $pedidoModel->actualizarEstado($pedidoId, $estadoInicial);
                
                // Limpiar carrito
                unset($_SESSION['carrito']);
                unset($_SESSION['total']);
                
                echo json_encode([
                    'success' => true,
                    'pedidoId' => $pedidoId,
                    'message' => 'Pedido procesado correctamente'
                ]);
                exit();
            }
        } catch (Exception $e) {
            Logger::error('procesarPago - Error al crear pedido', [
                'error' => $e->getMessage(),
                'metodo_pago' => $metodoPago,
                'usuario_id' => $usuario_id
            ]);
        }

        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar el pedido. Por favor intente nuevamente.'
        ]);
        exit();
    }

    // Procesar compra desde la vista pedido/confirmar.php (submit tradicional)
    public function confirmarCompra()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Validar carrito en sesión (el total lo recalculamos)
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito']) || count($_SESSION['carrito']) === 0) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Carrito vacío'];
            header('Location: ' . BASE_URL . 'carrito');
            exit();
        }

        // Normalizar campos del formulario
        $direccion = $_POST['direccion_envio'] ?? $_POST['direccion'] ?? '';
        $metodoForm = isset($_POST['metodo_pago']) ? (int)$_POST['metodo_pago'] : (isset($_POST['metodoPago']) ? (int)$_POST['metodoPago'] : 0);
        $totalCuotas = isset($_POST['total_cuotas']) ? (int)$_POST['total_cuotas'] : 0;

        // Usar el método tal cual viene de la vista (0=Efectivo, 1=Tarjeta, 2=Transferencia, 3=PayPal, 4=Crédito)
        $metodo = $metodoForm;

        // Si el usuario eligió PayPal en esta vista, se maneja con el botón de PayPal (JS), no por submit
        if ($metodo === 3) {
            header('Location: ' . BASE_URL . 'carrito/pago');
            exit();
        }

        if (empty($direccion)) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Debes ingresar una dirección de envío'];
            header('Location: ' . BASE_URL . 'pedido/confirmar');
            exit();
        }

        // Validar stock nuevamente y recalcular precios antes de crear pedido
        $productoModel = $this->model('Producto');
        $carrito = $_SESSION['carrito'];
        foreach ($carrito as $idx => $item) {
            $producto = $productoModel->obtenerPorId($item['id_producto']);
            if (!$producto || (int)$producto['stock'] < (int)$item['cantidad']) {
                $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Stock insuficiente para ' . ($item['nombre_producto'] ?? 'producto')];
                header('Location: ' . BASE_URL . 'carrito');
                exit();
            }
            // Ajustar precio a último valor de BD
            $carrito[$idx]['precio_unitario'] = (float)$producto['precio_unitario'];
            $carrito[$idx]['subtotal'] = round($carrito[$idx]['precio_unitario'] * (int)$carrito[$idx]['cantidad'], 2);
        }
        // Persistir ajustes al carrito en sesión
        $_SESSION['carrito'] = $carrito;

        // Crear pedido y descontar stock
        $pedidoModel = $this->model('Pedido');
        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $cuotas = ($metodo === 4 && $totalCuotas > 1) ? $totalCuotas : 1;

        try {
            $pedidoId = $pedidoModel->crearPedidoDesdeCarritoConPago(
                $carrito,
                $usuario_id,
                $direccion,
                (int)$metodo,
                (int)$cuotas,
                null
            );

            if ($pedidoId) {
                // Contado (0,1,2,3) = Pagado; Crédito (4) = Pendiente solo si es a plazos (>1 cuotas)
                $estadoInicial = ($metodo === 4 && $cuotas > 1) ? 1 : 2;
                $pedidoModel->actualizarEstado($pedidoId, $estadoInicial);

                // Limpiar carrito
                unset($_SESSION['carrito']);
                unset($_SESSION['total']);

                header('Location: ' . BASE_URL . 'pedido/confirmacion/' . $pedidoId);
                exit();
            }
        } catch (Exception $e) {
            Logger::error('confirmarCompra - Error al crear pedido', [
                'error' => $e->getMessage(),
                'metodo' => $metodo,
                'usuario_id' => $usuario_id
            ]);
        }

        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'No se pudo procesar la compra. Intente nuevamente.'];
        header('Location: ' . BASE_URL . 'carrito');
        exit();
    }
    
    // --- Métodos existentes ---

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "pedido/misPedidos");
        } else {
            header("Location: " . BASE_URL . "carrito");
        }
        exit();
    }

    public function confirmacion($id_pedido = null)
    {
        if (!$id_pedido) {
            header('Location: ' . BASE_URL);
            exit();
        }
        $pedidoModel = $this->model('Pedido');
        $detalleModel = $this->model('DetallePedido');
        $pedido = $pedidoModel->obtenerPorId((int) $id_pedido);
        $detalles = $detalleModel->obtenerPorPedido((int) $id_pedido);
        $this->view('pedido/confirmacion', [
            'pedido' => $pedido,
            'detalles' => $detalles
        ]);
    }

    public function misPedidos()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['usuario_id'])) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Debes iniciar sesión'];
            header('Location: ' . BASE_URL . 'usuario/login');
            exit();
        }
        $pedidoModel = $this->model('Pedido');
        $pedidos = $pedidoModel->obtenerPorUsuario($_SESSION['usuario_id']);
        $this->view('pedido/mis_pedidos', ['pedidos' => $pedidos]);
    }
}
?>