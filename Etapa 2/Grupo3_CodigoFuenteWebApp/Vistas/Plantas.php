<?php
$conn = require_once('../config/conexion.php');

// Obtener productos de la categoría Plantas
$sql = "SELECT p.* FROM producto p 
INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
WHERE c.nombre_categoria = 'Plantas'";
$result = $conn->query($sql);
$plantas = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $plantas[] = [
            'nombre' => $row['nombre_producto'],
            'precio' => '$' . number_format($row['precio_unitario'], 2),
            'imagen' => $row['imagen_url'],
            'alt' => $row['descripcion'],
            'stock' => $row['stock']
        ];
    }
}

$conn->close();

$usuario = "Néstor";
$saldo = "$0.00";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantas - Jardinería</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    
    <style>
        
        .btn-primary-custom{
            background-color: #67A981 !important;
            border-color: #67A981 !important;
            color: #ffffff !important;
        }
        .btn-primary-custom:hover{
            background-color: #5a9574 !important;
            border-color: #5a9574 !important;
        }
        .bg-accent-light{ background-color: #67A981 !important; }
        .bg-card-light{ background-color: #FFFFFF !important; }
    </style>
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
                    <input type="text" class="search-input" id="searchInput" placeholder="Buscar plantas..." oninput="filterProducts()">
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
        <h2 class="display-4 fw-bold text-center mb-5 text-text-light">Plantas</h2>
        <div class="bg-card-light rounded shadow-lg p-4">
            <div class="row g-4">
                <?php foreach ($plantas as $planta): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center product-card">
                    <div class="card-content">
                        <div class="bg-white p-2 rounded shadow-sm product-image-wrapper">
                            <img alt="<?php echo $planta['alt']; ?>" 
                                 class="w-100" 
                                 style="height: 8rem; object-fit: cover;" 
                                 src="<?php echo $planta['imagen']; ?>"/>
                        </div>
                        <div class="product-info">
                            <h3 class="fw-semibold text-text-light fs-6 mb-0"><?php echo $planta['nombre']; ?></h3>
                            <p class="fw-bold text-muted mb-0"><?php echo $planta['precio']; ?></p>
                            <p class="text-muted small">Stock: <?php echo $planta['stock']; ?> unidades</p>
                        </div>
                        <div class="btn-container">
                            <button class="w-100 btn btn-primary-custom text-white small fw-bold py-2 px-2 rounded d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-cart-plus"></i>
                                <span>AGREGAR AL CARRITO</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-5">
                <button class="btn text-white fw-bold py-3 px-4 rounded fs-5" style="background-color: #FF9800;">
                    Ver más
                </button>
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
        <p class="mb-0 position-relative">Información de la pagina</p>
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function filterProducts() {
    const searchInput = document.getElementById('searchInput');
    const filter = searchInput.value.toLowerCase();
    const productCards = document.getElementsByClassName('product-card');

    for (let i = 0; i < productCards.length; i++) {
        const card = productCards[i];
        const title = card.querySelector('h3').textContent.toLowerCase();
        
        if (title.includes(filter)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    }
}
</script>
</body>
</html>