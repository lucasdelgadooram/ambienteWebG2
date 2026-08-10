<?php

require_once '../app/config/Database.php';

class Producto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = "SELECT producto.*, categoria.descripcion AS categoria_descripcion
                  FROM producto
                  INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
                  ORDER BY producto.id_producto DESC";

        $result = $this->db->query($query);
        $productos = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        return $productos;
    }

    public function getActive() {
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
            ORDER BY producto.id_producto DESC
        ";

        $result = $this->db->query($query);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getActiveByCategoriaId($categoriaId) {
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
              AND producto.id_categoria = ?
            ORDER BY producto.id_producto DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $categoriaId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function buscar($termino) {
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
              AND (producto.descripcion LIKE ? OR producto.detalle LIKE ?)
            ORDER BY producto.id_producto DESC
        ";

        $stmt = $this->db->prepare($query);
        $like = '%' . $termino . '%';
        $stmt->bind_param('ss', $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOfertas() {
        //Se realiza una simulación de ofertas
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion,
                (producto.precio * 0.8) AS precio_oferta,
                20 AS porcentaje_descuento
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
              AND producto.precio > 10000
            ORDER BY producto.id_producto DESC
            LIMIT 6
        ";

        $result = $this->db->query($query);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM producto WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getByDescripcion($descripcion) {
        $query = "SELECT * FROM producto WHERE descripcion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $descripcion);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $query = "INSERT INTO producto (id_categoria, descripcion, detalle, precio, existencias, ruta_imagen, activo)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            'issdisi',
            $data['id_categoria'],
            $data['descripcion'],
            $data['detalle'],
            $data['precio'],
            $data['existencias'],
            $data['ruta_imagen'],
            $data['activo']
        );

        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE producto
                  SET id_categoria = ?, descripcion = ?, detalle = ?, precio = ?, existencias = ?, ruta_imagen = ?, activo = ?
                  WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            'issdisii',
            $data['id_categoria'],
            $data['descripcion'],
            $data['detalle'],
            $data['precio'],
            $data['existencias'],
            $data['ruta_imagen'],
            $data['activo'],
            $id
        );

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "UPDATE producto SET activo = 0 WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    public function getActiveByCategoria($categoria) {
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
              AND categoria.descripcion = ?
            ORDER BY producto.id_producto DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $categoria);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function getRecent($limite = 8) {
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
            ORDER BY producto.id_producto DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDestacados($limite = 4) {
        $query = "
            SELECT
                producto.*,
                categoria.descripcion AS categoria_descripcion
            FROM producto
            INNER JOIN categoria
                ON producto.id_categoria = categoria.id_categoria
            WHERE producto.activo = 1
              AND producto.existencias > 0
            ORDER BY RAND()
            LIMIT ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

}