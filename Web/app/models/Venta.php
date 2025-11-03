<?php
require_once __DIR__ . '/../../core/Model.php';

class Venta extends Model
{
    private $tabla = 'pedido';

    // Obtener las últimas ventas registradas 
    public function obtenerUltimasVentas()
    {
        $query = "SELECT 
                    p.id_pedido AS id_venta,
                    COALESCE(u.nombre_usuario, 'Cliente no registrado') AS cliente,
                    p.total_pedido AS total,
                    p.metodo_pago,
                    p.estado_pedido,
                    p.fecha_pedido AS fecha_pago
                  FROM {$this->tabla} p
                  LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
                  ORDER BY p.id_pedido DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
