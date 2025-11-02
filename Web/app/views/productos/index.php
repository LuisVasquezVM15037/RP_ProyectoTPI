<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 px-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-semibold text-success">
            <?= isset($termino) && $termino !== '' ? 'Resultados de búsqueda: ' . htmlspecialchars($termino) : 'Catálogo de productos'; ?>
        </h3>
        <a href="<?= BASE_URL ?>" class="btn btn-outline-success btn-sm">Volver al inicio</a>
    </div>
    <div id="resultados-busqueda" class="row g-4">
        <?php if (!empty($productos)): ?>
            <?php foreach ($productos as $p): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card producto-card h-100 border-0 shadow-sm">

                        <!-- Imagen del producto -->
                        <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                            <img src="<?= BASE_URL . 'public/img/' . htmlspecialchars($p['imagen_url']) ?>"
                                alt="<?= htmlspecialchars($p['nombre_producto']) ?>" class="img-fluid p-2 rounded"
                                style="object-fit: contain; max-height: 180px;">
                        </div>

                        <!-- Información del producto -->
                        <div class="card-body text-center producto-info">
                            <h6 class="fw-bold text-success mb-1">
                                <?= htmlspecialchars($p['nombre_producto']) ?>
                            </h6>
                            <p class="text-muted small mb-2">
                                <?= htmlspecialchars(substr($p['descripcion'], 0, 60)) ?>...
                            </p>
                            <p class="fw-bold text-dark mb-3">
                                $<?= number_format($p['precio_unitario'], 2) ?>
                            </p>

                            <a href="<?= BASE_URL ?>producto/ver/<?= $p['id_producto'] ?>"
                                class="btn btn-secondary btn-sm w-100">
                                🔍 Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <img src="<?= BASE_URL ?>public/img/empty.jpg" alt="Sin resultados" style="width:120px;">
                <p class="text-muted mt-3">
                    No se encontraron productos para "<strong><?= htmlspecialchars($termino) ?></strong>".
                </p>
                <a href="<?= BASE_URL ?>producto" class="btn btn-success btn-sm mt-3">Ver todo el catálogo</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>