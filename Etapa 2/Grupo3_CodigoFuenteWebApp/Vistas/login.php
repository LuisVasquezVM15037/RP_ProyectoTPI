<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email_usuario'];
    $password = $_POST['contrasenia_usuario'];

    $sql = "SELECT * FROM usuario WHERE email_usuario='$email' AND contrasenia_usuario='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        echo "Inicio de sesión correcto";
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="text" name="email_usuario" required><br><br>
        <label>Contraseña:</label><br>
        <input type="password" name="contrasenia_usuario" required><br><br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>