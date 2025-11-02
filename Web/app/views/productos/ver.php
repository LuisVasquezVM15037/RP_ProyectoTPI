<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <!-- Card principal del producto -->
    <div class="card shadow producto-detalle-card border-0 rounded-4 overflow-hidden">
        <div class="row g-4 align-items-center">

            <!-- 📸 Imagen del producto -->
            <div class="col-md-5 d-flex justify-content-center align-items-center bg-light p-3">
                <img src="<?= BASE_URL . 'public/img/' . htmlspecialchars($producto['imagen_url']) ?>"
                    alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" class="img-fluid rounded-3"
                    style="max-height: 400px; object-fit: cover;">
            </div>

            <!-- 📝 Información del producto -->
            <div class="col-md-7 p-4 d-flex flex-column justify-content-between">

                <h2 class="fw-bold mb-3 text-success"><?= htmlspecialchars($producto['nombre_producto']) ?></h2>
                <p class="text-muted mb-1">
                    <i class="bi bi-folder-fill"></i>
                    <strong>Categoría:</strong>
                    <?= htmlspecialchars($producto['nombre_categoria'] ?? 'Sin categoría') ?>
                </p>
                <p class="text-muted mb-3">
                    <i class="bi bi-upc-scan"></i>
                    <strong>SKU:</strong> <?= htmlspecialchars($producto['sku']) ?>
                </p>

                <p class="lead descripcion">
                    <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
                </p>

                <div class="mt-4 producto-precio">
                    <h4 class="fw-bold text-success">
                        💲<?= number_format($producto['precio_unitario'], 2) ?>
                    </h4>
                    <p class="text-muted">
                        Stock disponible:
                        <span class="<?= $producto['stock'] > 0 ? 'text-success' : 'text-danger' ?>">
                            <?= (int) $producto['stock'] ?> unidades
                        </span>
                    </p>
                </div>

                <!-- 🛒 Formulario para agregar al carrito -->
                <form action="<?= BASE_URL ?>carrito/agregar/<?= $producto['id_producto'] ?>" method="POST"
                    class="mt-3">
                    <div class="d-flex align-items-center mb-3" style="gap: 10px;">
                        <label for="cantidad" class="fw-semibold">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" value="1" min="1"
                            max="<?= $producto['stock'] ?>" style="width: 90px;">
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($producto['stock'] > 0): ?>
                            <button type="submit" class="btn btn-success px-4 shadow-sm">
                                <i class="bi bi-cart-plus"></i> Agregar al carrito
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary px-4" disabled>
                                <i class="bi bi-x-circle"></i> Sin stock
                            </button>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>producto" class="btn btn-outline-success px-4">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ⭐ Reseñas de usuarios -->
    <div class="mt-5">
        <h4 class="fw-bold mb-3">🗣️ Opiniones de nuestros clientes</h4>

        <?php if (!empty($resenias)): ?>
            <?php foreach ($resenias as $r): ?>
                <div class="card mb-3 border-0 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0 fw-semibold text-success">
                            <?= htmlspecialchars($r['nombre_usuario']) . ' ' . htmlspecialchars($r['apellido_usuario']) ?>
                        </h6>
                        <small class="text-muted"><?= htmlspecialchars($r['fecha_resenia']) ?></small>
                    </div>
                    <div>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi <?= $i <= $r['calificacion'] ? 'bi-star-fill text-warning' : 'bi-star text-muted' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($r['comentario'])) ?></p>

                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $r['id_usuario']): ?>
                        <form action="<?= BASE_URL ?>resenia/eliminar/<?= $r['id_resenia'] ?>" method="POST" class="mt-2">
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-light text-muted border text-center shadow-sm">
                <i class="bi bi-chat-dots"></i>
                Aún no hay reseñas para este producto. ¡Sé el primero en opinar!
            </div>
        <?php endif; ?>

        <!-- Formulario para nueva reseña -->
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="card shadow-sm mt-4 p-4">
                <form action="<?= BASE_URL ?>resenia/guardar" method="POST">
                    <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                    <div class="mb-3">
                        <label for="calificacion" class="form-label fw-semibold">Calificación:</label>
                        <select name="calificacion" id="calificacion" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐ (Excelente)</option>
                            <option value="4">⭐⭐⭐⭐ (Muy bueno)</option>
                            <option value="3">⭐⭐⭐ (Bueno)</option>
                            <option value="2">⭐⭐ (Regular)</option>
                            <option value="1">⭐ (Malo)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label fw-semibold">Tu comentario:</label>
                        <textarea name="comentario" id="comentario" rows="3" class="form-control"
                            placeholder="Escribe tu opinión..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-send"></i> Enviar reseña
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-4 text-center">
                <i class="bi bi-person-circle"></i>
                <a href="<?= BASE_URL ?>usuario/login" class="text-success fw-semibold">Inicia sesión</a> para dejar una
                reseña.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>