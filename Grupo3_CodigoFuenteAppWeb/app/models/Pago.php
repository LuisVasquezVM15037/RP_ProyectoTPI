<?php
require_once __DIR__ . '/../../core/Model.php';

class Pago extends Model
{
    protected $tabla = 'pago';

    public $id_pago;
    public $id_pedido;
    public $fecha_pago;
    public $monto_pago;
    public $es_pago_a_plazo;
    public $numero_cuota;
    public $total_cuotas;
    public $estado_cuota;
    public $fecha_vencimiento_cuota;
    public $descripcion_pago;

    public function obtenerPorPedido($id_pedido)
    {
        $sql = "SELECT * FROM {$this->tabla}
                WHERE id_pedido = :id_pedido
                ORDER BY fecha_pago DESC";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear()
    {
        $sql = "INSERT INTO {$this->tabla}
                (id_pedido, fecha_pago, monto_pago, es_pago_a_plazo, numero_cuota,
                 total_cuotas, estado_cuota, fecha_vencimiento_cuota, descripcion_pago)
                VALUES (:id_pedido, :fecha, :monto, :es_plazo, :num_cuota,
                        :tot_cuotas, :estado, :fec_venc, :desc)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
        $st->bindValue(':fecha', $this->fecha_pago);
        $st->bindValue(':monto', $this->monto_pago);
        $st->bindValue(':es_plazo', $this->es_pago_a_plazo);
        $st->bindValue(':num_cuota', $this->numero_cuota);
        $st->bindValue(':tot_cuotas', $this->total_cuotas);
        $st->bindValue(':estado', $this->estado_cuota);
        $st->bindValue(':fec_venc', $this->fecha_vencimiento_cuota);
        $st->bindValue(':desc', $this->descripcion_pago);
        if ($st->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function obtenerRecientes($limite = 10)
    {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY fecha_pago DESC LIMIT :limite";
        $st = $this->db->prepare($sql);
        $st->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario($id_usuario)
    {
        $sql = "SELECT pg.*, ped.total_pedido
                FROM {$this->tabla} pg
                INNER JOIN pedido ped ON pg.id_pedido = ped.id_pedido
                WHERE ped.id_usuario = :id_usuario
                ORDER BY pg.fecha_pago DESC";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorPedido($id_pedido)
    {
        $sql = "SELECT COUNT(*) FROM {$this->tabla} WHERE id_pedido = :id_pedido";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $st->execute();
        return (int)$st->fetchColumn();
    }

    public function sumaPorPedido($id_pedido)
    {
        $sql = "SELECT IFNULL(SUM(monto_pago),0) FROM {$this->tabla} WHERE id_pedido = :id_pedido";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_pedido', $id_pedido, PDO::PARAM_INT);
        $st->execute();
        return (float)$st->fetchColumn();
    }
}
