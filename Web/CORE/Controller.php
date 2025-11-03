<?php
// Clase Padre para los controladores. Proporciona métodos para cargar modelos y vistas. Su propósito es facilitar la comunicación entre los modelos y vistas.
class Controller {

    // Método para cargar un modelo
    public function model($model) {
        // Ruta del archivo del modelo
        $modelPath = __DIR__ . '/../app/models/' . $model . '.php';
        // Verificar si el archivo del modelo existe
        if(file_exists($modelPath)) {
            // Incluir el archivo del modelo y retornar una instancia
            require_once $modelPath;
            return new $model();
        } else {
            // Si no se encuentra el modelo, mostrar un error
            die("Error: No se encontró el modelo: " . $model);
        }
    }

    // Método para cargar una vista
    public function view($view, $data = []) {
        // Ruta del archivo de la vista
        $viewPath = __DIR__ . '/../app/views/' . $view . '.php';
        // Verificar si el archivo de la vista existe
        if(file_exists($viewPath)) {
            extract($data); //extrae las variables del array $data para usarlas en la vista
            require_once $viewPath; //incluir la vista
        } else {
            // Si no se encuentra la vista, mostrar un error
            die("Error: No se encontró la vista: " . $view);
        }
    }
}
?>