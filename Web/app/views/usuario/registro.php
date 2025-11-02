<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-1">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="row g-0">

                    <!-- 📝 FORMULARIO DE REGISTRO -->
                    <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
                        <h3 class="mb-1">Crear Cuenta</h3>
                        <p class="text-muted mb-3">Completa los campos para registrarte.</p>

                        <!-- Mensaje flash -->
                        <?php if (!empty($_SESSION['flash'])): ?>
                            <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'] ?? 'info') ?>">
                                <?= htmlspecialchars($_SESSION['flash']['message'] ?? '') ?>
                            </div>
                            <?php unset($_SESSION['flash']); ?>
                        <?php endif; ?>

                        <!-- Mensaje de error directo -->
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                ❌ <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= BASE_URL ?>usuario/procesarRegistro" class="needs-validation"
                            novalidate>
                            <div class="mb-3">
                                <label for="nombre" class="form-label" >Nombre</label>
                                <input id="nombre" type="text" name="nombre" class="form-control"
                                    placeholder="Tu nombre" required>
                                <div class="invalid-feedback">Ingrese su nombre.</div>
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input id="apellido" type="text" name="apellido" class="form-control"
                                    placeholder="Tu apellido" required>
                                <div class="invalid-feedback">Ingrese su apellido.</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label ">Correo electrónico</label>
                                <input id="email" type="email" name="email" class="form-control "
                                    placeholder="tucorreo@ejemplo.com" required autocomplete="new-password">
                                <div class="invalid-feedback ">Ingrese un correo válido.</div>
                            </div>

                            <div class="mb-3">
                                <label for="contrasenia" class="form-label">Contraseña</label>
                                <input id="contrasenia" type="password" name="contrasenia" class="form-control"
                                    placeholder="Mínimo 6 caracteres" minlength="6" required autocomplete="new-password">
                                <div class="invalid-feedback">Ingrese una contraseña válida (mínimo 6 caracteres).</div>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea id="direccion" name="direccion" class="form-control" rows="2"
                                    placeholder="Tu dirección completa"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input id="telefono" type="number" name="telefono" class="form-control"
                                    placeholder="0000-0000">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                            </div>
                        </form>

                        <p class="text-center mt-3">
                            ¿Ya tienes cuenta?
                            <a href="<?= BASE_URL ?>usuario/login" class="fw-bold text-decoration-none">Inicia sesión
                                aquí</a>
                        </p>
                    </div>

                    <!-- 🌿 IMAGEN DECORATIVA -->
                    <div class="col-md-6 d-none d-md-block">
                        <img src="<?= BASE_URL ?>public/img/planta.jpeg" alt="registro image"
                            class="img-fluid h-100 w-100" style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Script de validación Bootstrap -->
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
            }, false);
        });
    })();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>