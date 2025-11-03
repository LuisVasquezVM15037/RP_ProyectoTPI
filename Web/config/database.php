<?php
// Clase para manejar la conexión a la base de datos utilizando PDO.
class Database {
    // Atributos de configuración de la base de datos
    private $host = 'localhost'; // Dirección del servidor de la base de datos
    private $db = 'grupo3_bdappweb'; // Nombre de la base de datos
    private $user = 'root'; // Usuario de la base de datos
    private $pass = ''; // Contraseña de la base de datos
    private $conn;// Variable para almacenar la conexión PDO

    // Método para establecer la conexión a la base de datos
    public function connect() {
        $this->conn = null;// Inicializar la conexión como null
        //validar la conexion
        try {
            // Crear una nueva conexión PDO
            $this->conn = new PDO(
                //DSN: Data Source Name
                'mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=utf8mb4', 
                $this->user, 
                $this->pass
            ); // Establecer atributos de la conexión
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Manejar errores de conexión y enviar mensaje de notificación
            echo 'Error de conexión: ' . $e->getMessage();
        }
        // Devuelve la conexión establecida
        return $this->conn;
    }
}
?>