<?php
// Datos de las categorías principales
$categorias = [
    [
        'nombre' => 'PLANTAS',
        'imagen' => 'Images/plantas/menu_pla.jpg',
        'url' => 'Vistas/Plantas.php'
    ],
    [
        'nombre' => 'SEMILLAS',
        'imagen' => 'Images/plantas/semillas.jpg',
        'url' => 'Vistas/Semillas.php'
    ],
    [
        'nombre' => 'HERRAMIENTAS Y MATERIALES',
        'imagen' => 'Images/plantas/herramientas.jpg',
        'url' => 'Vistas/Herramientas.php'
    ],
    [
        'nombre' => 'ABONOS Y FERTILIZANTES',
        'imagen' => 'Images/plantas/abono.jpg',
        'url' => 'Vistas/abonos_fertilizantes.php'
    ]
];

// Incluir verificación de autenticación
include('./Controladores/auth_check.php');

// Determinar saludo y opciones de usuario
$saludo = isset($_SESSION['usuario_nombre']) ? "Hola, " . $_SESSION['usuario_nombre'] : "Bienvenido";
$saldo = "$0.00";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Jardinería</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        /* Header más compacto */
        .header-compact {
            padding: 0.5rem 0 !important;
        }
        
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
        
        .user-section span {
            font-size: 0.9rem !important;
        }
        
        /* Tarjetas compactas y del mismo tamaño */
        .card-categoria {
            border-radius: 12px 12px 0 0 !important;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 410px; /* Altura fija para todas las tarjetas */
            display: flex;
            flex-direction: column;
        }
        
        .card-categoria:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        
        .categoria-imagen-container {
            padding: 0 !important;
            height: 180px; /* Altura reducida para la imagen */
            overflow: hidden;
            border-radius: 12px 12px 0 0;
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
            flex-shrink: 0; /* Evita que se reduzca */
        }
        
        .card-categoria:hover .categoria-imagen-container {
            background: linear-gradient(135deg, #d4edda 0%, #b8e0c2 100%);
        }
        
        .categoria-imagen {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .card-categoria:hover .categoria-imagen {
            transform: scale(1.05);
        }
        
    .categoria-nombre-container {
        padding: 0.3rem 0.3rem; /* Muy reducido */
        background: var(--card-light);
        border-radius: 0 0 12px 12px;
        border-top: 1px solid #e9ecef;
        flex-grow: 0; /* Cambia a 0 para que no crezca */
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60px; /* Altura mínima reducida */
        height: auto; /* Altura automática */
    }
    
    .categoria-nombre {
        color: #333333;
        font-size: 0.9rem; /* Texto un poco más pequeño */
        font-weight: 600;
        margin: 0;
        text-align: center;
        line-height: 1.1; /* Menor interlineado */
        padding: 0;
    }
        /* Enlace INICIO */
        .inicio-link {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        
        .inicio-link:hover {
            color: #e0e0e0;
            text-decoration: none;
        }
        
        /* Grid responsivo mejorado */
        .row.g-4 {
            margin: 0 -12px;
        }
        
        .col-md-6, .col-lg-4, .col-xl-3 {
            padding: 12px;
        }
        
        /* Asegurar que todas las tarjetas tengan la misma altura */
        .card-link {
            display: block;
            height: 100%;
        }
    </style>
</head>
<body class="bg-background-light">
<div class="min-vh-100 d-flex flex-column">
    <!-- Header con hojas reales - MÁS COMPACTO -->
    <header class="header-jardineria header-compact">
        <div class="leaf-overlay">
            <!-- Hojas individuales con contorno completo -->
            <div class="leaf leaf-1"></div>
            <div class="leaf leaf-2"></div>
            <div class="leaf leaf-3"></div>
            <div class="leaf leaf-4"></div>
            <div class="leaf leaf-5"></div>
            <div class="leaf leaf-6"></div>
        </div>
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center position-relative">
            <!-- Enlace INICIO que lleva al index -->
            <a href="index.php" class="fs-5 fw-bold text-white inicio-link">INICIO</a>
            
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
                        <a href="Vistas/login.php" class="btn-login">
                            LOGIN
                        </a>
                    <?php else: ?>
                        <form method="POST" action="./Controladores/logout.php" style="display: inline;">
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
    <main class="flex-grow-1 container-fluid px-4 py-4">
        <!-- Título Principal -->
        <div class="text-center mb-4">
            <h1 class="display-5 fw-bold text-text-light mb-3">Nuestras Categorías</h1>
            <p class="lead text-muted">Descubre todo lo que necesitas para tu jardín</p>
        </div>

        <!-- Grid de Categorías -->
        <div class="row g-3 justify-content-center">
            <?php foreach ($categorias as $index => $categoria): ?>
            <div class="col-md-6 col-lg-4 col-xl-3 d-flex">
                <a href="<?php echo $categoria['url']; ?>" class="card-link text-decoration-none w-100">
                    <div class="card-categoria">
                        <div class="categoria-imagen-container">
                            <img src="<?php echo $categoria['imagen']; ?>" 
                                 alt="<?php echo $categoria['nombre']; ?>" 
                                 class="categoria-imagen"
                                 onerror="this.src='https://via.placeholder.com/300x180/4CAF50/FFFFFF?text=<?php echo urlencode($categoria['nombre']); ?>'">
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>