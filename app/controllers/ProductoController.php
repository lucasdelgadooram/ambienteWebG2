<?php

require_once '../app/core/Controller.php';

class ProductoController extends Controller {

    private $productoModel;
    private $categoriaModel;

    private function requireAdmin() {
        if (!$this->tieneRol('ADMIN')) {
            $this->redirect('/home/about');
        }
    }

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        $this->productoModel = $this->model('Producto');
        $this->categoriaModel = $this->model('Categoria');
    }

    public function catalogo() {
        $this->view('producto/catalogo', [
            'productos' => $this->productoModel->getActive(),
            'css' => ['catalogoStyles.css']
        ]);
    }

    public function index() {
        $this->requireAdmin();

        $this->view('producto/index', [
            'productos' => $this->productoModel->getAll(),
            'categorias' => $this->categoriaModel->getAll(),
            'css' => [
                'indexStyles.css',
                'adminStyles.css',
                'gestionarProductosStyles.css'
            ]
        ]);
    }


    public function create() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->buildProductData();

            if ($error = $this->validateProductData($data)) {
                $this->view('producto/create', [
                    'error' => $error,
                    'producto' => $data,
                    'categorias' => $this->categoriaModel->getAll(),
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            if ($this->productoModel->getByDescripcion($data['descripcion'])) {
                $this->view('producto/create', [
                    'error' => 'Ya existe un producto con esa descripción.',
                    'producto' => $data,
                    'categorias' => $this->categoriaModel->getAll(),
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            if ($this->productoModel->create($data)) {
                $this->redirect('/producto/index');
            }

            $this->view('producto/create', [
                'error' => 'No se pudo crear el producto.',
                'producto' => $data,
                'categorias' => $this->categoriaModel->getAll(),
                'css' => ['UserFormularios.css']
            ]);
            return;
        }

        $this->view('producto/create', [
            'categorias' => $this->categoriaModel->getAll(),
            'css' => ['UserFormularios.css']
        ]);
    }

    public function edit($id = null) {
        $this->requireAdmin();

        if (!$id) {
            $this->redirect('/producto/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->buildProductData();

            if ($error = $this->validateProductData($data)) {
                $data['id_producto'] = $id;
                $this->view('producto/edit', [
                    'error' => $error,
                    'producto' => $data,
                    'categorias' => $this->categoriaModel->getAll(),
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            $existingProduct = $this->productoModel->getByDescripcion($data['descripcion']);
            if ($existingProduct && $existingProduct['id_producto'] != $id) {
                $data['id_producto'] = $id;
                $this->view('producto/edit', [
                    'error' => 'Ya existe un producto con esa descripción.',
                    'producto' => $data,
                    'categorias' => $this->categoriaModel->getAll(),
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            if ($this->productoModel->update($id, $data)) {
                $this->redirect('/producto/index');
            }

            $data['id_producto'] = $id;
            $this->view('producto/edit', [
                'error' => 'No se pudo actualizar el producto.',
                'producto' => $data,
                'categorias' => $this->categoriaModel->getAll(),
                'css' => ['UserFormularios.css']
            ]);
            return;
        }

        $producto = $this->productoModel->getById($id);
        if (!$producto) {
            $this->redirect('/producto/index');
        }

        $this->view('producto/edit', [
            'producto' => $producto,
            'categorias' => $this->categoriaModel->getAll(),
            'css' => ['UserFormularios.css']
        ]);
    }

    public function delete($id = null) {
        $this->requireAdmin();

        if ($id) {
            $this->productoModel->delete($id);
        }

        $this->redirect('/producto/index');
    }

    private function buildProductData() {
        return [
            'id_categoria' => (int) ($_POST['id_categoria'] ?? 0),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'detalle' => trim($_POST['detalle'] ?? ''),
            'precio' => (float) ($_POST['precio'] ?? 0),
            'existencias' => (int) ($_POST['existencias'] ?? 0),
            'ruta_imagen' => trim($_POST['ruta_imagen'] ?? ''),
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];
    }

    private function validateProductData($data) {
        if ($data['id_categoria'] <= 0) {
            return 'Debes seleccionar una categoría.';
        }

        if ($data['descripcion'] === '') {
            return 'La descripción es obligatoria.';
        }

        if ($data['precio'] < 0) {
            return 'El precio no puede ser negativo.';
        }

        if ($data['existencias'] < 0) {
            return 'Las existencias no pueden ser negativas.';
        }

        return null;
    }
}