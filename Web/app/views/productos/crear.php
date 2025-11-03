<?php include __DIR__ . '/../layouts/header_admin.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= isset($modo) && $modo === 'editar' ? 'Editar producto' : 'Nuevo producto' ?></h5>
    <a href="<?= BASE_URL ?>admin" class="btn btn-sm btn-outline-secondary">Volver</a>
</div>

<form 
    action="<?= BASE_URL ?>producto/<?= isset($modo) && $modo === 'editar' ? 'actualizar' : 'guardar' ?>" 
    method="POST" 
    enctype="multipart/form-data" 
    class="row g-3 needs-validation" 
    novalidate>

    <?php if (isset($producto['id_producto'])): ?>
        <input type="hidden" name="id_producto" value="<?= htmlspecialchars($producto['id_producto']) ?>">
    <?php endif; ?>

    <div class="col-md-4">
        <label class="form-label">Categoría</label>
        <select name="id_categoria" class="form-select" required>
            <option value="">Selecciona...</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= (int) $c['id_categoria'] ?>" 
                    <?= isset($producto['id_categoria']) && $producto['id_categoria'] == $c['id_categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="invalid-feedback">Seleccione una categoría.</div>
    </div>

    <div class="col-md-8">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre_producto" 
               class="form-control" maxlength="200" required
               value="<?= htmlspecialchars($producto['nombre_producto'] ?? '') ?>">
        <div class="invalid-feedback">Ingrese el nombre.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" maxlength="100"
               value="<?= htmlspecialchars($producto['sku'] ?? '') ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">Precio unitario</label>
        <input type="number" name="precio_unitario" step="0.01" min="0" class="form-control" required
               value="<?= htmlspecialchars($producto['precio_unitario'] ?? '') ?>">
        <div class="invalid-feedback">Ingrese un precio válido.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" min="0" class="form-control" required
               value="<?= htmlspecialchars($producto['stock'] ?? 0) ?>">
        <div class="invalid-feedback">Ingrese stock válido.</div>
    </div>

    <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" rows="3" class="form-control"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
        <?php if (!empty($producto['imagen_url'])): ?>
            <div class="mt-2">
                <img src="<?= BASE_URL ?>public/img/<?= htmlspecialchars($producto['imagen_url']) ?>" 
                     alt="Imagen actual" style="width: 100px; height: auto; border-radius: 4px;">
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Proveedor</label>
        <input type="text" name="proveedor" class="form-control" maxlength="150"
               value="<?= htmlspecialchars($producto['proveedor'] ?? '') ?>">
    </div>

    <div class="col-12">
        <button class="btn btn-primary">
            <?= isset($modo) && $modo === 'editar' ? 'Actualizar' : 'Guardar' ?>
        </button>
    </div>
</form>

<script>
(() => {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(f => {
    f.addEventListener('submit', e => {
      if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
      f.classList.add('was-validated');
    }, false);
  });
})();
</script>

<?php include __DIR__ . '/../layouts/footer_admin.php'; ?>
