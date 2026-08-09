<?php

require_once '../app/core/Controller.php';

class ContactoController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $this->redirect('/contacto/redes');
    }

    public function redes() {
        $this->view('contacto/redes', [
            'css' => ['contactoRedesStyles.css']
        ]);
    }

    public function formulario() {
        $mensajeContactoModel = $this->model('MensajeContacto');

        $data = [
            'css' => ['contactoStyles.css', 'formularioContactoStyles.css'],
            'values' => [
                'nombre' => '',
                'correo' => '',
                'telefono' => '',
                'consulta' => ''
            ]
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['values'] = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'consulta' => trim($_POST['consulta'] ?? '')
            ];

            if (
                $data['values']['nombre'] === '' ||
                $data['values']['correo'] === '' ||
                $data['values']['consulta'] === ''
            ) {
                $data['error'] = 'Completa los campos obligatorios para enviar tu consulta.';
                $this->view('contacto/formulario', $data);
                return;
            }

            if (!filter_var($data['values']['correo'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'El correo electrónico no es válido.';
                $this->view('contacto/formulario', $data);
                return;
            }

            if (!$mensajeContactoModel->create($data['values'])) {
                $data['error'] = 'No se pudo enviar tu consulta. Intenta nuevamente.';
                $this->view('contacto/formulario', $data);
                return;
            }

            $data['success'] = 'Recibimos tu mensaje. Pronto te estaremos contactando.';
            $data['values'] = [
                'nombre' => '',
                'correo' => '',
                'telefono' => '',
                'consulta' => ''
            ];
        }

        $this->view('contacto/formulario', $data);
    }
}