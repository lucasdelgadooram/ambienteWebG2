<?php

require_once '../app/config/Database.php';

class RecuperacionToken
{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function guardarToken($idUsuario, $token){
        // Eliminar tokens anteriores del usuario
        $query = "DELETE FROM recuperacion_password WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        // Crear 
        $query = "INSERT INTO recuperacion_password (id_usuario, token, fecha_expiracion, utilizado) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY), 0)";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param( "is", $idUsuario, $token );
        return $stmt->execute();
    }

    public function getByToken($token){
        $query = "SELECT * FROM recuperacion_password WHERE token = ? AND utilizado = 0 AND fecha_expiracion > NOW()";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("s", $token);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function marcarUtilizado($idRecuperacion){
        $query = "UPDATE recuperacion_password SET utilizado = 1 WHERE id_recuperacion = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $idRecuperacion);

        return $stmt->execute();
    }
}