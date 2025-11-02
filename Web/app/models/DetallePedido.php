<?php
require_once __DIR__ . '/../../core/Model.php';

class DetallePedido extends Model
{
    protected $tabla = 'detallepedido';

    public $id_detalle_pedido;
    public $id_pedido;
    public $id_producto;
    public $cantidad_producto;
    public $total_detalle_pedido;

    public function obtenerPorPedido($id_pedido)
    {
        $sql = "SELECT dp.*, p.nombre_producto, p.precio_unitario, p.imagen_url
                FROM {$this->tabla} dp
                INNER JOIN producto p ON dp.id_producto = p.id_producto
                WHERE dp.id_pedido = :id_pedido";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($id_pedido, $id_producto, $cantidad, $total_detalle)
    {
        $sql = "INSERT INTO {$this->tabla} (id_pedido, id_producto, cantidad_producto, total_detalle_pedido)
                VALUES (:id_pedido, :id_producto, :cantidad, :total)";
        $st = $this->db->prepare($sql);
        return $st->execute([
            ':id_pedido' => (int)$id_pedido,
            ':id_producto' => (int)$id_producto,
            ':cantidad' => (int)$cantidad,
            ':total' => $total_detalle
        ]);
    }

    // Para dashboard (mes actual)
    public function ingresosPorRangoFechas($fi, $ff)
    {
        $sql = "SELECT IFNULL(SUM(dp.total_detalle_pedido),0)
                FROM {$this->tabla} dp
                INNER JOIN pedido pe ON dp.id_pedido = pe.id_pedido
                WHERE pe.fecha_pedido BETWEEN :fi AND :ff";
        $st = $this->db->prepare($sql);
        $st->bindValue(':fi', $fi);
        $st->bindValue(':ff', $ff);
        $st->execute();
        return (float)$st->fetchColumn();
    }

    public function productosMasVendidos($limite = 10)
    {
        $sql = "SELECT p.*, SUM(dp.cantidad_producto) AS total_vendido
                FROM {$this->tabla} dp
                INNER JOIN producto p ON dp.id_producto = p.id_producto
                GROUP BY dp.id_producto
                ORDER BY total_vendido DESC
                LIMIT :limite";
        $st = $this->db->prepare($sql);
        $st->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
