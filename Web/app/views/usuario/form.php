<?php include __DIR__ . '/../layouts/header_admin.php'; ?>

<h5 class="mb-3"><?= isset($modo) && $modo === 'editar' ? 'Editar usuario' : 'Nuevo usuario' ?></h5>

<form action="<?= BASE_URL ?>usuario/<?= isset($modo) && $modo === 'editar' ? 'actualizar' : 'guardar' ?>" method="POST"
  class="row g-3 needs-validation" novalidate>

  <?php if (isset($usuario['id_usuario'])): ?>
    <input type="hidden" name="id_usuario" value="<?= (int) $usuario['id_usuario'] ?>">
  <?php endif; ?>

  <div class="col-md-6">
    <label class="form-label">Nombre</label>
    <input type="text" name="nombre_usuario" class="form-control" required
      value="<?= htmlspecialchars($usuario['nombre_usuario'] ?? '') ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label">Apellido</label>
    <input type="text" name="apellido_usuario" class="form-control" required
      value="<?= htmlspecialchars($usuario['apellido_usuario'] ?? '') ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email_usuario" class="form-control" required
      value="<?= htmlspecialchars($usuario['email_usuario'] ?? '') ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Contraseña</label>
    <input type="password" name="contrasenia_usuario" class="form-control" required autocomplete="new-password">
  </div>


  <div class="col-md-6">
    <label class="form-label">Rol</label>
    <select name="rol_usuario" class="form-select" required>
      <option value="">Seleccione...</option>
      <option value="1" <?= isset($usuario['rol_usuario']) && $usuario['rol_usuario'] == 1 ? 'selected' : '' ?>>
        Administrador</option>
      <option value="0" <?= isset($usuario['rol_usuario']) && $usuario['rol_usuario'] == 0 ? 'selected' : '' ?>>Cliente
      </option>

    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Teléfono</label>
    <input type="text" name="telefono_usuario" class="form-control"
      value="<?= htmlspecialchars($usuario['telefono_usuario'] ?? '') ?>" required>
  </div>

  <div class="col-12">
    <label class="form-label">Dirección</label>
    <textarea name="direccion_usuario" class="form-control" rows="2" required>
    <?= htmlspecialchars($usuario['direccion_usuario'] ?? '') ?>
  </textarea>
  </div>


  <div class="col-12">
    <button class="btn btn-primary">
      <?= isset($modo) && $modo === 'editar' ? 'Actualizar' : 'Guardar' ?>
    </button>
    <a href="<?= BASE_URL ?>admin/usuarios" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>

<?php include __DIR__ . '/../layouts/footer_admin.php'; ?>