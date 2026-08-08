<?php

require_once '../app/config/Database.php';

class MensajeContacto {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO mensaje_contacto (nombre, correo, telefono, consulta, leido) VALUES (?, ?, ?, ?, 0)";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "ssss",
            $data['nombre'],
            $data['correo'],
            $data['telefono'],
            $data['consulta']
        );

        return $stmt->execute();
    }
}