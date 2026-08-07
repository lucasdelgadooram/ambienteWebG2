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
            categoria.descripcion
                AS categoria_descripcion
        FROM producto
        INNER JOIN categoria
            ON producto.id_categoria =
               categoria.id_categoria
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
}