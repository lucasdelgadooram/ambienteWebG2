<?php
require_once '../app/config/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {

        $query = "SELECT usuario.*, rol.rol AS nombre_rol FROM usuario
            INNER JOIN usuario_rol
                ON usuario.id_usuario = usuario_rol.id_usuario
            INNER JOIN rol
                ON usuario_rol.id_rol = rol.id_rol
            WHERE usuario.activo = 1
            ORDER BY usuario.id_usuario DESC
        ";

        $result = $this->db->query($query);

        $users = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }

    public function getById($id) {
        $query = "SELECT * FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getByEmail($email) {
        $query = "SELECT * FROM usuario WHERE correo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getByUsername($username){
        $query="SELECT * FROM usuario WHERE username=?";
        $stmt=$this->db->prepare($query);
        $stmt->bind_param("s",$username);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getRoles($idUsuario) {

        $query = "SELECT rol.* FROM usuario_rol AS usuarioRol INNER JOIN rol ON usuarioRol.id_rol = rol.id_rol WHERE usuarioRol.id_usuario = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        return $roles;
    }

    public function create($data) {

        // Insertar usuario
        $query = "INSERT INTO usuario (username, password, nombre, apellidos, correo, telefono, ruta_imagen, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->bind_param(
            "sssssssi",
            $data['username'],
            $hashedPassword,
            $data['nombre'],
            $data['apellidos'],
            $data['correo'],
            $data['telefono'],
            $data['ruta_imagen'],
            $data['activo']
        );

        if (!$stmt->execute()) {
            return false;
        }

        // Obtener el id del usuario recién creado
        $idUsuario = $this->db->insert_id;

        // Asignar el rol seleccionado
        $query = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "ii",
            $idUsuario,
            $data['id_rol']
        );

        return $stmt->execute();
}

    public function update($id, $data) {

        if (!empty($data['password'])) {
            $query = " UPDATE usuario SET 
                    username = ?,
                    password = ?,
                    nombre = ?,
                    apellidos = ?,
                    correo = ?,
                    telefono = ?,
                    ruta_imagen = ?,
                    activo = ?
                WHERE id_usuario = ?
            ";

            $stmt = $this->db->prepare($query);

            $hashedPassword = password_hash($data['password'],PASSWORD_DEFAULT);

            $stmt->bind_param(
                "sssssssii",
                $data['username'],
                $hashedPassword,
                $data['nombre'],
                $data['apellidos'],
                $data['correo'],
                $data['telefono'],
                $data['ruta_imagen'],
                $data['activo'],
                $id
            );

        } else {
            $query = "UPDATE usuario SET 
                    username = ?,
                    nombre = ?,
                    apellidos = ?,
                    correo = ?,
                    telefono = ?,
                    ruta_imagen = ?,
                    activo = ?
                WHERE id_usuario = ?
            ";

            $stmt = $this->db->prepare($query);

            $stmt->bind_param(
                "ssssssii",
                $data['username'],
                $data['nombre'],
                $data['apellidos'],
                $data['correo'],
                $data['telefono'],
                $data['ruta_imagen'],
                $data['activo'],
                $id
            );

        }
        return $stmt->execute();

}

    public function delete($id) {
        $query = "UPDATE usuario SET activo = 0 WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function existeCorreo($correo){

        $query = "SELECT id_usuario FROM usuario WHERE correo=?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("s",$correo);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
