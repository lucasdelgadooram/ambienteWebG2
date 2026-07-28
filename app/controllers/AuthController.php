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

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        $this->redirect('/auth/index');
    }
}