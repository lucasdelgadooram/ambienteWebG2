<?php
require_once '../app/core/Controller.php';

class UserController extends Controller {
    private $userModel;

    public function __construct() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }
        $this->userModel = $this->model('User');
    }

    public function index() {
        $users = $this->userModel->getAll();
        $this->view('usuario/index', ['users' => $users]);
    }

    public function create() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'apellidos' => $_POST['apellidos'] ?? '',
                'correo' => $_POST['correo'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'ruta_imagen' => $_POST['ruta_imagen'] ?? '',
                'activo' => 1
            ];

            // Validaciones básicas
            if (
                empty($data['username']) ||
                empty($data['password']) ||
                empty($data['nombre']) ||
                empty($data['correo'])
            ) {
                $this->view('usuario/create', [
                    'error' => 'Los campos obligatorios deben estar llenos'
                ]);
                return;
            }


            // Validar correo repetido
            if($this->userModel->getByEmail($data['correo'])){
                $this->view('usuario/create', [
                    'error' => 'El correo ya está registrado'
                ]);
                return;
            }


            // Validar username repetido
            if($this->userModel->getByUsername($data['username'])){
                $this->view('usuario/create', [
                    'error' => 'El usuario ya existe'
                ]);
                return;
            }

            // Crear usuario
            $resultado = $this->userModel->create($data);

            if($resultado){
                $this->redirect('/user/index');

            }else{
                $this->view('usuario/create', [
                    'error' => 'No se pudo crear el usuario'
                ]);
            }


        } else {
            $this->view('usuario/create');
        }

    }

    public function edit($id = null) {

        if(!$id){
            $this->redirect('/user/index');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = [
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'apellidos' => $_POST['apellidos'] ?? '',
                'correo' => $_POST['correo'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'ruta_imagen' => $_POST['ruta_imagen'] ?? '',
                'activo' => 1

            ];

            if(
                empty($data['username']) ||
                empty($data['nombre']) ||
                empty($data['correo'])
            ){
                $user = $this->userModel->getById($id);


                $this->view('usuario/edit',[
                    'user'=>$user,'error'=>'Usuario, nombre y correo son obligatorios']);
                return;
            }

            // Revisar correo duplicado
            $existingUser = $this->userModel->getByEmail($data['correo']);


            if($existingUser &&$existingUser['id_usuario'] != $id){
                $user = $this->userModel->getById($id);
                $this->view('usuario/edit',[
                    'user'=>$user, 'error'=>'El correo ya está siendo utilizado']);

                return;

            }

            // Actualizar usuario
            $resultado = $this->userModel->update($id,$data);

            if($resultado){

                $this->redirect('/user/index');

            }else{
                $user = $this->userModel->getById($id);
                $this->view('usuario/edit',['user'=>$user,'error'=>'No se pudo actualizar el usuario']);
            }



        }else{
            $user = $this->userModel->getById($id);

            if($user){
                $this->view('usuario/edit',['user'=>$user]);

            }else{
                $this->redirect('/user/index');
            }
        }

}

    public function delete($id = null) {
        if ($id) {
            // Evitar que el usuario se elimine a sí mismo
            if ($id != $_SESSION['user_id']) {
                $this->userModel->delete($id);
            }
        }
        $this->redirect('/user/index');
    }
}
