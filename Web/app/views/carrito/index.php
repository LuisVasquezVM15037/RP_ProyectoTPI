<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">

    <h2 style="margin-top:40px;">🛒 Carrito de Compras</h2>

    <?php if (!empty($carrito)): ?>
        <div class="tabla-carrito">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $item): ?>

                        <tr data-id="<?= $item['id_producto'] ?>">
                            <td class="producto-detalle">
                                <img src="<?= BASE_URL . 'public/img/' . htmlspecialchars($item['imagen_url'] ?? 'productos/default.jpg') ?>"
                                    alt="<?= htmlspecialchars($item['nombre_producto'] ?? 'Producto') ?>"
                                    class="card-img-top img-fluid">
                                <p><?= htmlspecialchars($item['nombre_producto'] ?? 'Producto') ?></p>

                                <small><?= htmlspecialchars($item['sku']) ?></small>
            </div>
            </td>
            <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
            <td>
                <button class="cantidad-btn disminuir" data-id="<?= $item['id_producto'] ?>">−</button>
                <span class="cantidad"><?= (int) $item['cantidad'] ?></span>
                <button class="cantidad-btn aumentar" data-id="<?= $item['id_producto'] ?>">+</button>
            </td>
            <td class="subtotal">$<?= number_format($item['subtotal'], 2) ?></td>
            <td>
                <button class="btn btn-danger eliminar-item" data-id="<?= $item['id_producto'] ?>">🗑</button>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>


        </table>

        <div class="resumen-carrito">
            <p><strong>Subtotal:</strong> $<?= number_format($subtotal, 2) ?></p>
            <p><strong>IVA (13%):</strong> $<?= number_format($iva, 2) ?></p>
            <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
            <a href="<?= BASE_URL ?>carrito/confirmar" class="btn btn-primary">Procesar Pago</a>
        </div>
    </div>
<?php else: ?>
    <p style="margin-top:50px; text-align:center;">🛍 Tu carrito está vacío</p>
<?php endif; ?>

</div>
<script>
    // Definimos primero la constante global BASE_URL
    const BASE_URL = "<?= BASE_URL ?>";
</script>

<script src="<?= BASE_URL ?>public/js/carrito.js"></script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>