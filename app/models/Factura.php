<?php

require_once '../app/config/Database.php';

class Factura {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene todas las facturas junto con los datos del usuario
     * y la cantidad de productos registrados.
     */
    public function getAll() {
        $query = "SELECT 
                    factura.*,
                    usuario.username,
                    usuario.nombre,
                    usuario.apellidos,
                    COUNT(venta.id_venta) AS cantidad_productos
                  FROM factura
                  INNER JOIN usuario 
                    ON factura.id_usuario = usuario.id_usuario
                  LEFT JOIN venta 
                    ON factura.id_factura = venta.id_factura
                  GROUP BY factura.id_factura
                  ORDER BY factura.id_factura DESC";

        $result = $this->db->query($query);
        $facturas = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $facturas[] = $row;
            }
        }

        return $facturas;
    }

    /**
     * Obtiene una factura específica por su ID.
     */
    public function getById($id) {
        $query = "SELECT 
                    factura.*,
                    usuario.username,
                    usuario.nombre,
                    usuario.apellidos,
                    usuario.correo,
                    usuario.telefono
                  FROM factura
                  INNER JOIN usuario 
                    ON factura.id_usuario = usuario.id_usuario
                  WHERE factura.id_factura = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Crea una factura inicialmente con total 0.
     */
    public function create($data) {
        $query = "INSERT INTO factura (
                    id_usuario,
                    total,
                    estado
                  ) VALUES (?, 0.00, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            'is',
            $data['id_usuario'],
            $data['estado']
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }

        return false;
    }

    /**
     * Actualiza el usuario y el estado de una factura.
     */
    public function update($id, $data) {
        $query = "UPDATE factura
                  SET id_usuario = ?,
                      estado = ?
                  WHERE id_factura = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            'isi',
            $data['id_usuario'],
            $data['estado'],
            $id
        );

        return $stmt->execute();
    }

    /**
     * Elimina una factura.
     *
     * Las ventas relacionadas se eliminan automáticamente
     * mediante ON DELETE CASCADE en database.sql.
     */
    public function delete($id) {
        $query = "DELETE FROM factura
                  WHERE id_factura = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    /**
     * Recalcula el total de una factura utilizando sus ventas.
     */
    public function recalcularTotal($idFactura) {
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

        return $stmt->execute();
    }

    /**
     * Verifica si una factura tiene productos registrados.
     */
    public function tieneVentas($idFactura) {
        $query = "SELECT COUNT(*) AS total
                  FROM venta
                  WHERE id_factura = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $idFactura);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return isset($result['total']) && $result['total'] > 0;
    }

    /**
     * Obtiene las opciones permitidas para el estado.
     */
    public function getEstados() {
        return [
            'Activa',
            'En proceso',
            'Enviado',
            'Completado',
            'Anulada'
        ];
    }
}