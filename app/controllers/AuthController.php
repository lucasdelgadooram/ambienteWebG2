<?php

require_once '../app/core/Controller.php';

class AuthController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            
        }
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/user/index');
        }

        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('User');
            $user = $userModel->getByUsername($username);

            if (!$user || !$user['activo']) {
                $this->view('auth/login', [
                    'error' => 'Usuario o contraseña incorrectos'
                ]);
                return;
            }

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellidos'] = $user['apellidos'];
                $_SESSION['roles'] = $userModel->getRoles(
                    $user['id_usuario']
                );
                $_SESSION['ruta_imagen'] = $user['ruta_imagen'];

                $this->redirect('/user/index');
            }

            $this->view('auth/login', [
                'error' => 'Usuario o contraseña incorrectos'
            ]);

            return;
        }

        $this->redirect('/auth/index');
    }

    public function logout() {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(session_name(),'',time() - 42000, $params['path'],$params['domain'],$params['secure'],$params['httponly'] );
        }

        session_destroy();

        $this->redirect('/auth/index');
    }

    public function solicitarRegistro(){

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->redirect('/auth/registro');
        }

        $correo = trim($_POST['correo']);
        $userModel = $this->model('User');

        if($userModel->existeCorreo($correo)){
            $this->view('auth/registroCorreo',['error'=>'Ese correo ya está registrado.']);
            return;
        }

        //https://www.php.net/manual/es/function.bin2hex.php
        $token = bin2hex(random_bytes(32));
        $tokenModel = $this->model('RegistroToken');
        $tokenModel->guardarToken($correo, $token);

        require_once '../app/services/MailService.php';

        $mail = new MailService();
        $mail->enviarCorreoRegistro( $correo, $token);

        //https://www.php.net/manual/es/function.mail.php
        $this->view('auth/registroCorreo',['success'=>'Se envió un correo de verificación.']);

    }

    public function verificar(){

        $token = $_GET['token'] ?? '';

        if(empty($token)){
            die("Token inválido.");
        }   

        $tokenModel = $this->model('RegistroToken');
        $registro = $tokenModel->getByToken($token);

        if(!$registro){
            die("El enlace no existe.");
        }

        if($registro['utilizado']){
            die("Este enlace ya fue utilizado.");
        }

        if(strtotime($registro['fecha_expiracion']) < time()){
            die("El enlace ha expirado.");
        }

        // Marcar el token como utilizado
        $tokenModel->marcarUtilizado($registro['id_token']);
        $_SESSION['correo_registro'] = $registro['correo'];

        // Ir al formulario de registro
        $this->redirect('/auth/registroUsuario');

    }

    public function registro(){

        $this->view('auth/registroCorreo');

    }

    public function registroUsuario(){

        if(!isset($_GET['token'])){
            die("Token inválido.");
        }

        $token = $_GET['token'];
        $tokenModel = $this->model('RegistroToken');
        $registro = $tokenModel->getByToken($token);

        if(!$registro){
            die("El enlace ya expiró o no es válido.");
        }

        $this->view('auth/registroUsuario',['correo' => $registro['correo'],'token' => $token]);

    }

    public function registrarUsuario(){

        if(!isset($_SESSION['correo_registro'])){
            $this->redirect('/auth/registro');
        }

        if($_SERVER['REQUEST_METHOD'] != 'POST'){
            $this->redirect('/auth/registroUsuario');
        }

        $userModel = $this->model('User');

        $data = [
            'username'      => trim($_POST['username']),
            'password'      => $_POST['password'],
            'nombre'        => trim($_POST['nombre']),
            'apellidos'     => trim($_POST['apellidos']),
            'correo'        => $_SESSION['correo_registro'],
            'telefono'      => trim($_POST['telefono']),
            'ruta_imagen'   => $_POST['ruta_imagen'] ?? '',
            'activo'        => 1
        ];

        if(empty($data['username']) ||empty($data['password']) ||empty($data['nombre']) || empty($data['apellidos'])){
                
            $this->view('auth/registroUsuario',['correo'=>$data['correo'],'error'=>'Complete todos los campos obligatorios.' ]);
            return;
        }

        if($userModel->getByUsername($data['username'])){

            $this->view('auth/registroUsuario',['correo'=>$data['correo'],'error'=>'Ese nombre de usuario ya existe.']);
            return;
        }

        if($_POST['password'] != $_POST['confirmar_password']){

            $this->view('auth/registroUsuario',['correo'=>$data['correo'],'error'=>'Las contraseñas no coinciden.']);
            return;
        }

        $resultado = $userModel->registrarCliente($data);

        if($resultado){

            unset($_SESSION['correo_registro']);
            $this->redirect('/auth/index');

        }else{

            $this->view('auth/registroUsuario',['correo'=>$data['correo'],'error'=>'No se pudo crear la cuenta.' ]);

        }

    }
}