<?php include __DIR__ . '/../layouts/header_admin.php'; ?>

<div class="container-fluid">

  <!-- === MÉTRICAS DE RESUMEN === -->
  <div class="metrics-row d-flex flex-wrap justify-content-between align-items-stretch mb-4">
    <div class="metric-box flex-fill text-center p-4 mx-2 my-2">
      <div class="metric-icon"><i class="bi bi-box-seam"></i></div>
      <div class="metric-title">Productos</div>
      <div class="metric-value"><?= (int) ($totales['productos'] ?? 0) ?></div>
    </div>
    <div class="metric-box flex-fill text-center p-4 mx-2 my-2">
      <div class="metric-icon"><i class="bi bi-people-fill"></i></div>
      <div class="metric-title">Usuarios</div>
      <div class="metric-value"><?= (int) ($totales['usuarios'] ?? 0) ?></div>
    </div>
    <div class="metric-box flex-fill text-center p-4 mx-2 my-2">
      <div class="metric-icon"><i class="bi bi-cash-stack"></i></div>
      <div class="metric-title">Venta del día</div>
      <div class="metric-value">$<?= number_format((float) ($totales['ventas_hoy'] ?? 0), 2) ?></div>
    </div>
    <div class="metric-box flex-fill text-center p-4 mx-2 my-2">
      <div class="metric-icon"><i class="bi bi-truck"></i></div>
      <div class="metric-title">Pedidos</div>
      <div class="metric-value"><?= (int) ($totales['pedidos'] ?? 0) ?></div>
    </div>
  </div>

  <!-- === FILA DE GRÁFICOS (DIARIOS Y SEMANALES) === -->
  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="card shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="fw-semibold text-center m-0">Ventas Diarias</h4>
        </div>
        <canvas id="chartVentas"></canvas>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm p-3">
        <h4 class="fw-semibold mb-3">Pedidos Semanales</h4>
        <canvas id="chartPedidos"></canvas>
      </div>
    </div>
  </div>

  <!-- === FILA DE GRÁFICOS (MENSUALES Y ANUALES) === -->
  <div class="row g-4">
    <div class="col-lg-6">
      <div class="card shadow-sm p-3">
        <h4 class="fw-semibold mb-3">Ventas Mensuales</h4>
        <canvas id="chartMensual"></canvas>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm p-3">
        <h4 class="fw-semibold mb-3">Ventas Anuales</h4>
        <canvas id="chartAnual"></canvas>
      </div>
    </div>
  </div>

</div>

<script>
<canvas id="chartVentas"></canvas>
<canvas id="chartPedidos"></canvas>
<canvas id="chartMensual"></canvas>
<canvas id="chartAnual"></canvas>

<script>
  const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= BASE_URL ?>public/js/dashboard-charts.js"></script>

<?php include __DIR__ . '/../layouts/footer_admin.php'; ?>
