<?php
$usuario = "Néstor";
$saldo = "$0.00";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página en Blanco</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body class="bg-background-light">
    <div class="min-vh-100 d-flex flex-column">
        <!-- Header con hojas reales -->
        <header class="header-jardineria">
            <div class="leaf-overlay">
                <!-- Hojas individuales con contorno completo -->
                <div class="leaf leaf-1"></div>
                <div class="leaf leaf-2"></div>
                <div class="leaf leaf-3"></div>
                <div class="leaf leaf-4"></div>
                <div class="leaf leaf-5"></div>
                <div class="leaf leaf-6"></div>
            </div>
            <div class="container-fluid px-4 py-3 d-flex justify-content-between align-items-center position-relative">
                <div class="fs-5 fw-bold text-white">INICIO</div>
                
                <!-- CONTENEDOR DERECHO - buscador + usuario -->
                <div class="header-right-section">
                    <!-- Campo de búsqueda -->
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Buscar productos...">
                    </div>
                    
                    <!-- Sección de usuario -->
                    <div class="user-section">
                        <span class="fs-5 fw-semibold text-white"><?php echo $saldo; ?></span>
                        <div class="separator"></div>
                        <span class="fs-5 fw-semibold text-white"><?php echo $usuario; ?></span>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow container-fluid px-4 py-5">
            <!-- Área en blanco -->
        </main>

        <!-- Footer con hojas -->
        <footer class="footer-jardineria text-white text-center p-4 position-relative">
            <div class="leaf-overlay-footer">
                <div class="leaf leaf-7"></div>
                <div class="leaf leaf-8"></div>
                <div class="leaf leaf-9"></div>
            </div>
            <p class="mb-0 position-relative">Información de la pagina</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
