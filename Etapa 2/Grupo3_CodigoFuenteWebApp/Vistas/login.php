<?php
include('../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email_usuario'];
    $password = $_POST['contrasenia_usuario'];

    $sql = "SELECT * FROM usuario WHERE email_usuario='$email' AND contrasenia_usuario='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        echo "<script>alert('✅ Inicio de sesión correcto');</script>";
        // header("Location: ../index.php");
    } else {
        echo "<script>alert('❌ Usuario o contraseña incorrectos');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
<link rel="stylesheet" href="/proyecto_grupo3/Etapa%202/Grupo3_CodigoFuenteWebApp/CSS/login.css"></head>
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
            <img src="../Images/plantas.png" alt="Plantas" width="350px">
        </div>
    </div>

    <div class="footer">
        Información de la página
    </div>
</body>
</html>