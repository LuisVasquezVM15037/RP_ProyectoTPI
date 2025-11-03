<?php
// Clase Padre para los modelos. Proporciona la conexión a la base de datos para que los modelos puedan interactuar con ella.
class Model {
    //Variable que almacena la conexion
    protected $db;
    //Constructor que inicializa la conexion a la BD
    public function __construct() {
        // Incluir el archivo de configuración de la base de datos
        require_once __DIR__ . '/../config/database.php'; //ruta del archivo database.php
        $database = new Database(); //crear una instancia de Database
        $this->db = $database->connect(); //establecer la conexion y asignarla a la variable $db
    }
}
?>