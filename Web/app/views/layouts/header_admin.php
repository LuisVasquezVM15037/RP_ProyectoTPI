<!DOCTYPE html>
<html lang="es">

<head>
  <title>Panel Administrativo | Jardinería y Plantas</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link href="<?= BASE_URL ?>public/css/admin.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

  <div class="admin-wrapper d-flex">
    <!-- ======= SIDEBAR ======= -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <div class="admin-avatar">
          <i class="bi bi-person-circle"></i>
        </div>
        <h5>Administrador</h5>
      </div>

      <ul class="sidebar-menu">
        <li><a href="<?= BASE_URL ?>admin" class="active"><i class="bi bi-speedometer2"></i> Resumen</a></li>
        <li><a href="<?= BASE_URL ?>admin/productos"><i class="bi bi-box-seam"></i> Gestionar Inventario</a></li>
        <li><a href="<?= BASE_URL ?>admin/usuarios"><i class="bi bi-people"></i> Gestionar Usuarios</a></li>
        <li><a href="<?= BASE_URL ?>admin/ventas"><i class="bi bi-bar-chart-line"></i> Reportes</a></li>
      </ul>

      <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>usuario/logout" class="btn btn-logout w-100">
          <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
      </div>
    </aside>
    <?php
    // Configurar idioma y zona horaria
    setlocale(LC_TIME, 'es_SV.UTF-8', 'es_SV', 'Spanish_El_Salvador', 'es_ES.UTF-8', 'esp_esp');
    date_default_timezone_set('America/El_Salvador');
    ?>

    <!-- ======= MAIN ======= -->
    <div class="main-content flex-grow-1">
      <header class="admin-header d-flex justify-content-between align-items-center px-4 py-3 shadow-sm">
        <h2 class="mb-0">Panel de Control</h2>
        <div class="text-end small text-muted">
          <?= strftime('%A, %d de %B del %Y - %I:%M %p') ?>
        </div>
      </header>

      <main class="p-4">