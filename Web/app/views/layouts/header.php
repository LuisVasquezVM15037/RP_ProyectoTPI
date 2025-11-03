<?php
// Inicia sesión si no existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar modelo Producto para mostrar el total del carrito
require_once __DIR__ . '/../../../app/models/Producto.php';
$productoModel = new Producto();
$carrito_total = isset($_SESSION['carrito']) ? $productoModel->calcularTotalCarrito($_SESSION['carrito']) : 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?? 'Tienda Verde' ?></title>

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
    <link rel="icon" href="<?= BASE_URL ?>public/img/icon.png">
</head>

<body>
    <!-- HEADER PRINCIPAL -->
    <header class="main-header shadow-sm">
        <div class="leaf-overlay">
            <div class="leaf leaf-1"></div>
            <div class="leaf leaf-2"></div>
            <div class="leaf leaf-3"></div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark py-3">
            <div class="container-fluid px-3">
                <!-- Logo -->
                <a class="navbar-brand fw-bold fs-3" href="<?= BASE_URL ?>">Tienda Verde</a>

                <!-- Botón hamburguesa móvil -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Contenido del menú -->
                <div class="collapse navbar-collapse justify-content-between" id="navbarMain">

                    <!-- Búsqueda -->
                    <form class="d-flex ms-4 my-3 my-lg-0 flex-grow-1" method="GET"
                        action="<?= BASE_URL ?>producto/buscar" style="max-width: 400px;">
                        <input class="form-control rounded-pill px-3" type="text" name="q"
                            placeholder="Buscar productos...">
                    </form>

                    <!-- Acciones: carrito + usuario -->
                    <ul class="navbar-nav align-items-center gap-3">
                        <!-- Carrito -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>carrito" class="nav-link fw-bold text-white">
                                🛒 <span class="cart-total">$<?= number_format($carrito_total, 2) ?></span>
                            </a>
                        </li>
                        <!-- Usuario logueado -->
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white fw-bold" href="#" id="userDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>usuario/perfil">👤 Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>pedido/misPedidos">🧾 Mis Pedidos</a>
                                    </li>
                                    <?php if ($_SESSION['usuario_rol'] == 1): ?>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>producto/crear">🪴 Crear Producto</a>
                                        </li>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>categoria">🗂 Categorías</a></li>
                                    <?php endif; ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>usuario/logout">🚪 Cerrar
                                            Sesión</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="<?= BASE_URL ?>usuario/login"
                                    class="btn btn-outline-light rounded-pill px-3 fw-bold">LOGIN</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Apertura del contenido principal -->
    <main class="main-content container py-2">