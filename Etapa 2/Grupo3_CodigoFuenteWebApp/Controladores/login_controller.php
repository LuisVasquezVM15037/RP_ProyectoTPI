<?php
session_start();
include('../config/conexion.php');

class LoginController {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    public function login($email, $password) {
        // Prevenir inyección SQL (mejoraría con prepared statements)
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);
        
        $sql = "SELECT * FROM usuario WHERE email_usuario='$email' AND contrasenia_usuario='$password'";
        $result = mysqli_query($this->conn, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $usuario = mysqli_fetch_assoc($result);
            
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
            $_SESSION['usuario_apellido'] = $usuario['apellido_usuario'];
            $_SESSION['usuario_email'] = $usuario['email_usuario'];
            $_SESSION['usuario_rol'] = $usuario['rol_usuario'];
            $_SESSION['logged_in'] = true;
            
            // Redirigir según el rol
            if ($usuario['rol_usuario'] == 1) {
                header("Location: ../index.php"); // Vista de usuario normal
            } else if ($usuario['rol_usuario'] == 0) {
                header("Location: ../admin/dashboard.php"); // Vista de admin
            }
            exit();
            
        } else {
            return "❌ Usuario o contraseña incorrectos";
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: ../login.php");
        exit();
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: ../login.php");
            exit();
        }
    }
    
    public function isAdmin() {
        return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 0;
    }
    
    public function isUser() {
        return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 1;
    }
}
?>