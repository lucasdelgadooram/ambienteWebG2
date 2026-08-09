<?php

require_once '../app/core/Controller.php';

class FacturaController extends Controller {

    private $facturaModel;
    private $usuarioModel;

    public function __construct() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        if (!$this->tieneRol('ADMIN')) {
            $this->redirect('/home/about');
        }

        $this->facturaModel = $this->model('Factura');
        $this->usuarioModel = $this->model('User');
    }

    public function index() {

        $this->view('factura/index', [
            'facturas' => $this->facturaModel->getAll(),
            'css' => [
                'indexStyles.css',
                'adminStyles.css'
            ]
        ]);
    }

    public function create() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'id_usuario' => (int) ($_POST['id_usuario'] ?? 0),
                'estado' => trim($_POST['estado'] ?? '')
            ];

            if ($data['id_usuario'] <= 0) {

                $this->view('factura/create', [
                    'error' => 'Debe seleccionar un usuario.',
                    'factura' => $data,
                    'usuarios' => $this->usuarioModel->getAll(),
                    'estados' => $this->facturaModel->getEstados(),
                    'css' => ['UserFormularios.css']
                ]);

                return;
            }

            if ($data['estado'] === '') {

                $this->view('factura/create', [
                    'error' => 'Debe seleccionar un estado.',
                    'factura' => $data,
                    'usuarios' => $this->usuarioModel->getAll(),
                    'estados' => $this->facturaModel->getEstados(),
                    'css' => ['UserFormularios.css']
                ]);

                return;
            }

            if ($this->facturaModel->create($data)) {
                $this->redirect('/factura/index');
            }

            $this->view('factura/create', [
                'error' => 'No se pudo crear la factura.',
                'factura' => $data,
                'usuarios' => $this->usuarioModel->getAll(),
                'estados' => $this->facturaModel->getEstados(),
                'css' => ['UserFormularios.css']
            ]);

            return;
        }

        $this->view('factura/create', [
            'factura' => [
                'id_usuario' => 0,
                'estado' => 'Activa'
            ],
            'usuarios' => $this->usuarioModel->getAll(),
            'estados' => $this->facturaModel->getEstados(),
            'css' => ['UserFormularios.css']
        ]);
    }

    public function edit($id = null) {

        if (!$id) {
            $this->redirect('/factura/index');
        }

        $factura = $this->facturaModel->getById($id);

        if (!$factura) {
            $this->redirect('/factura/index');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'id_usuario' => (int) ($_POST['id_usuario'] ?? 0),
                'estado' => trim($_POST['estado'] ?? ''),
                'id_factura' => $id
            ];

            if ($data['id_usuario'] <= 0) {

                $this->view('factura/edit', [
                    'error' => 'Debe seleccionar un usuario.',
                    'factura' => $data,
                    'usuarios' => $this->usuarioModel->getAll(),
                    'estados' => $this->facturaModel->getEstados(),
                    'css' => ['UserFormularios.css']
                ]);

                return;
            }

            if ($data['estado'] === '') {

                $this->view('factura/edit', [
                    'error' => 'Debe seleccionar un estado.',
                    'factura' => $data,
                    'usuarios' => $this->usuarioModel->getAll(),
                    'estados' => $this->facturaModel->getEstados(),
                    'css' => ['UserFormularios.css']
                ]);

                return;
            }

            if ($this->facturaModel->update($id, $data)) {
                $this->redirect('/factura/index');
            }

            $this->view('factura/edit', [
                'error' => 'No se pudo actualizar la factura.',
                'factura' => $data,
                'usuarios' => $this->usuarioModel->getAll(),
                'estados' => $this->facturaModel->getEstados(),
                'css' => ['UserFormularios.css']
            ]);

            return;
        }

        $this->view('factura/edit', [
            'factura' => $factura,
            'usuarios' => $this->usuarioModel->getAll(),
            'estados' => $this->facturaModel->getEstados(),
            'css' => ['UserFormularios.css']
        ]);
    }

    public function delete($id = null) {

        if ($id) {
            $this->facturaModel->delete($id);
        }

        $this->redirect('/factura/index');
    }
}