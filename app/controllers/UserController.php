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

    // Cargar los roles para el formulario
    $rolModel = $this->model('Rol');
    $roles = $rolModel->getAll();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $data = [
            'username' => $_POST['username'] ?? '',
            'password' => $_POST['password'] ?? '',
            'nombre' => $_POST['nombre'] ?? '',
            'apellidos' => $_POST['apellidos'] ?? '',
            'correo' => $_POST['correo'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'ruta_imagen' => $_POST['ruta_imagen'] ?? '',
            'id_rol' => $_POST['id_rol'] ?? '',
            'activo' => 1
        ];

        // Validaciones básicas
        if (empty($data['username']) || empty($data['password']) || empty($data['nombre']) || empty($data['correo']) || empty($data['id_rol'])) {
            $this->view('usuario/create', [
                'error' => 'Los campos obligatorios deben estar llenos.',
                'roles' => $roles
            ]);
            return;
        }

        // Validar correo repetido
        if ($this->userModel->getByEmail($data['correo'])) {
            $this->view('usuario/create', [
                'error' => 'El correo ya está registrado.',
                'roles' => $roles
            ]);
            return;
        }

        // Validar username repetido
        if ($this->userModel->getByUsername($data['username'])) {
            $this->view('usuario/create', [
                'error' => 'El usuario ya existe.',
                'roles' => $roles
            ]);
            return;
        }

        // Crear usuario
        $resultado = $this->userModel->create($data);

        if ($resultado) {
            $this->redirect('/user/index');
        } else {
            $this->view('usuario/create', [
                'error' => 'No se pudo crear el usuario.',
                'roles' => $roles
            ]);
        }

    } else {

        $this->view('usuario/create', [
            'roles' => $roles
        ]);

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

    public function perfil(){
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
            return;
        }

        $usuario = $this->userModel->getById($_SESSION['user_id']);

        if (!$usuario) {
            die('Usuario no encontrado.');
        }

        $this->view('usuario/perfil', ['usuario' => $usuario]);
    }

    public function editarPerfil(){
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
            return;
        }

        $idUsuario = $_SESSION['user_id'];

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellidos' => trim($_POST['apellidos'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'ruta_imagen' => $_POST['ruta_imagen'] ?? '',
                'activo' => 1
            ];

            // Campos obligatorios
            if (empty($data['username']) || empty($data['nombre']) || empty($data['correo'])) {
                $usuario = $this->userModel->getById($idUsuario);
                $this->view('usuario/editPerfil', ['usuario' => $usuario, 'error' => 'Usuario, nombre y correo son obligatorios.']);
                return;
            }

            if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
                $usuario = $this->userModel->getById($idUsuario);
                $this->view('usuario/editPerfil', ['usuario' => $usuario, 'error' => 'Ingrese un correo electrónico válido y con formato.']);
                return;
            }

            $existingUser = $this->userModel->getByEmail($data['correo']);

            if ($existingUser &&  $existingUser['id_usuario'] != $idUsuario) {
                $usuario = $this->userModel->getById($idUsuario);
                $this->view('usuario/editPerfil', ['usuario' => $usuario,'error' => 'El correo ya está siendo utilizado. necesita otro' ]);
                return;
            }

           
            $existingUsername = $this->userModel->getByUsername($data['username']);
            if ($existingUsername && $existingUsername['id_usuario'] != $idUsuario) {
                $usuario = $this->userModel->getById($idUsuario);
                $this->view('usuario/editPerfil', ['usuario' => $usuario,'error' => 'El nombre de usuario ya está siendo utilizado, necesita otro.']);
                return;
            }

        
            $resultado = $this->userModel->update($idUsuario, $data);
            if ($resultado) {
                $_SESSION['username'] = $data['username'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['apellidos'] = $data['apellidos'];
                $_SESSION['ruta_imagen'] = $data['ruta_imagen'];

                $this->redirect('/user/perfil');
                return;
            }

            $usuario = $this->userModel->getById($idUsuario);

            $this->view('usuario/editPerfil', ['usuario' => $usuario, 'error' => 'No se pudo actualizar el perfil.']);
            return;
        }

       
        $usuario = $this->userModel->getById($idUsuario);
        if (!$usuario) {
            die('Usuario no encontrado.');
        }

        $this->view('usuario/editPerfil', [ 'usuario' => $usuario]);
    }
}
