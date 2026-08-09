<?php

require_once '../app/core/Controller.php';

class HomeController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $this->redirect('/home/about');
    }

    public function about() {
        $this->view('home/sobreNosotros', [
            'css' => ['sobreNosotros.css']
        ]);
    }

    public function historia() {
        $this->view('home/historia', [
            'css' => ['historia.css']
        ]);
    }

    public function soporte() {
        $this->view('home/soporte', [
            'css' => ['contactoStyles.css']
        ]);
    }

    public function help() {
        $this->redirect('/home/soporte');
    }
}