<?php
class Controller {
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die("La vista $view no existe.");
        }
    }

    public function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    //Tener rol, espera un rol para buscar.
    public function tieneRol($rolBuscado) {
    //basicamente que si alguien intenta iniciar sin la variable se session roles entonces retorna falso
        if (!isset($_SESSION['roles'])) {
        return false;
        }
    //aqui compara por cada arreglos y el rol, entonces lo encuentra y retorna true
        foreach ($_SESSION['roles'] as $rol) {
            if ($rol['rol'] == $rolBuscado) {
                return true;
            }
        }

        return false;
    }
    
}
