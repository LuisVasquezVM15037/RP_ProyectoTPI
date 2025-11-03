<?php
require_once __DIR__ . '/../../core/Model.php';

class Categoria extends Model {
    private $conn;
    private $tabla = 'categoria';

    public $id_categoria;
    public $nombre_categoria;

    // Obtener todas las categorías
    public function obtenerTodos() {
        $query = "SELECT * FROM " . $this->tabla . " ORDER BY nombre_categoria";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 🔸 Asignar imágenes según el nombre
        foreach ($categorias as &$cat) {
            $nombre = strtolower(trim($cat['nombre_categoria']));

            switch ($nombre) {
                case 'plantas':
                    $cat['imagen'] = BASE_URL . 'public/img/menu_pla.jpg';
                    break;
                case 'semillas':
                    $cat['imagen'] = BASE_URL . 'public/img/semillas.jpg';
                    break;
                case 'abonos y fertilizantes':
                    $cat['imagen'] = BASE_URL . 'public/img/abono.jpg';
                    break;
                case 'herramientas':
                    $cat['imagen'] = BASE_URL . 'public/img/herramientas.jpg';
                    break;
                default:
                    $cat['imagen'] = BASE_URL . 'public/img/default_categoria.png';
                    break;
            }
        }

        return $categorias;
    }

    // Obtener categoría por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_categoria = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Obtener categoría con conteo de productos
    public function obtenerConProductos() {
        $query = "SELECT c.*, COUNT(p.id_producto) as total_productos 
                  FROM " . $this->tabla . " c
                  LEFT JOIN producto p ON c.id_categoria = p.id_categoria
                  GROUP BY c.id_categoria
                  ORDER BY c.nombre_categoria";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Crear categoría
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " (nombre_categoria) VALUES (:nombre)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre_categoria);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Actualizar categoría
    public function actualizar() {
        $query = "UPDATE " . $this->tabla . " 
                  SET nombre_categoria = :nombre 
                  WHERE id_categoria = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $this->id_categoria);
        $stmt->bindParam(':nombre', $this->nombre_categoria);
        
        return $stmt->execute();
    }

    // Eliminar categoría
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->tabla . " WHERE id_categoria = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>