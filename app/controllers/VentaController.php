<?php

require_once '../app/core/Controller.php';

class VentaController extends Controller {
    private $ventaModel;
    private $facturaModel;
    private $productoModel;

    public function __construct() {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/index');
        }

        $this->ventaModel = $this->model('Venta');
        $this->facturaModel = $this->model('Factura');
        $this->productoModel = $this->model('Producto');
    }

    public function index($idFactura = null) {
        $factura = null;

        if ($idFactura) {
            $factura = $this->facturaModel->getById($idFactura);

            if (!$factura) {
                $this->redirect('/factura/index');
            }

            $ventas = $this->ventaModel->getByFactura($idFactura);
        } else {
            $ventas = $this->ventaModel->getAll();
        }

        $this->view('venta/index', [
            'ventas' => $ventas,
            'factura' => $factura,
            'id_factura' => $idFactura,
            'css' => [
                'indexStyles.css',
                'adminStyles.css'
            ]
        ]);
    }

    public function create($idFactura = null) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->buildVentaData();

            if ($error = $this->validateVentaData($data)) {
                $this->showCreateView($data, $error);
                return;
            }

            $factura = $this->facturaModel->getById(
                $data['id_factura']
            );

            if (!$factura) {
                $this->showCreateView(
                    $data,
                    'La factura seleccionada no existe.'
                );
                return;
            }

            if ($factura['estado'] === 'Anulada') {
                $this->showCreateView(
                    $data,
                    'No se pueden agregar productos a una factura anulada.'
                );
                return;
            }

            $productoExistente =
                $this->ventaModel->getByFacturaAndProducto(
                    $data['id_factura'],
                    $data['id_producto']
                );

            if ($productoExistente) {
                $this->showCreateView(
                    $data,
                    'El producto ya fue agregado a esta factura. Puedes editar su cantidad.'
                );
                return;
            }

            try {
                if ($this->ventaModel->create($data)) {
                    $this->redirect(
                        '/venta/index/' . $data['id_factura']
                    );
                }

                $this->showCreateView(
                    $data,
                    'No se pudo registrar la venta.'
                );
            } catch (Throwable $error) {
                $this->showCreateView(
                    $data,
                    $error->getMessage()
                );
            }

            return;
        }

        $data = [
            'id_factura' => (int) ($idFactura ?? 0),
            'id_producto' => 0,
            'cantidad' => 1
        ];

        $this->showCreateView($data);
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('/venta/index');
        }

        $ventaActual = $this->ventaModel->getById($id);

        if (!$ventaActual) {
            $this->redirect('/venta/index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->buildVentaData();

            if ($error = $this->validateVentaData($data)) {
                $data['id_venta'] = $id;

                $this->showEditView(
                    $data,
                    $error
                );

                return;
            }

            $factura = $this->facturaModel->getById(
                $data['id_factura']
            );

            if (!$factura) {
                $data['id_venta'] = $id;

                $this->showEditView(
                    $data,
                    'La factura seleccionada no existe.'
                );

                return;
            }

            if ($factura['estado'] === 'Anulada') {
                $data['id_venta'] = $id;

                $this->showEditView(
                    $data,
                    'No se pueden modificar productos de una factura anulada.'
                );

                return;
            }

            $productoExistente =
                $this->ventaModel->getByFacturaAndProducto(
                    $data['id_factura'],
                    $data['id_producto']
                );

            if (
                $productoExistente &&
                (int) $productoExistente['id_venta'] !== (int) $id
            ) {
                $data['id_venta'] = $id;

                $this->showEditView(
                    $data,
                    'El producto ya fue agregado a esta factura.'
                );

                return;
            }

            try {
                if ($this->ventaModel->update($id, $data)) {
                    $this->redirect(
                        '/venta/index/' . $data['id_factura']
                    );
                }

                $data['id_venta'] = $id;

                $this->showEditView(
                    $data,
                    'No se pudo actualizar la venta.'
                );
            } catch (Throwable $error) {
                $data['id_venta'] = $id;

                $this->showEditView(
                    $data,
                    $error->getMessage()
                );
            }

            return;
        }

        $this->showEditView($ventaActual);
    }

    public function delete($id = null) {
        if (!$id) {
            $this->redirect('/venta/index');
        }

        $venta = $this->ventaModel->getById($id);

        if (!$venta) {
            $this->redirect('/venta/index');
        }

        $idFactura = (int) $venta['id_factura'];

        try {
            $this->ventaModel->delete($id);
        } catch (Throwable $error) {
            $_SESSION['venta_error'] = $error->getMessage();
        }

        $this->redirect('/venta/index/' . $idFactura);
    }

    private function buildVentaData() {
        return [
            'id_factura' => (int) (
                $_POST['id_factura'] ?? 0
            ),
            'id_producto' => (int) (
                $_POST['id_producto'] ?? 0
            ),
            'cantidad' => (int) (
                $_POST['cantidad'] ?? 0
            )
        ];
    }

    private function validateVentaData($data) {
        if ($data['id_factura'] <= 0) {
            return 'Debes seleccionar una factura.';
        }

        if ($data['id_producto'] <= 0) {
            return 'Debes seleccionar un producto.';
        }

        if ($data['cantidad'] <= 0) {
            return 'La cantidad debe ser mayor que cero.';
        }

        return null;
    }

    private function showCreateView(
        $venta = [],
        $error = null
    ) {
        $viewData = [
            'venta' => $venta,
            'facturas' => $this->facturaModel->getAll(),
            'productos' => $this->productoModel->getAll(),
            'css' => ['UserFormularios.css']
        ];

        if ($error !== null) {
            $viewData['error'] = $error;
        }

        $this->view('venta/create', $viewData);
    }

    private function showEditView(
        $venta,
        $error = null
    ) {
        $viewData = [
            'venta' => $venta,
            'facturas' => $this->facturaModel->getAll(),
            'productos' => $this->productoModel->getAll(),
            'css' => ['UserFormularios.css']
        ];

        if ($error !== null) {
            $viewData['error'] = $error;
        }

        $this->view('venta/edit', $viewData);
    }
}