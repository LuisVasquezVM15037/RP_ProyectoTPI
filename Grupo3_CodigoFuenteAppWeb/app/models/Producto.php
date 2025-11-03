<?php
require_once __DIR__ . '/../../core/Model.php';
class Producto extends Model
{
    //nombre de la tabla igual que en la base de datos para el Query
    private $tabla = 'producto';

    //Atributos de la clase
    public $id_producto;
    public $id_categoria;
    public $nombre_producto;
    public $sku;
    public $descripcion;
    public $precio_unitario;
    public $stock;
    public $imagen_url;
    public $proveedor;

    //***************************************************************************
    // En el modelo creamos todos los Querys necesarios para comunicarse a la BD        
    //****************************************************************************

    // Crear nuevo producto
    public function crear()
    {
        try {
            $sql = "INSERT INTO {$this->tabla} (
                    id_categoria,
                    nombre_producto,
                    sku,
                    descripcion,
                    precio_unitario,
                    stock,
                    imagen_url,
                    proveedor
                ) VALUES (
                    :id_categoria,
                    :nombre_producto,
                    :sku,
                    :descripcion,
                    :precio_unitario,
                    :stock,
                    :imagen_url,
                    :proveedor
                )";

            $stmt = $this->db->prepare($sql);

            // Vinculamos los valores de los atributos del modelo
            $stmt->bindValue(':id_categoria', $this->id_categoria, PDO::PARAM_INT);
            $stmt->bindValue(':nombre_producto', $this->nombre_producto, PDO::PARAM_STR);
            $stmt->bindValue(':sku', $this->sku, PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $stmt->bindValue(':precio_unitario', $this->precio_unitario);
            $stmt->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $stmt->bindValue(':imagen_url', $this->imagen_url, PDO::PARAM_STR);
            $stmt->bindValue(':proveedor', $this->proveedor, PDO::PARAM_STR);

            // Ejecuta la sentencia SQL
            $stmt->execute();

            // Retorna true si se insertó correctamente
            return true;

        } catch (PDOException $e) {
            // Si hay error, lo puedes registrar o mostrar de forma controlada
            error_log("Error al crear producto: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar producto existente
    public function actualizar()
    {
        try {
            $sql = "UPDATE {$this->tabla}
                SET 
                    id_categoria = :id_categoria,
                    nombre_producto = :nombre_producto,
                    sku = :sku,
                    descripcion = :descripcion,
                    precio_unitario = :precio_unitario,
                    stock = :stock,
                    imagen_url = :imagen_url,
                    proveedor = :proveedor
                WHERE id_producto = :id_producto";

            $stmt = $this->db->prepare($sql);

            // Vinculamos los valores
            $stmt->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
            $stmt->bindValue(':id_categoria', $this->id_categoria, PDO::PARAM_INT);
            $stmt->bindValue(':nombre_producto', $this->nombre_producto, PDO::PARAM_STR);
            $stmt->bindValue(':sku', $this->sku, PDO::PARAM_STR);
            $stmt->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $stmt->bindValue(':precio_unitario', $this->precio_unitario);
            $stmt->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $stmt->bindValue(':imagen_url', $this->imagen_url, PDO::PARAM_STR);
            $stmt->bindValue(':proveedor', $this->proveedor, PDO::PARAM_STR);

            // Ejecutar consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            return false;
        }
    }


    // Obtener todos los productos con su categoría
    public function obtenerTodos()
    {
        //construimos la Query para consulta 
        $query = "SELECT p.*, c.nombre_categoria 
                  FROM {$this->tabla} p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  ORDER BY p.id_producto DESC";
        //Se crea un objeto db que es un objeto PDO (viene de la clase base Model en CORE/model.php_
        $stmt = $this->db->prepare($query); //objeto PDOStatement listo para ejecutar la consulta.
        $stmt->execute(); // Se ejecuta la consulta SQL.
        //Recupera y retorna todas las filas de la consulta como un array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener categorías
    public function obtenerPorCategoria($id_categoria)
    {
        //construimos la Query para consulta 
        $query = "SELECT p.*, c.nombre_categoria 
              FROM {$this->tabla} p
              INNER JOIN categoria c ON p.id_categoria = c.id_categoria
              WHERE p.id_categoria = :id_categoria
              ORDER BY p.nombre_producto ASC";
        //Se crea un objeto db que es un objeto PDO (viene de la clase base Model en CORE/model.php)
        $stmt = $this->db->prepare($query);//objeto PDOStatement listo para ejecutar la consulta.
        //Vincula el valor del parámetro :id_categoria con la variable $id_categoria como un entero
        $stmt->bindValue(':id_categoria', (int) $id_categoria, PDO::PARAM_INT);
        $stmt->execute(); // Se ejecuta la consulta SQL.
        //Recupera y retorna todas las filas de la consulta como un array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener producto por ID
    public function obtenerPorId($id)
    {
        //construimos la Query para consulta
        $query = "SELECT p.*, c.nombre_categoria 
                  FROM {$this->tabla} p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  WHERE p.id_producto = :id";
        // creamos el objeto PDOStatement
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute(); // ejecuta la consulta SQL.

        return $stmt->fetch(PDO::FETCH_ASSOC); // retorna una sola fila como un array asociativo
    }

    // Buscar productos por un termino ingresado
    public function buscar($termino)
    {
        $query = "SELECT p.*, c.nombre_categoria 
                  FROM {$this->tabla} p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  WHERE p.nombre_producto LIKE :term OR p.descripcion LIKE :term
                  ORDER BY p.nombre_producto";
        $stmt = $this->db->prepare($query);
        //Se crea una cadena de búsqueda con comodines (%) para el operador LIKE de SQL.
        //Con esto se busca la cadena de caracteres contenida en la variable $termino en cualquier parte del texto
        $like = "%{$termino}%";
        $stmt->bindValue(':term', $like, PDO::PARAM_STR);
        $stmt->execute(); // ejecuta la consulta SQL.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calcular total del carrito
    public function calcularTotalCarrito($carrito)
    {
        //variable totalizadora
        $total = 0;
        //verificacion de existencias de producto en el carrito
        if (!empty($carrito)) {
            //si el carrito tiene items se extraen de la variable en la sesion
            foreach ($carrito as $item) {
                if (isset($item['id_producto'], $item['cantidad'])) {

                    // Se consulta el precio real del producto desde la BD para evitar que el usuario realice 
                    // insercion o manipulacion del valor desde la consola del navegador
                    $stmt = $this->db->prepare("SELECT precio_unitario FROM {$this->tabla} WHERE id_producto = :id");
                    $stmt->bindParam(':id', $item['id_producto']);
                    $stmt->execute();
                    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                    //calculo y retorno del precio
                    if ($producto) {
                        $precio = (float) $producto['precio_unitario'];
                        $cantidad = (int) $item['cantidad'];
                        $total += $precio * $cantidad;
                    }
                }
            }
        }

        return $total;
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM {$this->tabla} WHERE id_producto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Reducir stock de manera segura, retorna true si afectó 1 fila
    public function reducirStockSeguro($id_producto, $cantidad)
    {
        $sql = "UPDATE {$this->tabla} SET stock = stock - :cantidad WHERE id_producto = :id AND stock >= :cantidad";
        $st = $this->db->prepare($sql);
        $st->execute([':cantidad' => (int)$cantidad, ':id' => (int)$id_producto]);
        return ($st->rowCount() === 1);
    }



}
