
<?php

require_once '../app/core/Controller.php';

class AuthController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(){
        if (isset($_SESSION['user_id'])) {
            if ($this->tieneRol('ADMIN')) {
                $this->redirect('/user/index');
            } else {
                $this->redirect('/home/about');
            }
            return;
        }

        $this->view('auth/login');
    }

    public function login(){

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('User');
            $user = $userModel->getByUsername($username);

            if (!$user || !$user['activo']) {
                $this->view('auth/login', ['error' => 'Usuario o contraseña incorrectos']);
                return;
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellidos'] = $user['apellidos'];

                $_SESSION['roles'] = $userModel->getRoles($user['id_usuario']);
                $_SESSION['ruta_imagen'] = $user['ruta_imagen'];

                if ($this->tieneRol('ADMIN')) {
                    $this->redirect('/user/index');
                } else {
                    $this->redirect('/home/about');
                }
                return;
            }

            $this->view('auth/login', [
                'error' => 'Usuario o contraseña incorrectos'
            ]);

            return;
        }

        $this->redirect('/auth/index');
    }

    public function logout(){
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(),'', time() - 42000,$params['path'],$params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();

        $this->redirect('/auth/index');
    }


   
    // Mostrar formulario de correo
    public function registro(){
        $this->view('auth/registroCorreo');
    }

    // Recibir correo y enviar enlace
    public function solicitarRegistro(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->redirect('/auth/registro');
            return;
        }

        $correo = trim($_POST['correo'] ?? '');

        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {

            $this->view('auth/registroCorreo', ['error' => 'Ingrese un correo electrónico válido.']);
            return;
        }

        $userModel = $this->model('User');

        // Verificar si el correo ya existe
        if ($userModel->existeCorreo($correo)) {
            $this->view('auth/registroCorreo', ['error' => 'Ese correo ya está registrado.']);
            return;
        }

        
        $token = bin2hex(random_bytes(32));
        $tokenModel = $this->model('RegistroToken');

        if (!$tokenModel->guardarToken($correo, $token)) {

            $this->view('auth/registroCorreo', ['error' => 'No se pudo generar el registro.']);
            return;
        }

        // Cargar servicio de correo
        require_once '../app/services/MailService.php';
        $mail = new MailService();

        // Enviar correo
        if (!$mail->enviarCorreoRegistro($correo, $token)) {
            $this->view('auth/registroCorreo', [ 'error' => 'El token se guardó, pero no se pudo enviar el correo.' ]);
            return;
        }

        $this->view('auth/registroCorreo', ['success' => 'Se envió un correo de verificación. Revise su correo electrónico.']);
    }

    //Tokern, verificar cantida, tiempo, existencia, enalace
    public function verificar(){
        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            die('Token inválido.');
        }

        $tokenModel = $this->model('RegistroToken');
        $registro = $tokenModel->getByToken($token);

        if (!$registro) {
            die('El enlace de registro no existe.');
        }

        if ($registro['utilizado']) {
            die('Este enlace ya fue utilizado.');
        }

        if (strtotime($registro['fecha_expiracion']) < time()) {
            die('El enlace de registro ha expirado.');
        }

        // Guardar datos temporalmente en sesión
        $_SESSION['correo_registro'] = $registro['correo'];
        $_SESSION['token_registro'] = $token;

        // Mostrar formulario completo
        $this->redirect('/auth/registroUsuario');
    }

    //formularioRegistro
    public function registroUsuario(){
        if (!isset($_SESSION['correo_registro']) ||  !isset($_SESSION['token_registro'])) {
            $this->redirect('/auth/registro');
            return;
        }

        $this->view('auth/registroUsuario', ['correo' => $_SESSION['correo_registro'],'token' => $_SESSION['token_registro']]);
    }

    // Crear usuario CLIENTE
    public function registrarUsuario(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->redirect('/auth/registroUsuario');
            return;
        }

        if (!isset($_SESSION['correo_registro']) || !isset($_SESSION['token_registro'])) {
            $this->redirect('/auth/registro');
            return;
        }

        // Datos registro
        $correo = $_SESSION['correo_registro'];
        $token = $_SESSION['token_registro'];
        $username = trim($_POST['username'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($username) || empty($nombre) || empty($apellidos) || empty($password) || empty($passwordConfirm) ) {

            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'Complete todos los campos obligatorios.']);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'Las contraseñas no coinciden.']);
            return;
        }

        $tokenModel = $this->model('RegistroToken');
        $registro = $tokenModel->getByToken($token);

        if (!$registro) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'El enlace de registro no es válido.']);
            return;
        }

        if ($registro['utilizado']) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'Este enlace ya fue utilizado.']);
            return;
        }


        // Token expirado
        if (strtotime($registro['fecha_expiracion']) < time()) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'El enlace de registro ha expirado.']);
            return;
        }
     
        $userModel = $this->model('User');

        if ($userModel->getByUsername($username)) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'Ese nombre de usuario ya existe.']);
            return;
        }

        if ($userModel->existeCorreo($correo)) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'Ese correo ya está registrado.']);
            return;
        }

        $data = [
            'username' => $username,
            'password' => $password,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'correo' => $correo,
            'telefono' => $telefono,
            'ruta_imagen' => '',
            'activo' => 1
        ];

        //Crear Uusario
        $resultado = $userModel->createUser($data);

        if (!$resultado) {
            $this->view('auth/registroUsuario', ['correo' => $correo,'token' => $token,'error' => 'No se pudo crear la cuenta. Verifique que el usuario y los datos sean válidos.']);
            return;
        }
        $tokenModel->marcarUtilizado(
            $registro['id_token']
        );
        unset($_SESSION['correo_registro']);
        unset($_SESSION['token_registro']);

        $this->view('auth/login', [
            'success' => 'Cuenta creada correctamente. Ya puedes iniciar sesión.'
        ]);
    }

    public function olvidar(){
        $this->view('auth/forgotPassword');
    }

    public function recuperarSoli(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->redirect('/auth/forgotPassword');
            return;
        }

        $correo = trim($_POST['correo'] ?? '');

        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {

            $this->view('auth/forgotPassword', ['error' => 'Ingrese un correo electrónico válido.']);
            return;
        }

        $userModel = $this->model('User');
        $usuario = $userModel->getByEmail($correo);

        if (!$usuario) {
            $this->view('auth/forgotPassword', [ 'error' => 'No existe una cuenta registrada con ese correo.']);

            return;
        }

      
        $token = bin2hex(random_bytes(32));
        $tokenModel = $this->model('RecuperacionToken');

        if (!$tokenModel->guardarToken($usuario['id_usuario'], $token )) {
            $this->view('auth/forgotPassword', [ 'error' => 'No se pudo generar el enlace de recuperación.' ]);
            return;
        }

        require_once '../app/services/MailService.php';
        $mail = new MailService();

        // Enviar 
        if (!$mail->enviarCorreoRecuperacion($correo, $token)) {
            $this->view('auth/forgotPassword', ['error' => 'El token se guardó, pero no se pudo enviar el correo.']);
            return;
        }

        $this->view('auth/forgotPassword', [ 'success' =>'Se envió un correo de recuperación. Revise su correo electrónico.']);
    }

    public function cambiarPassword(){
        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            die('Token inválido.');
        }

        $tokenModel = $this->model('RecuperacionToken');
        $recuperacion = $tokenModel->getByToken($token);

        if (!$recuperacion) {
            die('El enlace de recuperación no existe, ya fue utilizado o ha vencido.');
        }

        $this->view('auth/resetPassword', [
            'token' => $token
        ]);
    }

    public function actualizarPassword(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/forgotPassword');
            return;
        }

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token)) {
            die('Token inválido.');
        }

        if (empty($password) || empty($passwordConfirm)) {
            $this->view('auth/resetPassword', ['token' => $token,'error' => 'Complete todos los campos.']);
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->view('auth/resetPassword', ['token' => $token, 'error' => 'Las contraseñas no coinciden.' ]);
            return;
        }

       
        $tokenModel = $this->model('RecuperacionToken');
        $recuperacion = $tokenModel->getByToken($token);

        if (!$recuperacion) {
            die('El enlace de recuperación no existe, ' . 'ya fue utilizado o ha vencido.');
        }

        
        $userModel = $this->model('User');

        $resultado = $userModel->updatePassword( $recuperacion['id_usuario'],$password);
        if (!$resultado) {
            $this->view('auth/resetPassword', ['token' => $token, 'error' => 'No se pudo actualizar la contraseña.']);
            return;
        }

        
        $tokenModel->marcarUtilizado(
            $recuperacion['id_recuperacion']
        );

        $this->view('auth/login', ['success' => 'Contraseña actualizada correctamente. ' .'Ya puedes iniciar sesión.']);
    }


}

