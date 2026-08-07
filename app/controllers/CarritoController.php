<?php

require_once '../app/core/Controller.php';

class CarritoController extends Controller {
    private $productoModel;
    private $compraModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $this->productoModel = $this->model('Producto');
        $this->compraModel = $this->model('Compra');
    }

    public function index() {
        $items = [];
        $total = 0;

        foreach ($_SESSION['carrito'] as $idProducto => $cantidad) {
            $producto = $this->productoModel->getById((int) $idProducto);

            if (!$producto) {
                unset($_SESSION['carrito'][$idProducto]);
                continue;
            }

            $producto['cantidad_carrito'] = (int) $cantidad;
            $producto['subtotal'] =
                (float) $producto['precio'] * (int) $cantidad;

            $total += $producto['subtotal'];
            $items[] = $producto;
        }

        $this->view('carrito/index', [
            'items' => $items,
            'total' => $total,
            'error' => $_SESSION['carrito_error'] ?? null,
            'success' => $_SESSION['carrito_success'] ?? null,
            'css' => ['carritoStyles.css']
        ]);

        unset(
            $_SESSION['carrito_error'],
            $_SESSION['carrito_success']
        );
    }

    public function agregar($idProducto = null) {
        $idProducto = (int) $idProducto;
        $producto = $this->productoModel->getById($idProducto);

        if (!$producto || (int) $producto['activo'] !== 1) {
            $_SESSION['carrito_error'] =
                'El producto no está disponible.';

            $this->redirect('/producto/catalogo');
        }

        $cantidadActual =
            (int) ($_SESSION['carrito'][$idProducto] ?? 0);

        if (
            $cantidadActual + 1 >
            (int) $producto['existencias']
        ) {
            $_SESSION['carrito_error'] =
                'No hay más existencias disponibles.';
        } else {
            $_SESSION['carrito'][$idProducto] =
                $cantidadActual + 1;

            $_SESSION['carrito_success'] =
                'Producto agregado al carrito.';
        }

        $this->redirect('/carrito/index');
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/carrito/index');
        }

        foreach (
            $_POST['cantidades'] ?? []
            as $idProducto => $cantidad
        ) {
            $idProducto = (int) $idProducto;
            $cantidad = (int) $cantidad;

            $producto =
                $this->productoModel->getById($idProducto);

            if ($cantidad <= 0) {
                unset($_SESSION['carrito'][$idProducto]);
            } elseif (
                $producto &&
                $cantidad <= (int) $producto['existencias']
            ) {
                $_SESSION['carrito'][$idProducto] = $cantidad;
            } else {
                $_SESSION['carrito_error'] =
                    'Una cantidad supera las existencias disponibles.';
            }
        }

        $this->redirect('/carrito/index');
    }

    public function eliminar($idProducto = null) {
        $idProducto = (int) $idProducto;

        unset($_SESSION['carrito'][$idProducto]);

        $this->redirect('/carrito/index');
    }

    public function vaciar() {
        $_SESSION['carrito'] = [];

        $this->redirect('/carrito/index');
    }

    public function finalizar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/carrito/index');
        }

        try {
            $idFactura = $this->compraModel->finalizar(
                (int) $_SESSION['user_id'],
                $_SESSION['carrito']
            );

            $_SESSION['carrito'] = [];

            $_SESSION['carrito_success'] =
                "Compra realizada. Factura #{$idFactura}.";
        } catch (Throwable $error) {
            $_SESSION['carrito_error'] =
                $error->getMessage();
        }

        $this->redirect('/carrito/index');
    }
}