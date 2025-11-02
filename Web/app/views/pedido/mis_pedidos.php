<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">

    <section class="hero">
        <h1>Mis Pedidos</h1>
        <p>Consulta el historial de tus compras realizadas en <strong>VerdeVida</strong>.</p>
    </section>

    <?php if (!empty($pedidos)): ?>
        <div class="tabla-carrito mt-4">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Método de Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td>#<?= htmlspecialchars($pedido['id_pedido']) ?></td>
                            <td><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></td>
                            <td><strong>$<?= number_format($pedido['total_pedido'], 2) ?></strong></td>
                            <td>
                                <?php
                                $estado = (int) $pedido['estado_pedido'];
                                $clase = match ($estado) {
                                    1 => 'badge bg-warning text-dark',   // Pendiente
                                    2 => 'badge bg-success',             // Completado
                                    3 => 'badge bg-danger',              // Cancelado
                                    default => 'badge bg-secondary'
                                };
                                $texto = match ($estado) {
                                    1 => 'Pendiente',
                                    2 => 'Completado',
                                    3 => 'Cancelado',
                                    default => 'Desconocido'
                                };
                                ?>
                                <span class="<?= $clase; ?>"><?= $texto; ?></span>
                            </td>
                            <td>
                                <?php
                                switch ((int) $pedido['metodo_pago']) {
                                    case 0:
                                        echo '<i class="bi bi-cash-stack text-success"></i> Efectivo';
                                        break;
                                    case 1:
                                        echo '<i class="bi bi-credit-card-2-front text-primary"></i> Tarjeta';
                                        break;
                                    case 2:
                                        echo '<i class="bi bi-bank text-info"></i> Transferencia';
                                        break;
                                    case 3:
                                        echo '<i class="bi bi-paypal text-primary"></i> PayPal';
                                        break;
                                    case 4:
                                        echo '<i class="bi bi-clock-history text-warning"></i> Crédito a plazo';
                                        break;
                                    default:
                                        echo 'Otro';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>pedido/ver/<?= $pedido['id_pedido'] ?>"
                                    class="btn btn-secondary btn-sm">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <div class="no-productos">
            <i class="bi bi-box-seam" style="font-size: 3rem; color: #ccc;"></i>
            <p class="mt-3">Aún no tienes pedidos registrados.</p>
            <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">
                <i class="bi bi-shop"></i> Ir a comprar
            </a>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>