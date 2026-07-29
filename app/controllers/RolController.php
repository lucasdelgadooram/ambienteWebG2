<?php

require_once '../app/core/Controller.php';

class RolController extends Controller {
    private $rolModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        // Solo ADMIN puede gestionar roles
        if (!$this->tieneRol('ADMIN')) {
            $this->redirect('/home/index');
        }

        $this->rolModel = $this->model('Rol');
    }

    public function index() {
        $roles = $this->rolModel->getAll();
        
        $this->view('rol/index', [
            'roles' => $roles,
            'css' => ['usuarioStyles.css']
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'rol' => trim($_POST['rol'] ?? '')
            ];


            if (empty($data['rol'])) {
                $this->view('rol/create', [
                    'error' => 'El nombre del rol es obligatorio.',
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            if ($this->rolModel->getByRol($data['rol'])) {
                $this->view('rol/create', [
                    'error' => 'El rol ya existe.',
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            if ($this->rolModel->create($data)) {
                $_SESSION['rol_success'] = 'Rol creado exitosamente.';
                $this->redirect('/rol/index');
            } else {
                $this->view('rol/create', [
                    'error' => 'No se pudo crear el rol.',
                    'css' => ['UserFormularios.css']
                ]);
            }
        } else {
            $this->view('rol/create', [
                'css' => ['UserFormularios.css']
            ]);
        }
    }


    public function edit($id = null) {
        if (!$id) {
            $this->redirect('/rol/index');
        }

        $rol = $this->rolModel->getById($id);

        if (!$rol) {
            $this->redirect('/rol/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'rol' => trim($_POST['rol'] ?? '')
            ];


            if (empty($data['rol'])) {
                $this->view('rol/edit', [
                    'rol' => $rol,
                    'error' => 'El nombre del rol es obligatorio.',
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            
            $existingRol = $this->rolModel->getByRol($data['rol']);
            if ($existingRol && $existingRol['id_rol'] != $id) {
                $this->view('rol/edit', [
                    'rol' => $rol,
                    'error' => 'El rol ya existe.',
                    'css' => ['UserFormularios.css']
                ]);
                return;
            }

            // Actualizar rol
            if ($this->rolModel->update($id, $data)) {
                $_SESSION['rol_success'] = 'Rol actualizado exitosamente.';
                $this->redirect('/rol/index');
            } else {
                $this->view('rol/edit', [
                    'rol' => $rol,
                    'error' => 'No se pudo actualizar el rol.',
                    'css' => ['UserFormularios.css']
                ]);
            }
        } else {
            $this->view('rol/edit', [
                'rol' => $rol,
                'css' => ['UserFormularios.css']
            ]);
        }
    }

    public function delete($id = null) {
        if (!$id) {
            $this->redirect('/rol/index');
        }

        $rol = $this->rolModel->getById($id);

        if (!$rol) {
            $this->redirect('/rol/index');
        }

        // Verificar si tiene usuarios asociados
        if ($this->rolModel->tieneUsuarios($id)) {
            $_SESSION['rol_error'] = 'No se puede eliminar el rol porque tiene usuarios asociados.';
            $this->redirect('/rol/index');
        }

        if ($this->rolModel->delete($id)) {
            $_SESSION['rol_success'] = 'Rol eliminado correctamente.';
        } else {
            $_SESSION['rol_error'] = 'No se pudo eliminar el rol.';
        }

        $this->redirect('/rol/index');
    }
}