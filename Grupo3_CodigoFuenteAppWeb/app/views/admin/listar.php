<?php include __DIR__ . '/../layouts/header_admin.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Gestión de productos</h5>
    <a href="<?= BASE_URL ?>producto/crear" class="btn btn-sm btn-primary">+ Nuevo</a>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-4">
        <label class="form-label small">Categoría</label>
        <select id="filtroCategoria" class="form-select">
            <option value="">— Todas —</option>
            <?php if (!empty($categorias)):
                foreach ($categorias as $cat): ?>
                    <option value="<?= (int) $cat['id_categoria'] ?>" <?= (isset($categoriaSeleccionada) && $categoriaSeleccionada == $cat['id_categoria']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre_categoria']) ?>
                    </option>
                <?php endforeach; endif; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label small">Buscar</label>
        <input id="buscadorProductos" type="search" class="form-control" placeholder="Nombre o SKU">
    </div>
    <div class="col-md-4 d-flex align-items-end justify-content-end">
        <button id="btnLimpiar" class="btn btn-outline-secondary">Limpiar</button>
    </div>
</div>

<div class="table-responsive">
    <table id="tablaProductos" class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>SKU</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Proveedor</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($productos)):
                $i = 1;
                foreach ($productos as $p): ?>
                    <tr data-categoria="<?= (int) $p['id_categoria'] ?>">
                        <td><?= $i++ ?></td>
                        <td style="width:72px">
                            <img class="img-fluid" style="max-height:56px"
                                src="<?= BASE_URL . 'public/img/' . htmlspecialchars($p['imagen_url']) ?>"
                                alt="<?= htmlspecialchars($p['nombre_producto']) ?>">
                        </td>
                        <td class="nombre"><?= htmlspecialchars($p['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($p['nombre_categoria'] ?? 'Sin categoría') ?></td>
                        <td class="sku"><?= htmlspecialchars($p['sku']) ?></td>
                        <td>$<?= number_format($p['precio_unitario'], 2) ?></td>
                        <td><?= (int) $p['stock'] ?></td>
                        <td><?= htmlspecialchars($p['proveedor']) ?></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>producto/editar/<?= $p['id_producto'] ?>"
                                class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="<?= BASE_URL ?>producto/eliminar/<?= $p['id_producto'] ?>" method="POST"
                                style="display:inline"
                                onsubmit="return confirm('¿Eliminar <?= htmlspecialchars($p['nombre_producto']) ?>?');">
                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">No hay productos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const filtro = document.getElementById('filtroCategoria');
        const buscador = document.getElementById('buscadorProductos');
        const limpiar = document.getElementById('btnLimpiar');
        const filas = Array.from(document.querySelectorAll('#tablaProductos tbody tr'));

        const filtrar = () => {
            const cat = (filtro.value || '').trim();
            const term = (buscador.value || '').trim().toLowerCase();

            filas.forEach(tr => {
                const matchCat = !cat || (tr.getAttribute('data-categoria') === cat);
                const nombre = tr.querySelector('.nombre')?.textContent.toLowerCase() || '';
                const sku = tr.querySelector('.sku')?.textContent.toLowerCase() || '';
                const matchTerm = !term || nombre.includes(term) || sku.includes(term);
                tr.style.display = (matchCat && matchTerm) ? '' : 'none';
            });
        };

        filtro.addEventListener('change', filtrar);
        buscador.addEventListener('input', filtrar);
        limpiar.addEventListener('click', () => { filtro.value = ''; buscador.value = ''; filtrar(); });
        filtrar();
    });
</script>

<?php include __DIR__ . '/../layouts/footer_admin.php'; ?>