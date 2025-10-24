<?php
include('../config/conexion.php');
include('../Controladores/login_controller.php'); // Incluir el controlador

// Crear instancia del controlador
$loginController = new LoginController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email_usuario'];
    $password = $_POST['contrasenia_usuario'];
    
    // Usar el controlador para hacer login
    $resultado = $loginController->login($email, $password);
    
    if ($resultado !== true) {
        echo "<script>alert('$resultado');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="header">
        <div>INICIO</div>
        <div>REGISTRO</div>
    </div>

    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <form method="POST" action="">
                <label>Email:</label>
                <input type="text" name="email_usuario" required>

                <label>Contraseña:</label>
                <input type="password" name="contrasenia_usuario" required>

                <button type="submit">Ingresar</button>
            </form>
        </div>

        <div>
            <img src="../Images/plantas/Planta.JPEG" alt="Plantas" width="350px">
        </div>
    </div>

    <div class="footer">
        Información de la página
    </div>
</body>
</html>