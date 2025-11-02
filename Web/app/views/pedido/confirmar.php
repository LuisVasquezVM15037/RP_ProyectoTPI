<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-5 mb-5">
  <h3 class="mb-4 text-center">🧾 Confirmar compra</h3>

  <?php if (!empty($carrito)): ?>
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5 class="card-title mb-3">Resumen del pedido</h5>

        <table class="table table-bordered align-middle text-center">
          <thead class="table-success">
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php $total = 0;
            foreach ($carrito as $item):
              $subtotal = $item['precio_unitario'] * $item['cantidad'];
              $total += $subtotal;
              ?>
              <tr>
                <td><?= htmlspecialchars($item['nombre_producto'] ?? 'Producto') ?></td>
                <td><?= (int) $item['cantidad'] ?></td>
                <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                <td>$<?= number_format($subtotal, 2) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="text-end mt-3">
          <h5>Subtotal: $<?= number_format($total, 2) ?></h5>
          <h6>IVA (13%): $<?= number_format($total * 0.13, 2) ?></h6>
          <h4>Total: $<?= number_format($total * 1.13, 2) ?></h4>
        </div>
      </div>
    </div>

    <form action="<?= BASE_URL ?>pedido/confirmarCompra" method="POST" class="card shadow-sm p-4">
      <h5 class="mb-3">Datos de envío y pago</h5>

      <div class="mb-3">
        <label for="direccion_envio" class="form-label fw-semibold">Dirección de envío</label>
        <input type="text" id="direccion_envio" name="direccion_envio" class="form-control"
          value="<?= htmlspecialchars($usuario['direccion_usuario'] ?? '') ?>" required>
      </div>
      <?php if (empty($_SESSION['usuario_id'])): ?>
        <div class="mb-3">
          <label for="email_comprador" class="form-label fw-semibold">Correo electrónico</label>
          <input type="email" id="email_comprador" name="email_comprador" class="form-control" placeholder="tu@correo.com"
            required>
          <div class="form-text text-muted">Usaremos este correo para enviarte la confirmación del pedido.</div>
        </div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="metodo_pago" class="form-label fw-semibold">Método de pago</label>
        <select id="metodo_pago" name="metodo_pago" class="form-select" required>
          <option value="" disabled selected>Seleccione...</option>
          <option value="0">Efectivo</option>
          <option value="1">Tarjeta</option>
          <option value="2">Transferencia</option>
          <option value="3">PayPal</option>
          <option value="4">Crédito (a plazo)</option>
        </select>
      </div>

      <!-- Contenedor PayPal (se muestra sólo si el usuario selecciona PayPal) -->
      <div id="paypal-button-wrapper" style="display:none;">
        <div id="paypal-button-container" class="mt-3"></div>
        <small class="text-muted">Se abrirá una ventana de PayPal para completar el pago.</small>
      </div>

      <div id="cuotas-section" style="display:none;">
        <div class="mb-3">
          <label for="total_cuotas" class="form-label fw-semibold">Número de cuotas</label>
          <select id="total_cuotas" name="total_cuotas" class="form-select">
            <option value="3">3 cuotas</option>
            <option value="6">6 cuotas</option>
            <option value="12">12 cuotas</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= BASE_URL ?>carrito" class="btn btn-secondary">⬅️ Volver</a>
        <button type="submit" class="btn btn-success">✅ Confirmar compra</button>
      </div>
    </form>
  <?php else: ?>
    <div class="alert alert-warning mt-4 text-center">Tu carrito está vacío</div>
  <?php endif; ?>
</div>

<script>
  (function(){
    var metodo = document.getElementById('metodo_pago');
    var cuotas = document.getElementById('cuotas-section');
    var paypalWrapper = document.getElementById('paypal-button-wrapper');
    var form = document.querySelector('form');
    var submitBtn = form.querySelector('button[type="submit"]');

    metodo.addEventListener('change', function () {
      cuotas.style.display = (this.value == '4') ? 'block' : 'none';
      if (this.value == '3') {
        // Mostrar botón de PayPal y ocultar el botón de submit para que el flujo pase por PayPal
        paypalWrapper.style.display = 'block';
        if (submitBtn) submitBtn.style.display = 'none';
      } else {
        paypalWrapper.style.display = 'none';
        if (submitBtn) submitBtn.style.display = 'inline-block';
      }
    });
  })();
</script>

<!-- SDK de PayPal (sandbox/prod usan PAYPAL_CLIENT_ID desde config) -->
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=USD&intent=capture&commit=true"></script>
<!-- Script que maneja la creación/captura (usa #paypal-button-container) -->
<script src="<?php echo BASE_URL; ?>public/js/paypal.js?v=<?php echo time(); ?>"></script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>