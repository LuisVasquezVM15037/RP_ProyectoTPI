<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link rel="stylesheet" href="CSS/index.css"> 
</head>
<body>
    <header>
        <h1>INICIO</h1>

        <div class="search-bar">
            <input type="text" placeholder="Search">
        </div>

        <div class="user-options">
            <span>$0.00</span>
            <a href="Vistas/login.php">LOGIN</a>
        </div>
    </header>

    <section class="main-content">
        <div class="card" onclick="window.location.href='Vistas/Plantas.php'">
            <img src="Images/plantas/menu pla.jpg   " alt="Plantas">
            <h2>PLANTAS</h2>
        </div>

        <div class="card" onclick="window.location.href='Vistas/Palas.php'">
            <img src="Images/plantas/semillas.jpg" alt="Semillas">
            <h2>SEMILLAS</h2>
        </div>

        <div class="card" onclick="window.location.href='Vistas/Herramientas.php'">
            <img src="Images/plantas/herramientas.jpg" alt="Herramientas y materiales">
            <h2>HERRAMIENTAS Y<br>MATERIALES</h2>
        </div>

        <div class="card" onclick="window.location.href='Vistas/Regaderas.php'">
            <img src="Images/plantas/abono.jpg" alt="Abonos y fertilizantes">
            <h2>ABONOS Y<br>FERTILIZANTES</h2>
        </div>
    </section>

    <footer>
        Información de la página
    </footer>
</body>
</html>