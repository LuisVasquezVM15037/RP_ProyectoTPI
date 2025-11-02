<?php
class DashboardController extends Controller
{
public function datos()
{
    header('Content-Type: application/json');

    $pedidoModel = $this->model('Pedido');

    $ventasDiarias = $pedidoModel->obtenerVentasDiarias(7);
    $pedidosSemanales = $pedidoModel->obtenerPedidosPorSemana(4);
    $ventasMensuales = $pedidoModel->obtenerVentasMensuales(12);
    $ventasAnuales = $pedidoModel->obtenerVentasAnuales(6);

    echo json_encode([
        'ventasDiarias' => $ventasDiarias,
        'pedidosSemanales' => $pedidosSemanales,
        'ventasMensuales' => $ventasMensuales,
        'ventasAnuales' => $ventasAnuales
    ]);

    exit; // 👈 AGREGA ESTO al final (importante)
}

}
