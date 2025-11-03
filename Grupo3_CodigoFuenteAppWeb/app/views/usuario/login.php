<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-3">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
                        <h3 class="mb-1">Iniciar Sesión</h3>
                        <p class="text-muted mb-3">Ingrese sus credenciales para acceder.</p>

                        <!-- Mensaje flash -->
                        <?php if (!empty($_SESSION['flash'])): ?>
                            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'] ?? 'info') ?>">
                                <?= htmlspecialchars($_SESSION['flash']['message'] ?? '') ?>
                            </div>
                            <?php unset($_SESSION['flash']); ?>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>usuario/login" method="POST" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="email_usuario" class="form-label">Correo electrónico</label>
                                <input id="email_usuario" name="email_usuario" type="email" class="form-control" required autocomplete="new-password">
                                <div class="invalid-feedback">Ingrese un correo válido.</div>
                            </div>

                            <div class="mb-3">
                                <label for="contrasenia_usuario" class="form-label">Contraseña</label>
                                <input id="contrasenia_usuario" name="contrasenia_usuario" type="password" class="form-control" required autocomplete="new-password">
                                <div class="invalid-feedback">Ingrese su contraseña.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                                <a href="<?= BASE_URL ?>usuario/registro" class="btn btn-outline-secondary">Registrarse</a>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6 d-none d-md-block">
                        <img src="<?= BASE_URL ?>public/img/planta.jpeg" alt="login image" class="img-fluid h-100 w-100" style="object-fit:cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false)
  });
})();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
