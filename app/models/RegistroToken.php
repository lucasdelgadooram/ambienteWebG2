<?php

require_once '../app/config/Database.php';

class RegistroToken{
    private $db;
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    public function guardarToken($correo,$token){

        $query="INSERT INTO registro_token (correo,token,fecha_expiracion) VALUES (?, ?, DATE_ADD(NOW(),INTERVAL 1 DAY))";
        $stmt=$this->db->prepare($query);

        $stmt->bind_param( "ss",$correo,$token );

        return $stmt->execute();

    }

    public function getByToken($token){

        $query = "SELECT * FROM registro_token WHERE token = ? AND utilizado = 0 AND fecha_expiracion > NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s",$token);

        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();

    }

    public function marcarUtilizado($idToken){

        $query = "UPDATE registro_token SET utilizado = 1 WHERE id_token = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idToken);

        return $stmt->execute();

    }

}