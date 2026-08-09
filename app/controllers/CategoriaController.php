<?php

//Importa el  controller  
require_once '../app/core/Controller.php';

//Hereda desde Controller
class CategoriaController extends Controller {
    //Guarda un objeto de tipo categoria, luego se utilizara
    private $categoriaModel;

    //hace el constructor
    public function __construct() {
        //Inicia sesion
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //si la sesion no existe, entonces lo manda a loguearse
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        if (!$this->tieneRol('ADMIN')) {
            $this->redirect('/home/about');
        }

        //sino, entonces podra llamar al modelo de categoria
        $this->categoriaModel = $this->model('Categoria');
    }


    public function index() {
        //va hacia el modelo y ejecuta el metodo getAll, este metodo tiene la consulta select * from categoria
        $categorias = $this->categoriaModel->getAll();
        //Carga la vista dentro de categoria llamada index.php
        $this->view('categoria/index', [
            'categorias' => $categorias
        ]);

    }

    public function create() {
        //le da el metodo, pasa la data y valida por campos y existencias
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'descripcion' => $_POST['descripcion'] ?? '',
                'ruta_imagen' => $_POST['ruta_imagen'] ?? '',
                'activo' => 1
            ];

            // Validación de campos obligatorios
            if (empty($data['descripcion'])) {
                $this->view('categoria/create', [ 'error' => 'La descripción es obligatoria.']);
                return;
            }

            // Validar categoría repetida
            if ($this->categoriaModel->getByDescripcion($data['descripcion'])) {
                $this->view('categoria/create', ['error' => 'La categoría ya existe.']);
                return;
            }
            // Crear categoría
            $resultado = $this->categoriaModel->create($data);

            //aqui si ya es la redireccion y error
            if ($resultado) {
                $this->redirect('/categoria/index');

            } else {
                $this->view('categoria/create', [
                    'error' => 'No se pudo crear la categoría.'
                ]);
            }

        } else {
            $this->view('categoria/create');
        }

    }

    //recibe como parametro el id
     public function edit($id = null) {
        //funciona practicamente que igual que usuario 
        //sino esta, entonces lo manda al index de categoria
        if (!$id) {
            $this->redirect('/categoria/index');
        }
        //aqui igual recibe la data
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'descripcion' => $_POST['descripcion'] ?? '',
                'ruta_imagen' => $_POST['ruta_imagen'] ?? '',
                'activo' => 1
            ];

            // Validar campo obligatorio
            if (empty($data['descripcion'])) {
                //aqui si  que va a por tomar el id
                $categoria = $this->categoriaModel->getById($id);
                //pasa a la edicion y le manda los receptores
                $this->view('categoria/edit', [
                    'categoria' => $categoria,
                    'error' => 'La descripción es obligatoria.'
                ]);

                return;
            }

            // Validar descripción duplicada
            $existingCategoria = $this->categoriaModel->getByDescripcion($data['descripcion']);

            if ($existingCategoria && $existingCategoria['id_categoria'] != $id) {

                $categoria = $this->categoriaModel->getById($id);

                $this->view('categoria/edit', [
                    'categoria' => $categoria,
                    'error' => 'La categoría ya existe.'
                ]);

                return;
            }

            // Actualizar categoría
            $resultado = $this->categoriaModel->update($id, $data);

            if ($resultado) {

                $this->redirect('/categoria/index');

            } else {

                $categoria = $this->categoriaModel->getById($id);

                $this->view('categoria/edit', [
                    'categoria' => $categoria,
                    'error' => 'No se pudo actualizar la categoría.'
                ]);

            }

        } else {

            $categoria = $this->categoriaModel->getById($id);

            if ($categoria) {

                $this->view('categoria/edit', [
                    'categoria' => $categoria
                ]);

            } else {

                $this->redirect('/categoria/index');

            }

        }

    }

    //aqui es practicamente igual recibe un id si ese id esta entonces lo elimina coso contrairo lo mandaria al listado
    public function delete($id = null) {

        if ($id) {
            $this->categoriaModel->delete($id);
        }

        $this->redirect('/categoria/index');

    }





}