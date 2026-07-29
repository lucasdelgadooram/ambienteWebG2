<?php

require_once '../app/config/Database.php';

class Rol {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM rol ORDER BY id_rol DESC";
        $result = $this->db->query($query);
        $roles = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $roles[] = $row;
            }
        }

        return $roles;
    }

    public function getById($id) {
        $query = "SELECT * FROM rol WHERE id_rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByRol($rol) {
        $query = "SELECT * FROM rol WHERE rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $rol);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $query = "INSERT INTO rol (rol) VALUES (?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $data['rol']);

        return $stmt->execute();
    }

    public function update($id, $data) {
        $query = "UPDATE rol SET rol = ? WHERE id_rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $data['rol'], $id);

        return $stmt->execute();
    }

    public function delete($id) {
        // Verificar si el rol tiene usuarios asociados
        $query = "SELECT COUNT(*) as total FROM usuario_rol WHERE id_rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            return false; // No se puede eliminar porque tiene usuarios asociados
        }

        $query = "DELETE FROM rol WHERE id_rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function tieneUsuarios($id) {
        $query = "SELECT COUNT(*) as total FROM usuario_rol WHERE id_rol = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['total'] > 0;
    }
}