<?php

class Controller {
    
    public function model($model) {
        require_once APPROOT . '/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        $viewPath = APPROOT . '/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die('La vista ' . $view . ' no existe en: ' . $viewPath);
        }
    }

    public function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    protected function tieneRol($rolRequerido) {
        if (!isset($_SESSION['roles']) || !is_array($_SESSION['roles'])) {
            return false;
        }
        foreach ($_SESSION['roles'] as $rol) {
            if (isset($rol['rol']) && $rol['rol'] === $rolRequerido) {
                return true;
            }
        }
        return false;
    }
}
