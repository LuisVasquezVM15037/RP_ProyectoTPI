<?php
$conn = require_once('../config/conexion.php');

// INCLUIR TU auth_check ACTUAL (que funciona con usuario_id)
include('../Controladores/auth_check.php');

// Incluir modelos y controladores
include('../Modelos/ReseniaModel.php');
include('../Controladores/ReseniaController.php');

// Crear instancia del controlador
$reseniaController = new ReseniaController($conn);

// Obtener ID del producto desde la URL
$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_producto == 0) {
    header('Location: productos.php');
    exit;
}

// Procesar envío de reseña si existe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // AGREGAR RESEÑA
    if (isset($_POST['agregar_resenia'])) {
        if (isset($_SESSION['usuario_id'])) {
            $id_usuario = $_SESSION['usuario_id'];
            $comentario = trim($_POST['comentario']);
            $calificacion = intval($_POST['calificacion']);
            
            $resultado = $reseniaController->agregarResenia($id_usuario, $id_producto, $comentario, $calificacion);
            
            if ($resultado['success']) {
                $_SESSION['mensaje_exito'] = $resultado['message'];
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje_error'] = $resultado['message'];
                $_SESSION['tipo_mensaje'] = 'error';
            }
            
            header("Location: detalle_producto.php?id=$id_producto");
            exit;
        } else {
            $_SESSION['mensaje_error'] = "Debes iniciar sesión para dejar una reseña";
            $_SESSION['tipo_mensaje'] = 'warning';
            header("Location: detalle_producto.php?id=$id_producto");
            exit;
        }
    }
    
    // ELIMINAR RESEÑA
    if (isset($_POST['eliminar_resenia'])) {
        if (isset($_SESSION['usuario_id'])) {
            $id_resenia = intval($_POST['id_resenia']);
            $id_usuario = $_SESSION['usuario_id'];
            
            $resultado = $reseniaController->eliminarResenia($id_resenia, $id_usuario);
            
            if ($resultado['success']) {
                $_SESSION['mensaje_exito'] = $resultado['message'];
                $_SESSION['tipo_mensaje'] = 'success';
            } else {
                $_SESSION['mensaje_error'] = $resultado['message'];
                $_SESSION['tipo_mensaje'] = 'error';
            }
            
            header("Location: detalle_producto.php?id=$id_producto");
            exit;
        }
    }
}

// Obtener información del producto
$sql_producto = "SELECT p.*, c.nombre_categoria 
                 FROM producto p 
                 INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                 WHERE p.id_producto = ?";
$stmt = $conn->prepare($sql_producto);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: productos.php');
    exit;
}

$producto = $result->fetch_assoc();
$stmt->close();

// Obtener promedio de calificaciones y reseñas usando el controlador
$promedio_data = $reseniaController->obtenerPromedioCalificaciones($id_producto);
$promedio = $promedio_data['promedio'] ? round($promedio_data['promedio'], 1) : 0;
$total_resenias = $promedio_data['total'];

// Obtener reseñas recientes usando el controlador
$resenias = $reseniaController->obtenerReseniasProducto($id_producto, 5);

// Verificar si el usuario actual ya hizo una reseña para este producto
$usuario_ya_resenio = false;
$resenia_usuario = null;
if (isset($_SESSION['usuario_id'])) {
    $resenia_usuario = $reseniaController->obtenerReseniaUsuario($_SESSION['usuario_id'], $id_producto);
    $usuario_ya_resenio = ($resenia_usuario !== null && $resenia_usuario !== false);
}

// Cerrar conexión después de usarla
$conn->close();

// VERIFICAR SI EL USUARIO ESTÁ LOGUEADO - USANDO TUS VARIABLES DE SESIÓN
$usuario_logueado = isset($_SESSION['usuario_id']);
$usuario_nombre = "Invitado"; // Valor por defecto

if ($usuario_logueado) {
    // Usar usuario_nombre que tu auth_check establece
    if (isset($_SESSION['usuario_nombre']) && !empty($_SESSION['usuario_nombre'])) {
        $usuario_nombre = $_SESSION['usuario_nombre'];
    } else {
        $usuario_nombre = "Usuario";
    }
}

$saldo = "$0.00"; // O usa tu variable de sesión si existe

// Determinar saludo y opciones de usuario usando TU sistema de sesión
$saludo = $usuario_logueado ? "Hola, " . $usuario_nombre : "Bienvenido";
$opcion_login = !$usuario_logueado ? "LOGIN" : "LOGOUT";
$url_login = !$usuario_logueado ? "login.php" : "../Controladores/logout.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $producto['nombre_producto']; ?> - Jardinería</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../CSS/detalle_producto.css">
</head>
<body class="bg-background-light">
<div class="min-vh-100 d-flex flex-column">
    <!-- Header con hojas reales -->
    <header class="header-jardineria">
        <div class="leaf-overlay">
            <div class="leaf leaf-1"></div>
            <div class="leaf leaf-2"></div>
            <div class="leaf leaf-3"></div>
            <div class="leaf leaf-4"></div>
            <div class="leaf leaf-5"></div>
            <div class="leaf leaf-6"></div>
        </div>
        <div class="container-fluid px-4 py-3 d-flex justify-content-between align-items-center position-relative">
            <!-- Enlace INICIO que lleva al index -->
            <a href="../index.php" class="fs-5 fw-bold text-white inicio-link">INICIO</a>
            
            <!-- CONTENEDOR DERECHO - buscador + usuario -->
            <div class="header-right-section">
                <!-- Campo de búsqueda -->
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Buscar productos...">
                </div>
                
                <!-- Sección de usuario -->
                <div class="user-section">
                    <span class="fw-semibold text-white"><?php echo $saludo; ?></span>
                    <div class="separator"></div>
                    <span class="fw-semibold text-white"><?php echo $saldo; ?></span>
                    <div class="separator"></div>
                    <?php if(!isset($_SESSION['usuario_nombre'])): ?>
                        <a href="login.php" class="btn-login">
                            LOGIN
                        </a>
                    <?php else: ?>
                        <form method="POST" action="../Controladores/logout.php" style="display: inline;">
                            <button type="submit" class="btn-logout">
                                LOGOUT
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow-1 container-fluid px-4 py-5">
        <div class="producto-simple">
            <!-- Contenedor principal con tarjeta y sección lateral -->
            <div class="contenedor-principal">
                <!-- Tarjeta principal del producto -->
                <div class="tarjeta-producto">
                    <!-- Título del producto alineado a la izquierda -->
                    <h1 class="producto-titulo"><?php echo $producto['nombre_producto']; ?></h1>
                    
                    <div class="contenido-tarjeta">
                        <!-- Columna izquierda: Imagen, SKU y Stock -->
                        <div class="columna-imagen">
                            <div class="producto-imagen-contenedor">
                                <img src="<?php echo $producto['imagen_url']; ?>" 
                                     alt="<?php echo $producto['nombre_producto']; ?>" 
                                     class="producto-imagen">
                            </div>
                            
                            <!-- SKU y Stock debajo de la imagen -->
                            <div class="info-imagen">
                                <div class="sku-container">
                                    <p class="mb-0"><strong>SKU:</strong><br><?php echo $producto['sku']; ?></p>
                                </div>
                                <div class="stock-container">
                                    <i class="bi bi-box-seam icono-stock"></i>
                                    <p class="mb-0"><strong>Stock:</strong><br><?php echo $producto['stock']; ?> disponibles</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna derecha: Precio y Descripción -->
                        <div class="columna-info">
                            <p class="precio-producto">$<?php echo number_format($producto['precio_unitario'], 2); ?></p>
                            <p class="producto-descripcion"><?php echo $producto['descripcion']; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Sección lateral con características y botones -->
                <div class="seccion-lateral">
                    <!-- Características del producto -->
                    <div class="seccion-caracteristicas">
                        <h3 class="caracteristicas-titulo">Descripción del producto</h3>
                        <p class="caracteristicas-texto"><?php echo $producto['caracteristicas']; ?></p>
                    </div>
                    
                    <!-- Botones de acción -->
                    <div class="seccion-botones">
                        <div class="botones-accion">
                            <button class="boton-accion-grande boton-carrito">
                                <i class="bi bi-cart-plus"></i> AGREGAR AL CARRITO
                            </button>
                            <button class="boton-accion-grande boton-comprar">
                                COMPRAR AHORA
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Reseñas -->
            <div class="seccion-resenias">
                <h3 class="seccion-titulo">Deja tu reseña</h3>

                <!-- Rating y reseñas existentes -->
                <div class="rating-container">
                    <div class="rating-stars me-2">
                        <?php
                        $estrellas_llenas = floor($promedio);
                        $media_estrella = $promedio - $estrellas_llenas >= 0.5;
                        $estrellas_vacias = 5 - $estrellas_llenas - ($media_estrella ? 1 : 0);
                        
                        // Estrellas llenas
                        for ($i = 0; $i < $estrellas_llenas; $i++) {
                            echo '<i class="bi bi-star-fill estrella"></i>';
                        }
                        
                        // Media estrella
                        if ($media_estrella) {
                            echo '<i class="bi bi-star-half estrella"></i>';
                        }
                        
                        // Estrellas vacías
                        for ($i = 0; $i < $estrellas_vacias; $i++) {
                            echo '<i class="bi bi-star estrella-vacia"></i>';
                        }
                        ?>
                    </div>
                    <span class="text-muted">(<?php echo $promedio; ?> · <?php echo $total_resenias; ?> reseñas)</span>
                </div>

                <!-- Mensaje si el usuario ya hizo una reseña -->
                <?php if ($usuario_ya_resenio): ?>
                <div class="mensaje-ya-resenio">
                    <i class="bi bi-info-circle-fill text-warning"></i>
                    <strong>Ya has realizado una reseña para este producto.</strong>
                    <p class="mb-0 mt-1">Solo puedes dejar una reseña por producto.</p>
                </div>
                <?php endif; ?>

                <!-- Formulario de reseña -->
                <form method="POST" action="" id="formResenia">
                    <input type="hidden" name="agregar_resenia" value="1">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Calificación</label>
                        <div class="rating-stars mb-2" id="ratingStars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star estrella-vacia" data-rating="<?php echo $i; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="calificacion" id="calificacion" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tu reseña</label>
                        <textarea class="textarea-resenia" name="comentario" id="comentario" placeholder="Escribe aquí tu reseña..." <?php echo $usuario_ya_resenio ? 'disabled' : ''; ?>></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary-custom" <?php echo $usuario_ya_resenio ? 'disabled' : ''; ?>>
                        <i class="bi bi-send"></i> <?php echo $usuario_ya_resenio ? 'YA RESEÑADO' : 'ENVIAR RESEÑA'; ?>
                    </button>
                </form>

                <!-- Botón para mostrar reseñas -->
                <button class="btn btn-resenias" id="btnMostrarResenias">
                    <i class="bi bi-chat-text"></i> Mostrar Reseñas
                </button>

                <!-- Lista de reseñas (oculta inicialmente) -->
                <div id="listaResenias" style="display: none; margin-top: 20px;">
                    <h4 class="seccion-titulo">Reseñas de usuarios</h4>
                    
                    <?php if (count($resenias) > 0): ?>
                        <?php foreach ($resenias as $resenia): ?>
                        <div class="resenia-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="usuario-resenia">
                                        <?php echo htmlspecialchars($resenia['nombre_usuario'] . ' ' . $resenia['apellido_usuario']); ?>
                                        <?php if ($usuario_logueado && $resenia['id_usuario'] == $_SESSION['usuario_id']): ?>
                                            <span class="badge bg-primary ms-2">Tu reseña</span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="rating-stars mt-1">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $resenia['calificacion']) {
                                                echo '<i class="bi bi-star-fill estrella"></i>';
                                            } else {
                                                echo '<i class="bi bi-star estrella-vacia"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fecha-resenia d-block">
                                        <?php echo date('d/m/Y', strtotime($resenia['fecha_resenia'])); ?>
                                    </span>
                                    <?php if ($usuario_logueado && $resenia['id_usuario'] == $_SESSION['usuario_id']): ?>
                                        <form method="POST" action="" class="d-inline-block mt-1" onsubmit="return confirmarEliminacionSweet(event)">
                                            <input type="hidden" name="eliminar_resenia" value="1">
                                            <input type="hidden" name="id_resenia" value="<?php echo $resenia['id_resenia']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="mb-0"><?php echo htmlspecialchars($resenia['comentario']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle"></i> Aún no hay reseñas para este producto.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer con hojas -->
    <footer class="footer-jardineria text-white text-center p-4 position-relative">
        <div class="leaf-overlay-footer">
            <div class="leaf leaf-7"></div>
            <div class="leaf leaf-8"></div>
            <div class="leaf leaf-9"></div>
        </div>
        <p class="mb-0 position-relative">© 2025 Jardinería Verde - Todos los derechos reservados</p>
    </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Función para confirmar eliminación de reseña con SweetAlert
function confirmarEliminacionSweet(event) {
    event.preventDefault();
    const form = event.target;
    
    Swal.fire({
        title: '¿Eliminar reseña?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    
    return false;
}

$(document).ready(function() {
    // Mostrar mensajes con SweetAlert
    <?php if (isset($_SESSION['mensaje_exito'])): ?>
        Swal.fire({
            title: '¡Éxito!',
            text: '<?php echo $_SESSION['mensaje_exito']; ?>',
            icon: 'success',
            confirmButtonColor: '#67A981',
            confirmButtonText: 'Aceptar'
        });
        <?php unset($_SESSION['mensaje_exito']); ?>
        <?php unset($_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje_error'])): ?>
        Swal.fire({
            title: 'Error',
            text: '<?php echo $_SESSION['mensaje_error']; ?>',
            icon: 'error',
            confirmButtonColor: '#67A981',
            confirmButtonText: 'Aceptar'
        });
        <?php unset($_SESSION['mensaje_error']); ?>
        <?php unset($_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>
    
    // SISTEMA DE RATING SIMPLIFICADO Y MÁS ROBUSTO
    let selectedRating = 0;
    
    // Función para resaltar estrellas
    function updateStars(rating) {
        $('#ratingStars .bi-star').each(function() {
            const starValue = $(this).data('rating');
            if (starValue <= rating) {
                $(this).removeClass('bi-star estrella-vacia').addClass('bi-star-fill estrella');
            } else {
                $(this).removeClass('bi-star-fill estrella').addClass('bi-star estrella-vacia');
            }
        });
    }
    
    // Eventos para las estrellas
    $('#ratingStars .bi-star').hover(
        function() {
            const hoverRating = $(this).data('rating');
            updateStars(hoverRating);
        },
        function() {
            updateStars(selectedRating);
        }
    ).click(function() {
        selectedRating = $(this).data('rating');
        $('#calificacion').val(selectedRating);
        updateStars(selectedRating);
        
        // Confirmar visualmente la selección
        $(this).effect("highlight", {color: "#ffc107"}, 1000);
    });
    
    // Mostrar/ocultar reseñas
    $('#btnMostrarResenias').click(function() {
        $('#listaResenias').slideToggle();
        $(this).html($('#listaResenias').is(':visible') ? 
            '<i class="bi bi-eye-slash"></i> Ocultar Reseñas' : 
            '<i class="bi bi-chat-text"></i> Mostrar Reseñas'
        );
    });
    
    // Validación del formulario
    $('#formResenia').submit(function(e) {
        const calificacion = $('#calificacion').val();
        const comentario = $('#comentario').val().trim();
        
        // Verificar login
        <?php if (!$usuario_logueado): ?>
            e.preventDefault();
            Swal.fire({
                title: 'Iniciar sesión requerido',
                text: 'Debes iniciar sesión para dejar una reseña',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Iniciar sesión',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#67A981'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'login.php';
            });
            return false;
        <?php endif; ?>
        
        // Verificar reseña duplicada
        <?php if ($usuario_ya_resenio): ?>
            e.preventDefault();
            Swal.fire({
                title: 'Reseña ya realizada',
                text: 'Ya has reseñado este producto',
                icon: 'warning',
                confirmButtonColor: '#67A981'
            });
            return false;
        <?php endif; ?>
        
        // Validar calificación
        if (!calificacion || calificacion == 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Calificación requerida',
                text: 'Por favor selecciona una calificación',
                icon: 'warning',
                confirmButtonColor: '#67A981'
            });
            return false;
        }
        
        // Validar comentario
        if (!comentario) {
            e.preventDefault();
            Swal.fire({
                title: 'Comentario requerido',
                text: 'Por favor escribe tu reseña',
                icon: 'warning',
                confirmButtonColor: '#67A981'
            });
            return false;
        }
        
        // Confirmación final
        e.preventDefault();
        Swal.fire({
            title: '¿Enviar reseña?',
            text: 'Tu calificación: ' + calificacion + ' estrellas',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#67A981'
        }).then((result) => {
            if (result.isConfirmed) {
                $(this).unbind('submit').submit();
            }
        });
    });
});
</script>
</body>
</html>