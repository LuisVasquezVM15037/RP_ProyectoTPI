<?php
// controllers/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si existe la conexión, si no, crearla
if (!isset($conn)) {
    // Ajusta la ruta según tu estructura de carpetas
    $ruta_conexion = __DIR__ . '/../config/conexion.php';
    
    if (file_exists($ruta_conexion)) {
        include($ruta_conexion);
    } else {
        // Si no encuentra el archivo, mostrar error controlado
        error_log("No se pudo encontrar el archivo de conexión: " . $ruta_conexion);
        // Podemos continuar sin conexión para desarrollo
    }
}

// Verificar si el usuario está logueado
if (isset($_SESSION['usuario_id']) && isset($conn)) {
    try {
        // CORREGIR: Usar el nombre correcto de la tabla (probablemente 'usuario' en singular)
        $stmt = $conn->prepare("SELECT nombre_usuario FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $_SESSION['usuario_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
        }
        
        $stmt->close();
    } catch(Exception $e) {
        error_log("Error al obtener datos del usuario: " . $e->getMessage());
    }
}
?>