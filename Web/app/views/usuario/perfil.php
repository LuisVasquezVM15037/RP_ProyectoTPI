<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Mi Perfil</h1>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
        
        <div class="form-container" style="margin: 0;">
            <h2>Información Personal</h2>
            
            <div class="form-group">
                <label>Nombre:</label>
                <p style="padding: 12px; background: #f5f5f5; border-radius: 5px;">
                    <?php echo htmlspecialchars($usuario['nombre_usuario'] . ' ' . $usuario['apellido_usuario']); ?>
                </p>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <p style="padding: 12px; background: #f5f5f5; border-radius: 5px;">
                    <?php echo htmlspecialchars($usuario['email_usuario']); ?>
                </p>
            </div>

            <div class="form-group">
                <label>Dirección:</label>
                <p style="padding: 12px; background: #f5f5f5; border-radius: 5px;">
                    <?php echo htmlspecialchars($usuario['direccion_usuario'] ?? 'No registrada'); ?>
                </p>
            </div>

            <div class="form-group">
                <label>Teléfono:</label>
                <p style="padding: 12px; background: #f5f5f5; border-radius: 5px;">
                    <?php echo htmlspecialchars($usuario['telefono_usuario'] ?? 'No registrado'); ?>
                </p>
            </div>

            <div class="form-group">
                <label>Rol:</label>
                <p style="padding: 12px; background: #f5f5f5; border-radius: 5px;">
                    <?php echo $usuario['rol_usuario'] == 1 ? '👑 Administrador' : '👤 Cliente'; ?>
                </p>
            </div>
        </div>

        <div>
            <div class="form-container" style="margin: 0 0 20px 0;">
                <h3>Acciones Rápidas</h3>
                <a href="<?php echo BASE_URL; ?>pedido/misPedidos" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                    📦 Ver Mis Pedidos
                </a>
                <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary" style="width: 100%;">
                    🛍️ Seguir Comprando
                </a>
            </div>

            <?php if($usuario['rol_usuario'] == 1): ?>
            <div class="form-container" style="margin: 0; background: #fff3cd;">
                <h3>Panel de Administrador</h3>
                <a href="<?php echo BASE_URL; ?>producto/crear" class="btn btn-success" style="width: 100%; margin-bottom: 10px;">
                    ➕ Crear Producto
                </a>
                <a href="<?php echo BASE_URL; ?>categoria/crear" class="btn btn-success" style="width: 100%;">
                    ➕ Crear Categoría
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>