<?php

require_once '../app/config/Database.php';

class Venta {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }


    public function getAll() {
        $query = "SELECT
                    venta.*,
                    factura.fecha AS fecha_factura,
                    factura.estado AS estado_factura,
                    usuario.nombre,
                    usuario.apellidos,
                    producto.descripcion AS producto_descripcion,
                    (venta.precio_historico * venta.cantidad) AS subtotal
                  FROM venta
                  INNER JOIN factura
                    ON venta.id_factura = factura.id_factura
                  INNER JOIN usuario
                    ON factura.id_usuario = usuario.id_usuario
                  INNER JOIN producto
                    ON venta.id_producto = producto.id_producto
                  ORDER BY venta.id_venta DESC";

        $result = $this->db->query($query);
        $ventas = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ventas[] = $row;
            }
        }

        return $ventas;
    }

    public function getByFactura($idFactura) {
        $query = "SELECT
                    venta.*,
                    producto.descripcion AS producto_descripcion,
                    producto.existencias,
                    (venta.precio_historico * venta.cantidad) AS subtotal
                  FROM venta
                  INNER JOIN producto
                    ON venta.id_producto = producto.id_producto
                  WHERE venta.id_factura = ?
                  ORDER BY venta.id_venta DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idFactura);
        $stmt->execute();

        $result = $stmt->get_result();
        $ventas = [];

        while ($row = $result->fetch_assoc()) {
            $ventas[] = $row;
        }

        return $ventas;
    }

    public function getById($id) {
        $query = "SELECT
                    venta.*,
                    producto.descripcion AS producto_descripcion,
                    producto.precio AS precio_actual,
                    factura.id_usuario
                  FROM venta
                  INNER JOIN producto
                    ON venta.id_producto = producto.id_producto
                  INNER JOIN factura
                    ON venta.id_factura = factura.id_factura
                  WHERE venta.id_venta = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getByFacturaAndProducto($idFactura, $idProducto) {
        $query = "SELECT *
                  FROM venta
                  WHERE id_factura = ?
                    AND id_producto = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            'ii',
            $idFactura,
            $idProducto
        );

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $this->db->begin_transaction();

        try {
            $producto = $this->getProductoForUpdate($data['id_producto']);

            if (!$producto) {
                throw new Exception('El producto seleccionado no existe.');
            }

            if ((int) $producto['activo'] !== 1) {
                throw new Exception('El producto seleccionado está inactivo.');
            }

            if ((int) $producto['existencias'] < $data['cantidad']) {
                throw new Exception(
                    'No hay suficientes existencias del producto.'
                );
            }

            $precioHistorico = (float) $producto['precio'];

            $query = "INSERT INTO venta (
                        id_factura,
                        id_producto,
                        precio_historico,
                        cantidad
                      ) VALUES (?, ?, ?, ?)";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                'iidi',
                $data['id_factura'],
                $data['id_producto'],
                $precioHistorico,
                $data['cantidad']
            );

            if (!$stmt->execute()) {
                throw new Exception('No se pudo registrar la venta.');
            }

            $query = "UPDATE producto
                      SET existencias = existencias - ?
                      WHERE id_producto = ?";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                'ii',
                $data['cantidad'],
                $data['id_producto']
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'No se pudieron actualizar las existencias.'
                );
            }

            $this->recalcularTotalFactura($data['id_factura']);

            $this->db->commit();

            return true;
        } catch (Throwable $error) {
            $this->db->rollback();

            throw $error;
        }
    }

    public function update($id, $data) {
        $this->db->begin_transaction();

        try {
            $ventaAnterior = $this->getVentaForUpdate($id);

            if (!$ventaAnterior) {
                throw new Exception('La venta no existe.');
            }

            $idFacturaAnterior = (int) $ventaAnterior['id_factura'];
            $idProductoAnterior = (int) $ventaAnterior['id_producto'];
            $cantidadAnterior = (int) $ventaAnterior['cantidad'];

            $query = "UPDATE producto
                      SET existencias = existencias + ?
                      WHERE id_producto = ?";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                'ii',
                $cantidadAnterior,
                $idProductoAnterior
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'No se pudieron devolver las existencias anteriores.'
                );
            }

            $productoNuevo = $this->getProductoForUpdate(
                $data['id_producto']
            );

            if (!$productoNuevo) {
                throw new Exception('El producto seleccionado no existe.');
            }

            if ((int) $productoNuevo['activo'] !== 1) {
                throw new Exception('El producto seleccionado está inactivo.');
            }

            if ((int) $productoNuevo['existencias'] < $data['cantidad']) {
                throw new Exception(
                    'No hay suficientes existencias del producto.'
                );
            }

            $precioHistorico = (float) $productoNuevo['precio'];

            $query = "UPDATE venta
                      SET id_factura = ?,
                          id_producto = ?,
                          precio_historico = ?,
                          cantidad = ?
                      WHERE id_venta = ?";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                'iidii',
                $data['id_factura'],
                $data['id_producto'],
                $precioHistorico,
                $data['cantidad'],
                $id
            );

            if (!$stmt->execute()) {
                throw new Exception('No se pudo actualizar la venta.');
            }

            $query = "UPDATE producto
                      SET existencias = existencias - ?
                      WHERE id_producto = ?";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                'ii',
                $data['cantidad'],
                $data['id_producto']
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'No se pudieron descontar las nuevas existencias.'
                );
            }

            $this->recalcularTotalFactura($idFacturaAnterior);

            if ($idFacturaAnterior !== $data['id_factura']) {
                $this->recalcularTotalFactura(
                    $data['id_factura']
                );
            }

            $this->db->commit();

            return true;
        } catch (Throwable $error) {
            $this->db->rollback();

            throw $error;
        }
    }

    public function delete($id) {
        $this->db->begin_transaction();

        try {
            $venta = $this->getVentaForUpdate($id);

            if (!$venta) {
                throw new Exception('La venta no existe.');
            }

            $idFactura = (int) $venta['id_factura'];
            $idProducto = (int) $venta['id_producto'];
            $cantidad = (int) $venta['cantidad'];

            $query = "DELETE FROM venta
                      WHERE id_venta = ?";

            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $id);

            if (!$stmt->execute()) {
                throw new Exception('No se pudo eliminar la venta.');
            }

            $query = "UPDATE producto
                      SET existencias = existencias + ?
                      WHERE id_producto = ?";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                'ii',
                $cantidad,
                $idProducto
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'No se pudieron devolver las existencias.'
                );
            }

            $this->recalcularTotalFactura($idFactura);

            $this->db->commit();

            return true;
        } catch (Throwable $error) {
            $this->db->rollback();

            throw $error;
        }
    }

    private function getProductoForUpdate($idProducto) {
        $query = "SELECT *
                  FROM producto
                  WHERE id_producto = ?
                  FOR UPDATE";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idProducto);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    private function getVentaForUpdate($idVenta) {
        $query = "SELECT *
                  FROM venta
                  WHERE id_venta = ?
                  FOR UPDATE";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idVenta);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    private function recalcularTotalFactura($idFactura) {
        $query = "UPDATE factura
                  SET total = (
                      SELECT COALESCE(
                          SUM(precio_historico * cantidad),
                          0
                      )
                      FROM venta
                      WHERE venta.id_factura = factura.id_factura
                  )
                  WHERE id_factura = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idFactura);

        if (!$stmt->execute()) {
            throw new Exception(
                'No se pudo recalcular el total de la factura.'
            );
        }
    }
}