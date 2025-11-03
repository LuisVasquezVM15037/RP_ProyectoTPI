<?php
class ReporteController extends Controller
{
    public function exportarVentasExcel()
    {
        // Cargar el modelo
        $ventaModel = $this->model('Venta');
        $ventas = $ventaModel->obtenerUltimasVentas();

        // Configurar cabeceras para descarga de Excel
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=ventas_" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Diccionario de métodos de pago
        $metodos = [
            0 => 'Efectivo',
            1 => 'Tarjeta',
            2 => 'Transferencia',
            3 => 'PayPal',
            4 => 'Crédito',
            5 => 'Otro'
        ];

        //  Encabezado de tabla
        echo "<table border='1' style='border-collapse:collapse; font-family:Arial; font-size:13px;'>";
        echo "<thead style='background-color:#cce5cc; font-weight:bold;'>
                <tr>
                    <th>ID Venta</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Método de Pago</th>
                    <th>Tipo</th>
                    <th>Estado Pedido</th>
                    <th>Fecha Pago</th>
                </tr>
              </thead>";
        echo "<tbody>";

        // Rellenar filas con las ventas
        foreach ($ventas as $v) {

            $id = htmlspecialchars($v['id_venta'] ?? '');
            $cliente = htmlspecialchars($v['cliente'] ?? 'Cliente no registrado');
            $total = '$' . number_format((float)($v['total'] ?? 0), 2);
            $metodo = $metodos[$v['metodo_pago']] ?? 'Desconocido';

            // Tipo de pago 
            $tipo = 'Contado';

            // Estado del pedido
            switch ((int)($v['estado_pedido'] ?? 0)) {
                case 1:
                    $estado = 'Pendiente';
                    break;
                case 2:
                    $estado = 'Pagado';
                    break;
                default:
                    $estado = 'Desconocido';
                    break;
            }

            $fecha = htmlspecialchars($v['fecha_pago'] ?? '-');

            echo "<tr>
                    <td>{$id}</td>
                    <td>{$cliente}</td>
                    <td>{$total}</td>
                    <td>{$metodo}</td>
                    <td>{$tipo}</td>
                    <td>{$estado}</td>
                    <td>{$fecha}</td>
                  </tr>";
        }

        echo "</tbody></table>";
        exit;
    }
}
