<?php
$conn = require_once('../config/conexion.php');

// Incluir verificación de autenticación
include('../Controladores/auth_check.php');

// Determinar saludo y opciones de usuario
$saludo = isset($_SESSION['usuario_nombre']) ? "Hola, " . $_SESSION['usuario_nombre'] : "Bienvenido";
$opcion_login = !isset($_SESSION['usuario_nombre']) ? "LOGIN" : "LOGOUT";
$url_login = !isset($_SESSION['usuario_nombre']) ? "login.php" : "../Controladores/logout.php";
$usuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : "Invitado";
$saldo = "$0.00";

// Verificar si es una búsqueda
$es_busqueda = isset($_POST['termino']) && !empty($_POST['termino']);
$termino_busqueda = $es_busqueda ? $_POST['termino'] : '';

// Configuración de paginación (solo si no es búsqueda)
$productos_por_pagina = 18;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($es_busqueda) {
    // BÚSQUEDA: Mostrar todos los resultados sin paginación
    $termino = $conn->real_escape_string($termino_busqueda);
    $sql = "SELECT p.* FROM producto p 
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
            WHERE c.nombre_categoria = 'Palas'
            AND (p.nombre_producto LIKE '%$termino%' OR p.sku LIKE '%$termino%')
            ORDER BY p.nombre_producto";
    
    $result = $conn->query($sql);
    $total_productos = $result->num_rows;
    $total_paginas = 1; // En búsqueda no usamos paginación
} else {
    // PAGINACIÓN NORMAL
    $offset = ($pagina_actual - 1) * $productos_por_pagina;

    // Obtener el total de productos de palas
    $sql_total = "SELECT COUNT(*) as total FROM producto p 
                  INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                  WHERE c.nombre_categoria = 'Palas'";
    $result_total = $conn->query($sql_total);
    $total_productos = $result_total->fetch_assoc()['total'];
    $total_paginas = ceil($total_productos / $productos_por_pagina);

    // Asegurar que la página actual esté dentro del rango válido
    if ($pagina_actual < 1) $pagina_actual = 1;
    if ($pagina_actual > $total_paginas) $pagina_actual = $total_paginas;

    // Obtener productos con paginación
    $sql = "SELECT p.* FROM producto p 
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
            WHERE c.nombre_categoria = 'Palas'
            ORDER BY p.id_producto
            LIMIT $offset, $productos_por_pagina";
    
    $result = $conn->query($sql);
}

$palas = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $palas[] = [
            'id' => $row['id_producto'],
            'nombre' => $row['nombre_producto'],
            'precio' => '$' . number_format($row['precio_unitario'], 2),
            'imagen' => $row['imagen_url'],
            'sku' => $row['sku']
        ];
    }
}

// Calcular rangos para la numeración
$inicio_rango = (($pagina_actual - 1) * $productos_por_pagina) + 1;
$fin_rango = min($pagina_actual * $productos_por_pagina, $total_productos);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Palas - Jardinería</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .btn-login {
            background-color: #67A981;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            font-size: 0.9rem;
        }
        
        .btn-login:hover {
            background-color: #5a9574;
            color: white;
            text-decoration: none;
        }
        
        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
        }
        
        .btn-logout:hover {
            background-color: #c82333;
            color: white;
        }
        
        .inicio-link {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        
        .inicio-link:hover {
            color: #e0e0e0;
            text-decoration: none;
        }
    </style>
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
                <form method="POST" action="" id="searchForm" class="d-inline">
                    <div class="search-container">
                        <input type="text" class="search-input" name="termino" id="searchInput" 
                               placeholder="Buscar en todas las palas..." 
                               value="<?php echo htmlspecialchars($termino_busqueda); ?>">
                    </div>
                </form>
                
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
        <h2 class="display-4 fw-bold text-center mb-5 text-text-light">Palas</h2>
        
        <!-- CONTENEDOR PRINCIPAL CON FONDO -->
        <div class="contenedor-principal">
            
            <?php if ($es_busqueda): ?>
                <!-- Resultado de búsqueda -->
                <div class="resultado-busqueda">
                    <h4 class="mb-3">
                        <i class="bi bi-search"></i> 
                        Resultados de búsqueda para "<?php echo htmlspecialchars($termino_busqueda); ?>"
                    </h4>
                    <p class="mb-0">Se encontraron <strong><?php echo $total_productos; ?></strong> palas</p>
                    <a href="?" class="btn btn-sm btn-primary-custom mt-2">
                        <i class="bi bi-arrow-left"></i> Volver a todas las palas
                    </a>
                </div>
            <?php endif; ?>

            <!-- Loading para búsqueda AJAX (oculto por ahora) -->
            <div class="search-loading" id="searchLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Buscando...</span>
                </div>
                <p class="mt-2">Buscando palas...</p>
            </div>

            <!-- Contenedor de productos -->
            <div class="row g-4" id="productos-container">
                <?php if (count($palas) > 0): ?>
                    <?php foreach ($palas as $pala): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center">
                        <a href="detalle_producto.php?id=<?php echo $pala['id']; ?>" class="card-link-custom">
                            <div class="product-card-custom">
                                <div class="product-image-wrapper">
                                    <img alt="<?php echo $pala['nombre']; ?>" 
                                         class="product-image" 
                                         src="<?php echo $pala['imagen']; ?>"/>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo $pala['nombre']; ?></h3>
                                    <p class="product-price"><?php echo $pala['precio']; ?></p>
                                    <p class="product-sku">SKU: <?php echo $pala['sku']; ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-warning">
                            <h4><i class="bi bi-exclamation-triangle"></i> No se encontraron palas</h4>
                            <?php if ($es_busqueda): ?>
                                <p>No hay palas que coincidan con "<?php echo htmlspecialchars($termino_busqueda); ?>"</p>
                                <a href="?" class="btn btn-primary-custom">Ver todas las palas</a>
                            <?php else: ?>
                                <p>No hay palas disponibles en este momento.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navegación de páginas (solo si no es búsqueda y hay más de una página) -->
            <?php if (!$es_busqueda && $total_paginas > 1): ?>
            <div class="paginacion-container mt-5">
                <div>
                    <?php if ($pagina_actual > 1): ?>
                    <a href="?pagina=<?php echo $pagina_actual - 1; ?>" class="btn btn-navegacion">
                        <i class="bi bi-arrow-left"></i> Anterior
                    </a>
                    <?php else: ?>
                    <button class="btn btn-navegacion" disabled>
                        <i class="bi bi-arrow-left"></i> Anterior
                    </button>
                    <?php endif; ?>
                </div>
                
                <div class="paginacion-info">
                    Página <?php echo $pagina_actual; ?> de <?php echo $total_paginas; ?>
                    <div class="paginacion-numeros">
                        Mostrando <?php echo $inicio_rango; ?>-<?php echo $fin_rango; ?> de <?php echo $total_productos; ?> palas
                    </div>
                </div>
                
                <div>
                    <?php if ($pagina_actual < $total_paginas): ?>
                    <a href="?pagina=<?php echo $pagina_actual + 1; ?>" class="btn btn-navegacion">
                        Siguiente <i class="bi bi-arrow-right"></i>
                    </a>
                    <?php else: ?>
                    <button class="btn btn-navegacion" disabled>
                        Siguiente <i class="bi bi-arrow-right"></i>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Enviar formulario automáticamente al escribir (con delay)
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 800); // 800ms de delay
});

// También enviar al presionar Enter
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('searchForm').submit();
    }
});
</script>
</body>
</html>