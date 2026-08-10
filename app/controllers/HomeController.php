<?php

require_once '../app/core/Controller.php';

class HomeController extends Controller {

    private $productoModel;
    private $categoriaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productoModel = $this->model('Producto');
        $this->categoriaModel = $this->model('Categoria');
    }

    // Nuevo index con productos
    public function index() {

        $productosRecientes = $this->productoModel->getRecent(8);
        
        $productosOferta = $this->productoModel->getOfertas();

        $categorias = $this->categoriaModel->getAll();

        $data = [
            'titulo' => 'Paluse - Productos Personalizados',
            'productosRecientes' => $productosRecientes,
            'productosOferta' => $productosOferta,
            'categorias' => $categorias,
            'css' => ['indexStyles.css', 'homeStyles.css']
        ];

        $this->view('home/index', $data);
    }

    public function about() {
        $data = [
            'titulo' => 'Sobre Nosotros - Paluse',
            'css' => ['sobreNosotros.css']
        ];
        $this->view('home/about', $data);
    }

    public function soporte() {
        $data = [
            'titulo' => 'Soporte - Paluse',
            'css' => ['contactoStyles.css']
        ];
        $this->view('home/soporte', $data);
    }

    public function historia() {
        $data = [
            'titulo' => 'Historia - Paluse',
            'css' => ['historia.css']
        ];
        $this->view('home/historia', $data);
    }

    public function getCategoriaIcono($nombre) {
    $iconos = [
        'Ropa' => 'tshirt',
        'Accesorios' => 'gem',
        'Envoltorios' => 'gift',
        'Papeleria' => 'pen-ruler',
        'Personalizados' => 'paintbrush',
        'Otros' => 'boxes'
    ];
    return $iconos[$nombre] ?? 'cube';
    }
}