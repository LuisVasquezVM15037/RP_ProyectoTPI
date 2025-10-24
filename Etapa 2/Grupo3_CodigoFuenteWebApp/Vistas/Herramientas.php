<?php
// Datos de las categorías con imágenes locales
$categorias = [
    [
        'nombre' => 'Palas',
        'imagen' => '../images/pala.png',
        'url' => 'Palas.php'
    ],
    [
        'nombre' => 'Regaderas',
        'imagen' => '../images/regadera.png',
        'url' => 'Regaderas.php'
    ],
    [
        'nombre' => 'Guantes',
        'imagen' => '../images/Guantes.png',
        'url' => 'Guantes.php'
    ]
];

// Incluir verificación de autenticación
include('../Controladores/auth_check.php');

// Determinar saludo y opciones de usuario
$saludo = isset($_SESSION['usuario_nombre']) ? "Hola, " . $_SESSION['usuario_nombre'] : "Bienvenido";
$saldo = "$0.00";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herramientas y Materiales - Jardinería</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
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
    <!-- Header con hojas reales - Mismo grosor que index -->
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

    <!-- Contenido Principal -->
    <main class="flex-grow-1 container-fluid px-4 py-5">
        <!-- Título Principal -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-text-light mb-3">Herramientas y materiales</h1>
            <p class="lead text-muted">Encuentra todo lo necesario para el cuidado de tu jardín</p>
        </div>

        <!-- Grid de Categorías -->
        <div class="row g-4 justify-content-center">
            <?php foreach ($categorias as $index => $categoria): ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="<?php echo $categoria['url']; ?>" class="card-link text-decoration-none">
                    <div class="card-categoria h-100 text-center">
                        <div class="categoria-imagen-container">
                            <img src="<?php echo $categoria['imagen']; ?>" 
                                 alt="<?php echo $categoria['nombre']; ?>" 
                                 class="categoria-imagen"
                                 onerror="this.src='https://via.placeholder.com/100x100/4CAF50/FFFFFF?text=<?php echo urlencode($categoria['nombre']); ?>'">
                        </div>
                        <div class="categoria-nombre-container">
                            <h3 class="categoria-nombre"><?php echo $categoria['nombre']; ?></h3>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer con hojas - Mismo diseño que index -->
    <footer class="footer-jardineria text-white text-center p-4 position-relative">
        <div class="leaf-overlay-footer">
            <div class="leaf leaf-7"></div>
            <div class="leaf leaf-8"></div>
            <div class="leaf leaf-9"></div>
        </div>
        <p class="mb-0 position-relative">© 2025 Jardinería Verde - Todos los derechos reservados</p>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>