<!-- incluye el header -->
<?php include __DIR__ . '/../layouts/header.php'; ?>
<!-- contenedor principal de la categoria-->
<div class="container my-5 categoria-pagina">
    <h1 class="text-center fw-bold text-success mb-3">
        🗂️ <?= htmlspecialchars($categoriaNombre) ?>
    </h1>
    <p class="text-center text-muted mb-5">
        Explora nuestros productos de <?= htmlspecialchars($categoriaNombre) ?>.
    </p>
    <!-- lista de productos filtados por categoría -->
    <?php if (!empty($productos)): ?>
        <!-- area para productos -->
        <div class="row g-4">
            <!-- bucle para mostrar cada producto en la BD segun flitro de categotia -->
            <?php foreach ($productos as $producto): ?>
                <!-- area de cada producto -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <!-- carts de producto -->
                    <div class="card producto-card h-100 border-0 shadow-sm">
                        <!-- imagen del producto -->
                        <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                            <img src="<?= BASE_URL . 'public/img/' . htmlspecialchars($producto['imagen_url']) ?>"
                                alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" class="img-fluid p-2 rounded"
                                style="object-fit: contain; max-height: 180px;">
                        </div>
                        <!-- Informacion del producto -->
                        <div class="card-body text-center producto-info">
                            <!-- nombre del producto -->
                            <h5 class="fw-bold text-success">
                                <?= htmlspecialchars($producto['nombre_producto']) ?>
                            </h5>
                            <!-- descripcion corta del producto -->
                            <p class="text-muted small mb-2">
                                <?= htmlspecialchars(substr($producto['descripcion'], 0, 80)) ?>...
                            </p>
                            <!-- precio del producto -->
                            <p class="precio fw-bold text-dark mb-3">
                                $<?= number_format($producto['precio_unitario'], 2) ?>
                            </p>
                            <!-- botones de accion -->
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Ver detalles -->
                                <a href="<?= BASE_URL ?>producto/ver/<?= $producto['id_producto'] ?>"
                                    class="btn btn-secondary btn-sm">
                                    🔍 Ver Detalles
                                </a>
                                <!-- Agregar al carrito -->
                                <a href="<?= BASE_URL ?>carrito/agregar/<?= $producto['id_producto'] ?>"
                                    class="btn btn-success btn-sm">
                                    🛒 Agregar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- mensaje si no hay productos en la categoria -->
        <p class="text-center text-muted mt-4">
            No hay productos disponibles en esta categoría 🌱
        </p>
    <?php endif; ?>
</div>
<!-- incluye el footer -->
<?php include __DIR__ . '/../layouts/footer.php'; ?>