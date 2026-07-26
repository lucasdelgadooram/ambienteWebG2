<?php
require_once '../app/core/Controller.php';

class AuthController extends Controller {
    public function __construct() {
        session_start();
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/usuario/index');
        }
        $this->view('auth/login');
    }

public function login() {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = $this->model('User');
        $user = $userModel->getByUsername($username);

        if (!$user || !$user['activo']) {
            $this->view('auth/login', [ 'error' => 'Usuario o contraseña incorrectos']);
            return;
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['apellidos'] = $user['apellidos'];
            $_SESSION['roles'] = $userModel->getRoles($user['id_usuario']);
            $_SESSION['ruta_imagen'] = $user['ruta_imagen'];

            $this->redirect('/user/index');

        } else {
            $this->view('auth/login', ['error' => 'Usuario o contraseña incorrectos' ]);
        }

    } else {

        $this->redirect('/auth/index');
        }
}



    public function logout() {
        session_destroy();
        $this->redirect('/auth/index');
    }
}
