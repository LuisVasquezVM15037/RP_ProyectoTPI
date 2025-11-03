<?php include __DIR__ . '/../layouts/header_admin.php'; ?>

<div class="d-flex justify-content-between align-items-center page-heading">
  <h5 class="mb-0">👥 Gestión de Usuarios</h5>
  <a href="<?= BASE_URL ?>usuario/crear" class="btn btn-success btn-sm">➕ Nuevo usuario</a>
</div>

<div class="card admin-card mt-3">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($usuarios)): foreach ($usuarios as $u): ?>
          <tr>
            <td><?= (int)$u['id_usuario'] ?></td>
            <td><?= htmlspecialchars(trim(($u['nombre_usuario'] ?? '').' '.($u['apellido_usuario'] ?? ''))) ?></td>
            <td><?= htmlspecialchars($u['email_usuario']) ?></td>
            <td>
              <span class="badge rounded-pill <?= ((int)$u['rol_usuario']===1?'text-bg-primary':'badge-soft') ?>">
                <?= ((int)$u['rol_usuario']===1?'Administrador':'Cliente') ?>
              </span>
            </td>
            <td>
              <a href="<?= BASE_URL ?>usuario/editar/<?= (int)$u['id_usuario'] ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i> Editar
              </a>
              <form action="<?= BASE_URL ?>usuario/eliminar/<?= (int)$u['id_usuario'] ?>" method="POST" style="display:inline-block"
                    onsubmit="return confirm('¿Eliminar este usuario?')">
                <button type="submit" class="btn btn-danger btn-sm">
                  <i class="bi bi-trash"></i> Eliminar
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios registrados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/footer_admin.php'; ?>
