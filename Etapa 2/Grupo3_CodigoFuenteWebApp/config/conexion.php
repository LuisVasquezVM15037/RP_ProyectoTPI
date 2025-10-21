<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "Grupo3_BDAppWeb"; 

$conn = mysqli_connect($host, $usuario, $clave, $base_datos);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>