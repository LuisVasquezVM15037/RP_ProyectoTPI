<?php include __DIR__ . '/../layouts/header_admin.php'; ?>

<div class="d-flex justify-content-between align-items-center page-heading">
  <h5 class="mb-0">💰 Ventas</h5>
</div>

<div class="d-flex justify-content-end mb-3">
  <a href="<?= BASE_URL ?>reporte/exportarVentasExcel" class="btn btn-success">
    <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
  </a>
</div>

<!-- === MÉTRICAS DE RESUMEN === -->
<div class="row g-3 mb-3">
  <div class="col-sm-4">
    <div class="card admin-card">
      <div class="card-body">
        <div class="metric-title">Hoy</div>
        <div class="metric-value">
          $<?= number_format((float) ($resumen['hoy'] ?? 0), 2) ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card admin-card">
      <div class="card-body">
        <div class="metric-title">Este mes</div>
        <div class="metric-value">
          $<?= number_format((float) ($resumen['mes'] ?? 0), 2) ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card admin-card">
      <div class="card-body">
        <div class="metric-title">Órdenes</div>
        <div class="metric-value">
          <?= (int) ($resumen['ordenes'] ?? 0) ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- === FILTRO DE FECHAS PARA REPORTE === -->
<div class="card admin-card mb-3">
  <div class="card-header fw-semibold">Filtrar fechas</div>
  <div class="card-body">
    <form method="GET" action="" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label for="fecha_inicio" class="form-label fw-semibold">Desde:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control"
          value="<?= htmlspecialchars($_GET['fecha_inicio'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label for="fecha_fin" class="form-label fw-semibold">Hasta:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control"
          value="<?= htmlspecialchars($_GET['fecha_fin'] ?? '') ?>">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search"></i> Filtrar
        </button>
      </div>
    </form>
  </div>
</div>
<!-- === TABLA DE ÚLTIMAS VENTAS === -->
<div class="card admin-card">
  <div class="card-header fw-semibold">Últimas ventas registradas</div>
  <div class="card-body">
    <?php if (!empty($ultimas)): ?>
      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
          <thead class="table-light text-center">
            <tr>
              <th>#</th>
              <th>Cliente</th>
              <th>Total</th>
              <th>Método de Pago</th>
              <th>Tipo</th>
              <th>Estado Pedido</th>
              <th>Cuota</th>
              <th>Estado Cuota</th>
              <th>Fecha Pago</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php foreach ($ultimas as $v): ?>
              <tr>
                <td><?= (int) ($v['id_venta'] ?? 0) ?></td>
                <td><?= htmlspecialchars($v['cliente'] ?? 'Cliente no registrado') ?></td>
                <td>$<?= number_format((float) ($v['total'] ?? 0), 2) ?></td>

                <!-- Método de pago -->
                <td>
                  <?php
                  $metodos = [
                    0 => 'Efectivo',
                    1 => 'Tarjeta',
                    2 => 'Transferencia',
                    3 => 'PayPal',
                    4 => 'Crédito',
                    5 => 'Otro'
                  ];
                  echo htmlspecialchars($metodos[$v['metodo_pago']] ?? 'Desconocido');
                  ?>
                </td>

                <!-- Tipo de pago -->
                <td>
                  <?= ($v['es_pago_a_plazo'])
                    ? '<span class="badge bg-info text-dark">A Plazo</span>'
                    : '<span class="badge bg-success">Contado</span>' ?>
                </td>

                <!-- Estado del pedido -->
                <td>
                  <?php
                  if (($v['estado_pedido'] ?? 1) == 1)
                    echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                  elseif (($v['estado_pedido'] ?? 0) == 2)
                    echo '<span class="badge bg-success">Pagado</span>';
                  else
                    echo '<span class="badge bg-secondary">Desconocido</span>';
                  ?>
                </td>

                <!-- Cuota -->
                <td>
                  <?php
                  if (!empty($v['es_pago_a_plazo'])) {
                    echo ($v['numero_cuota'] ?? 0) . ' / ' . ($v['total_cuotas'] ?? 0);
                  } else {
                    echo '—';
                  }
                  ?>
                </td>

                <!-- Estado de la cuota -->
                <td>
                  <?php
                  if (!empty($v['es_pago_a_plazo'])) {
                    switch ((int) ($v['estado_cuota'] ?? 0)) {
                      case 1:
                        echo '<span class="badge bg-success">Pagada</span>';
                        break;
                      case 2:
                        echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                        break;
                      case 3:
                        echo '<span class="badge bg-danger">Vencida</span>';
                        break;
                      default:
                        echo '<span class="badge bg-secondary">N/A</span>';
                    }
                  } else {
                    echo '—';
                  }
                  ?>
                </td>

                <!-- Fecha del pago -->
                <td><?= htmlspecialchars($v['fecha_pago'] ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted mb-0">No hay ventas registradas aún.</p>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer_admin.php'; ?>