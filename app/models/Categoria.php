<?php

//Trae la conexion creada en el archivo Database.php
require_once '../app/config/Database.php';

//Representa la tabla y dentro se guarda la conexion, hace un constructor
class Categoria {

    private $db;

    public function __construct() {

        $this->db = Database::getInstance()->getConnection();

    }

    /*Obtiene todas categorias en orden del id
    Manda la consulta y posterior se crea el arreglo vacio llamado categorias
    
    si la consulta funciona y hay datos, entonces ejecuta
    mientras que cada fila se vuelva parte del arreglo*/
    public function getAll() {

        $query = "SELECT * FROM categoria ORDER BY id_categoria DESC";
        $result = $this->db->query($query);

        $categorias = [];

        if($result && $result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $categorias[] = $row;
        }
        }
        //devuelve el arreglo
        return $categorias;

    }

    public function getById($id) {
        // igualmente va y hace un query, pero como se ve tiene que cambiar el id luego
        $query = "SELECT * FROM categoria WHERE id_categoria = ?";
        //recibe el query  y dice que en esta misma clase
        $stmt = $this->db->prepare($query);
        //cambia el id, el i le dice el tipo de dato
        $stmt->bind_param("i", $id);
        //ejecutamos y mandamos la consulta
        $stmt->execute();

        //busca obtener el resultado
        $result = $stmt->get_result();
        //devuelve el resultado, pero como un array
        return $result->fetch_assoc();

    }

    public function getByDescripcion($descripcion){

        $query = "SELECT * FROM categoria WHERE descripcion = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("s",$descripcion);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();

    }

    //El controlador tomara datos de la categoria y los mandara con el parametro $data
    
    public function create($data) {
        //Hace el query en donde pasa los parametros como ? 
        $query = "INSERT INTO categoria (descripcion, ruta_imagen, activo) VALUES (?, ?, ?)";
        //en esta clase va y toma el query
        $stmt = $this->db->prepare($query);
        // cambia los parametros
        $stmt->bind_param("ssi",$data['descripcion'],$data['ruta_imagen'],$data['activo'] );

        //retorna la ejecucion del statement
        return $stmt->execute();

    }

    /*
    Aqui es exactamente lo mismo qeu en los anteriores metodos
    */

    public function update($id,$data) {

        $query = "UPDATE categoria 
                  SET descripcion = ?, ruta_imagen = ?, activo = ?
                  WHERE id_categoria = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param(
            "ssii",
            $data['descripcion'],
            $data['ruta_imagen'],
            $data['activo'],
            $id
        );

        return $stmt->execute();

    }

    public function delete($id) {

        $query = "UPDATE categoria
                SET activo = 0
                WHERE id_categoria = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i",$id);

        return $stmt->execute();

    }   



}