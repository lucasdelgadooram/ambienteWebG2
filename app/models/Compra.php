<?php

require_once '../app/config/Database.php';

class Compra {
    private $db;

    public function __construct() {
        $this->db =
            Database::getInstance()->getConnection();
    }

    public function finalizar($idUsuario, array $carrito) {
        if (empty($carrito)) {
            throw new Exception(
                'El carrito está vacío.'
            );
        }

        $this->db->begin_transaction();

        try {
            $queryFactura = "
                INSERT INTO factura (
                    id_usuario,
                    total,
                    estado
                )
                VALUES (?, 0.00, 'Activa')
            ";

            $stmtFactura =
                $this->db->prepare($queryFactura);

            $stmtFactura->bind_param(
                'i',
                $idUsuario
            );

            $stmtFactura->execute();

            $idFactura = $this->db->insert_id;

            $queryProducto = "
                SELECT
                    id_producto,
                    precio,
                    existencias,
                    activo
                FROM producto
                WHERE id_producto = ?
                FOR UPDATE
            ";

            $stmtProducto =
                $this->db->prepare($queryProducto);

            $queryVenta = "
                INSERT INTO venta (
                    id_factura,
                    id_producto,
                    precio_historico,
                    cantidad
                )
                VALUES (?, ?, ?, ?)
            ";

            $stmtVenta =
                $this->db->prepare($queryVenta);

            $queryStock = "
                UPDATE producto
                SET existencias = existencias - ?
                WHERE id_producto = ?
            ";

            $stmtStock =
                $this->db->prepare($queryStock);

            $total = 0;

            foreach (
                $carrito as $idProducto => $cantidad
            ) {
                $idProducto = (int) $idProducto;
                $cantidad = (int) $cantidad;

                if ($cantidad <= 0) {
                    throw new Exception(
                        'Hay una cantidad inválida en el carrito.'
                    );
                }

                $stmtProducto->bind_param(
                    'i',
                    $idProducto
                );

                $stmtProducto->execute();

                $producto = $stmtProducto
                    ->get_result()
                    ->fetch_assoc();

                if (
                    !$producto ||
                    (int) $producto['activo'] !== 1
                ) {
                    throw new Exception(
                        'Uno de los productos ya no está disponible.'
                    );
                }

                if (
                    (int) $producto['existencias']
                    < $cantidad
                ) {
                    throw new Exception(
                        'No hay suficientes existencias para uno de los productos.'
                    );
                }

                $precio =
                    (float) $producto['precio'];

                $stmtVenta->bind_param(
                    'iidi',
                    $idFactura,
                    $idProducto,
                    $precio,
                    $cantidad
                );

                $stmtVenta->execute();

                $stmtStock->bind_param(
                    'ii',
                    $cantidad,
                    $idProducto
                );

                $stmtStock->execute();

                $total += $precio * $cantidad;
            }

            $queryTotal = "
                UPDATE factura
                SET total = ?
                WHERE id_factura = ?
            ";

            $stmtTotal =
                $this->db->prepare($queryTotal);

            $stmtTotal->bind_param(
                'di',
                $total,
                $idFactura
            );

            $stmtTotal->execute();

            $this->db->commit();

            return $idFactura;
        } catch (Throwable $error) {
            $this->db->rollback();

            throw $error;
        }
    }
}