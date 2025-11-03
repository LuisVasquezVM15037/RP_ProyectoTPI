<?php
require_once __DIR__ . '/../../core/Model.php';

class Pedido extends Model
{
    protected $tabla = 'pedido';

    public $id_pedido;
    public $id_usuario;
    public $fecha_pedido;
    public $estado_pedido;
    public $total_pedido;
    public $direccion_envio;
    public $impuesto_IVA;
    public $metodo_pago;
    public $email_comprador_anonimo;

    public function obtenerTodos()
    {
        $sql = "SELECT p.*, u.nombre_usuario, u.apellido_usuario
                FROM {$this->tabla} p
                LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
                ORDER BY p.fecha_pedido DESC";
        $st = $this->db->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario($id_usuario)
    {
        $query = "SELECT * FROM {$this->tabla} 
              WHERE id_usuario = :id_usuario
              ORDER BY fecha_pedido DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function obtenerPorId($id)
    {
        $sql = "SELECT p.*, u.nombre_usuario, u.apellido_usuario, u.email_usuario
                FROM {$this->tabla} p
                LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.id_pedido = :id";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function crear()
    {
        $sql = "INSERT INTO {$this->tabla}
                (id_usuario, fecha_pedido, estado_pedido, total_pedido, direccion_envio,
                 impuesto_IVA, metodo_pago, email_comprador_anonimo)
                VALUES (:id_usuario, :fecha, :estado, :total, :direccion, :iva, :metodo, :email_anonimo)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':id_usuario', $this->id_usuario);
        $st->bindValue(':fecha', $this->fecha_pedido);
        $st->bindValue(':estado', $this->estado_pedido);
        $st->bindValue(':total', $this->total_pedido);
        $st->bindValue(':direccion', $this->direccion_envio);
        $st->bindValue(':iva', $this->impuesto_IVA);
        $st->bindValue(':metodo', $this->metodo_pago);
        $st->bindValue(':email_anonimo', $this->email_comprador_anonimo);

        if ($st->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function actualizarEstado($id, $nuevo_estado)
    {
        $sql = "UPDATE {$this->tabla} SET estado_pedido = :estado WHERE id_pedido = :id";
        $st = $this->db->prepare($sql);
        $st->bindValue(':estado', $nuevo_estado, PDO::PARAM_INT);
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        return $st->execute();
    }

    public function obtenerEstadisticas()
    {
        $sql = "SELECT 
                  COUNT(id_pedido) AS total_pedidos,
                  IFNULL(SUM(total_pedido), 0) AS ventas_totales,
                  IFNULL(AVG(total_pedido), 0) AS promedio_venta,
                  SUM(CASE WHEN estado_pedido = 1 THEN 1 ELSE 0 END) AS pendientes,
                  SUM(CASE WHEN estado_pedido = 2 THEN 1 ELSE 0 END) AS completados
                FROM {$this->tabla}";
        $st = $this->db->prepare($sql);
        $st->execute();
        $r = $st->fetch(PDO::FETCH_ASSOC) ?: [];
        return [
            'total_pedidos' => (int) ($r['total_pedidos'] ?? 0),
            'ventas_totales' => (float) ($r['ventas_totales'] ?? 0),
            'promedio_venta' => (float) ($r['promedio_venta'] ?? 0),
            'pendientes' => (int) ($r['pendientes'] ?? 0),
            'completados' => (int) ($r['completados'] ?? 0),
        ];
    }

    public function obtenerUltimosPedidos($limite = 10)
    {
        $sql = "SELECT 
                p.id_pedido AS id_venta,
                IFNULL(CONCAT(u.nombre_usuario, ' ', u.apellido_usuario), 'Cliente no registrado') AS cliente,
                p.total_pedido AS total,
                p.estado_pedido,
                p.metodo_pago,
                DATE_FORMAT(p.fecha_pedido, '%Y-%m-%d') AS fecha,
                pa.es_pago_a_plazo,
                pa.numero_cuota,
                pa.total_cuotas,
                pa.estado_cuota,
                DATE_FORMAT(pa.fecha_pago, '%Y-%m-%d') AS fecha_pago,
                pa.descripcion_pago
            FROM {$this->tabla} p
            LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
            LEFT JOIN pago pa ON p.id_pedido = pa.id_pedido
            ORDER BY p.fecha_pedido DESC
            LIMIT :limite";

        $st = $this->db->prepare($sql);
        $st->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }



    // Para dashboard
    public function obtenerVentasHoy()
    {
        $sql = "SELECT IFNULL(SUM(total_pedido), 0)
                FROM {$this->tabla}
                WHERE DATE(fecha_pedido) = CURDATE()";
        $st = $this->db->prepare($sql);
        $st->execute();
        return (float) $st->fetchColumn();
    }

    // Método transaccional: crear pedido + detalles + pago + descontar stock
    public function crearPedidoDesdeCarritoConPago($carrito, $usuario_id = null, $direccion = '', $metodo = 0, $cuotas = 0, $email_anonimo = null)
    {
        try {
            // Aseguramos excepciones PDO
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->beginTransaction();

            if (empty($carrito)) {
                throw new Exception("Carrito vacío");
            }

            // Recalcular totales (basado en precio_unitario enviado por controller, que ya consultó BD)
            $subtotal = 0.0;
            foreach ($carrito as $it) {
                $precio = (float) ($it['precio_unitario'] ?? 0);
                $cantidad = (int) ($it['cantidad'] ?? 0);
                if ($precio <= 0 || $cantidad <= 0) {
                    throw new Exception("Precio o cantidad inválidos");
                }
                $subtotal += round($precio * $cantidad, 2);
            }
            if ($subtotal <= 0)
                throw new Exception("Total inválido");

            $iva = round($subtotal * 0.13, 2);
            $total = round($subtotal + $iva, 2);

            // Validar comprador: si no hay usuario, debe venir email
            if (empty($usuario_id) && empty($email_anonimo)) {
                throw new Exception("Se requiere correo para compra anónima");
            }

            // Determinar el estado inicial del pedido
            // Contado (0,1,2,3) = Pagado; Crédito (4) = Pendiente solo si es a plazos (>1 cuotas)
            $estadoInicial = ($metodo == 4 && (int)$cuotas > 1) ? 1 : 2;
            
            // Insertar pedido
            $stmt = $this->db->prepare("
                INSERT INTO pedido (id_usuario, fecha_pedido, estado_pedido, total_pedido, direccion_envio, impuesto_IVA, metodo_pago, email_comprador_anonimo)
                VALUES (:id_usuario, CURDATE(), :estado, :total, :direccion, :iva, :metodo, :email)
            ");
            $stmt->execute([
                ':id_usuario' => $usuario_id,
                ':estado' => $estadoInicial,
                ':total' => $total,
                ':direccion' => $direccion,
                ':iva' => $iva,
                ':metodo' => (int) $metodo,
                ':email' => $usuario_id ? null : $email_anonimo
            ]);

            $pedido_id = (int) $this->db->lastInsertId();
            if ($pedido_id <= 0)
                throw new Exception("No se pudo crear pedido");

            // Insertar detalles y descontar stock
            foreach ($carrito as $it) {
                $id_producto = (int) $it['id_producto'];
                $cantidad = (int) $it['cantidad'];
                $precio = (float) $it['precio_unitario'];
                $total_det = round($precio * $cantidad, 2);

                // Insert detalle
                $stmtDet = $this->db->prepare("
                    INSERT INTO detallepedido (id_pedido, id_producto, cantidad_producto, total_detalle_pedido)
                    VALUES (:pedido, :producto, :cantidad, :total)
                ");
                $stmtDet->execute([
                    ':pedido' => $pedido_id,
                    ':producto' => $id_producto,
                    ':cantidad' => $cantidad,
                    ':total' => $total_det
                ]);

                // Descontar stock de forma segura (UPDATE ... WHERE stock >= cantidad)
                $stmtStock = $this->db->prepare("
                    UPDATE producto SET stock = stock - :cantidad WHERE id_producto = :id_producto AND stock >= :cantidad
                ");
                $stmtStock->execute([':cantidad' => $cantidad, ':id_producto' => $id_producto]);
                if ($stmtStock->rowCount() !== 1) {
                    throw new Exception("Stock insuficiente para producto ID {$id_producto}");
                }
            }

            // Registrar pago
            $es_plazo = ($metodo == 4);
            if ($es_plazo && $cuotas > 1) {
                $monto_cuota = round($total / max(1, $cuotas), 2);
                $stmtPago = $this->db->prepare("
                    INSERT INTO pago (id_pedido, fecha_pago, monto_pago, es_pago_a_plazo, numero_cuota, total_cuotas, estado_cuota, descripcion_pago)
                    VALUES (:pedido, CURDATE(), :monto, 1, 1, :cuotas, 2, :desc)
                ");
                $stmtPago->execute([
                    ':pedido' => $pedido_id,
                    ':monto' => $monto_cuota,
                    ':cuotas' => $cuotas,
                    ':desc' => "Pago inicial. {$cuotas} cuotas"
                ]);
            } else {
                $stmtPago = $this->db->prepare("
                    INSERT INTO pago (id_pedido, fecha_pago, monto_pago, es_pago_a_plazo, estado_cuota, descripcion_pago)
                    VALUES (:pedido, CURDATE(), :monto, 0, 1, :desc)
                ");
                $stmtPago->execute([
                    ':pedido' => $pedido_id,
                    ':monto' => $total,
                    ':desc' => "Pago completo"
                ]);
            }

            $this->db->commit();
            return $pedido_id;
        } catch (Exception $e) {
            error_log("Pedido::crearPedidoDesdeCarritoConPago ERROR: " . $e->getMessage());
            try {
                $this->db->rollBack();
            } catch (Exception $x) {
            }
            return false;
        }
    }

    public function crearPedidoDesdeCarritoConPagoAnonimo($carrito, $usuario_id, $direccion, $metodo, $cuotas = 0, $email_anonimo = null)
    {
        try {
            $this->db->beginTransaction();

            if (empty($carrito)) {
                throw new Exception("Carrito vacío");
            }

            $total = 0;
            foreach ($carrito as $item) {
                $subtotal = (float) ($item['precio_unitario'] ?? 0) * (int) ($item['cantidad'] ?? 0);
                if ($subtotal <= 0)
                    throw new Exception("Subtotal inválido");
                $total += $subtotal;
            }

            $iva = $total * 0.13;
            $total_con_iva = $total + $iva;

            // ✅ Insertar pedido (considerando usuario o anónimo)
            $stmt = $this->db->prepare("
            INSERT INTO pedido (id_usuario, fecha_pedido, estado_pedido, total_pedido, 
                                direccion_envio, impuesto_IVA, metodo_pago, email_comprador_anonimo)
            VALUES (:usuario_id, NOW(), :estado, :total, :direccion, :iva, :metodo, :email)
        ");
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':estado' => ($metodo == 4 && (int)$cuotas > 1) ? 1 : 2,
                ':total' => $total_con_iva,
                ':direccion' => $direccion,
                ':iva' => $iva,
                ':metodo' => $metodo,
                ':email' => $email_anonimo
            ]);

            $pedido_id = $this->db->lastInsertId();

            // 🔹 Detalles y descuento de stock
            foreach ($carrito as $item) {
                $id_producto = $item['id_producto'];
                $cantidad = (int) $item['cantidad'];
                $precio = (float) $item['precio_unitario'];
                $subtotal = $precio * $cantidad;

                $stmtDet = $this->db->prepare("
                INSERT INTO detallepedido (id_pedido, id_producto, cantidad_producto, total_detalle_pedido)
                VALUES (:pedido, :producto, :cantidad, :total)
            ");
                $stmtDet->execute([
                    ':pedido' => $pedido_id,
                    ':producto' => $id_producto,
                    ':cantidad' => $cantidad,
                    ':total' => $subtotal
                ]);

                $stmtStock = $this->db->prepare("UPDATE producto SET stock = stock - :cant WHERE id_producto = :id");
                $stmtStock->execute([':cant' => $cantidad, ':id' => $id_producto]);
            }

            // 🔹 Registrar pago
            $es_plazo = ($metodo == 4);
            if ($es_plazo && $cuotas > 1) {
                $monto_cuota = $total_con_iva / $cuotas;
                $stmtPago = $this->db->prepare("
                INSERT INTO pago (id_pedido, fecha_pago, monto_pago, es_pago_a_plazo, 
                                  numero_cuota, total_cuotas, estado_cuota, descripcion_pago)
                VALUES (:pedido, NOW(), :monto, 1, 1, :cuotas, 2, :desc)
            ");
                $stmtPago->execute([
                    ':pedido' => $pedido_id,
                    ':monto' => $monto_cuota,
                    ':cuotas' => $cuotas,
                    ':desc' => 'Pago inicial en cuotas (' . $cuotas . ' totales)'
                ]);
            } else {
                $stmtPago = $this->db->prepare("
                INSERT INTO pago (id_pedido, fecha_pago, monto_pago, es_pago_a_plazo, estado_cuota, descripcion_pago)
                VALUES (:pedido, NOW(), :monto, 0, 1, :desc)
            ");
                $stmtPago->execute([
                    ':pedido' => $pedido_id,
                    ':monto' => $total_con_iva,
                    ':desc' => 'Pago completo al confirmar compra'
                ]);
            }

            $this->db->commit();
            return $pedido_id;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en crearPedidoDesdeCarritoConPagoAnonimo: " . $e->getMessage());
            return false;
        }
    }

    // === VENTAS DIARIAS ===
    public function obtenerVentasDiarias($dias = 7)
    {
        $sql = "SELECT 
                DAYNAME(fecha_pedido) AS dia,
                SUM(total_pedido) AS total_ventas,
                COUNT(id_pedido) AS pedidos
            FROM pedido
            WHERE fecha_pedido >= DATE_SUB(CURDATE(), INTERVAL :dias DAY)
            GROUP BY DAY(fecha_pedido)
            ORDER BY fecha_pedido ASC";
        $st = $this->db->prepare($sql);
        $st->bindValue(':dias', (int) $dias, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    // === PEDIDOS SEMANALES ===
    public function obtenerPedidosPorSemana($semanas = 4)
    {
        $sql = "SELECT 
                CONCAT('Semana ', WEEK(fecha_pedido, 1)) AS semana,
                COUNT(*) AS pedidos
            FROM pedido
            WHERE fecha_pedido >= DATE_SUB(CURDATE(), INTERVAL :semanas WEEK)
            GROUP BY WEEK(fecha_pedido, 1)
            ORDER BY WEEK(fecha_pedido, 1)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':semanas', (int) $semanas, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    // === VENTAS MENSUALES ===
    public function obtenerVentasMensuales($meses = 12)
    {
        $sql = "SELECT 
                DATE_FORMAT(fecha_pedido, '%b') AS mes,
                SUM(total_pedido) AS total_ventas,
                COUNT(*) AS pedidos
            FROM pedido
            WHERE fecha_pedido >= DATE_SUB(CURDATE(), INTERVAL :meses MONTH)
            GROUP BY MONTH(fecha_pedido)
            ORDER BY MONTH(fecha_pedido)";
        $st = $this->db->prepare($sql);
        $st->bindValue(':meses', (int) $meses, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    // === VENTAS ANUALES ===
    public function obtenerVentasAnuales($anios = 6)
    {
        $sql = "SELECT 
                YEAR(fecha_pedido) AS anio,
                SUM(total_pedido) AS total_ventas
            FROM pedido
            WHERE YEAR(fecha_pedido) >= YEAR(CURDATE()) - :anios
            GROUP BY YEAR(fecha_pedido)
            ORDER BY anio";
        $st = $this->db->prepare($sql);
        $st->bindValue(':anios', (int) $anios, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>