<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <!-- Seccion del Banner -->
    <section class="hero text-center mb-3 p-3 rounded shadow-sm">
        <h1 class="display-5 fw-bold">Bienvenido a <span class="text-success">Tienda Verde</span> 🌱</h1>
        <p class="lead mb-3">Encuentra las mejores plantas, semillas y materiales para tu jardín.</p>
        <a href="<?= BASE_URL ?>producto" class="btn btn-primary btn-lg">Explorar Productos</a>
    </section>

    <!-- Muestra las categorias segun la tabla -->
    <?php if (!empty($categorias)): ?>
        <section class="mb-5">
            <h2 class="text-center mb-4 fw-bold text-success">Categorías</h2>
            <div class="row g-4">
                <?php foreach ($categorias as $categoria): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card categoria-card h-100 border-0 shadow-sm"
                            onclick="window.location.href='<?= BASE_URL ?>producto/categoria/<?= $categoria['id_categoria'] ?>'">
                            <div class="categoria-imagen">
                                <img src="<?= $categoria['imagen'] ?? BASE_URL . 'public/img/default_categoria.jpg' ?>"
                                    alt="<?= htmlspecialchars($categoria['nombre_categoria']) ?>"
                                    class="card-img-top img-fluid">
                            </div>
                            <div class="card-body categoria-info">
                                <h5 class="card-title text-success fw-bold">
                                    <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                                </h5>
                                <p class="text-muted mb-0">Ver productos disponibles</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Seccion de productos destacasdos -->
    <section class="productos-destacados mb-5">
        <h2 class="text-center fw-bold text-success mb-4">Productos Destacados</h2>

        <?php if (!empty($productos)): ?>
            <!-- mesclar aleatoriamente el array de productos para mostrarlos en productos destacados -->
            <?php $productosAleatorios = $productos;
            shuffle($productosAleatorios); ?>
            <div class="row g-4">
                <?php foreach (array_slice($productosAleatorios, 0, 8) as $producto): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card producto-card h-100 border-0 shadow-sm">
                            <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                                <img src="<?= BASE_URL . 'public/img/' . htmlspecialchars($producto['imagen_url']) ?>"
                                    alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" class="img-fluid p-2 rounded"
                                    style="object-fit: contain; max-height: 180px;">

                            </div>
                            <div class="card-body producto-info">
                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h5>
                                <p class="text-muted categoria mb-1">
                                    📁 <?= htmlspecialchars($producto['nombre_categoria'] ?? 'Sin categoría') ?>
                                </p>
                                <p class="descripcion"><?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...</p>

                                <div class="d-flex justify-content-between align-items-center producto-footer mb-3">
                                    <p class="precio mb-0">$<?= number_format($producto['precio_unitario'], 2) ?></p>
                                    <small class="text-muted">Stock: <?= $producto['stock'] ?></small>
                                </div>

                                <div class="d-grid">
                                    <a href="<?= BASE_URL ?>producto/ver/<?= $producto['id_producto'] ?>"
                                        class="btn btn-secondary">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-muted mt-4">No hay productos disponibles en este momento.</p>
        <?php endif; ?>
    </section>

    <!-- 🔸 CARACTERÍSTICAS -->
    <section class="info-seccion my-5">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <div class="fs-1">🚚</div>
                    <h5 class="fw-bold mt-3">Envío Rápido</h5>
                    <p class="text-muted mb-0">Entregamos en 24-48 horas</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <div class="fs-1">💳</div>
                    <h5 class="fw-bold mt-3">Pago Seguro</h5>
                    <p class="text-muted mb-0">Con múltiples métodos</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <div class="fs-1">✨</div>
                    <h5 class="fw-bold mt-3">Calidad Garantizada</h5>
                    <p class="text-muted mb-0">Los mejores productos</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-4 bg-white rounded shadow-sm h-100">
                    <div class="fs-1">📞</div>
                    <h5 class="fw-bold mt-3">Soporte 24/7</h5>
                    <p class="text-muted mb-0">Siempre disponibles</p>
                </div>
            </div>
        </div>
    </section>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>