<?php
// Controladores/logout.php
session_start();
session_destroy();

// Redirigir al index (página principal) después de cerrar sesión
header("Location: ../index.php");
exit();
?>