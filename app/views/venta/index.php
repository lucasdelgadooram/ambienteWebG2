<?php require_once '../app/views/layouts/header.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h2>
                <?= !empty($data['factura'])
                    ? 'Detalle de la Factura #' . $data['factura']['id_factura']
                    : 'Gestión de Ventas'; ?>
            </h2>

            <?php if (!empty($data['factura'])) : ?>
                <p class="mb-0">
                    Cliente:
                    <strong>
                        <?= htmlspecialchars(
                            $data['factura']['nombre'] . ' ' .
                            $data['factura']['apellidos']
                        ); ?>
                    </strong>
                </p>

                <p>
                    Total:
                    <strong>
                        ₡<?= number_format(
                            $data['factura']['total'],
                            2
                        ); ?>
                    </strong>
                </p>
            <?php endif; ?>
        </div>

        <div>
            <?php if (!empty($data['id_factura'])) : ?>

                <a
                    href="<?= BASE_URL ?>/venta/create/<?= $data['id_factura']; ?>"
                    class="btn btn-success"
                >
                    Agregar Producto
                </a>

                <a
                    href="<?= BASE_URL ?>/factura/index"
                    class="btn btn-secondary"
                >
                    Volver a Facturas
                </a>

            <?php else : ?>

                <a
                    href="<?= BASE_URL ?>/venta/create"
                    class="btn btn-success"
                >
                    Nueva Venta
                </a>

            <?php endif; ?>
        </div>

    </div>

    <?php if (!empty($_SESSION['venta_error'])) : ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['venta_error']); ?>
        </div>

        <?php unset($_SESSION['venta_error']); ?>

    <?php endif; ?>

    <?php if (empty($data['ventas'])) : ?>

        <div class="alert alert-info">
            No existen ventas registradas.
        </div>

    <?php else : ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                    <tr>
                        <th>ID</th>

                        <?php if (empty($data['factura'])) : ?>
                            <th>Factura</th>
                            <th>Cliente</th>
                        <?php endif; ?>

                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th width="170">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($data['ventas'] as $venta) : ?>

                        <tr>

                            <td>
                                <?= $venta['id_venta']; ?>
                            </td>

                            <?php if (empty($data['factura'])) : ?>

                                <td>
                                    #<?= $venta['id_factura']; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $venta['nombre'] . ' ' .
                                        $venta['apellidos']
                                    ); ?>
                                </td>

                            <?php endif; ?>

                            <td>
                                <?= htmlspecialchars(
                                    $venta['producto_descripcion']
                                ); ?>
                            </td>

                            <td>
                                ₡<?= number_format(
                                    $venta['precio_historico'],
                                    2
                                ); ?>
                            </td>

                            <td>
                                <?= $venta['cantidad']; ?>
                            </td>

                            <td>
                                ₡<?= number_format(
                                    $venta['subtotal'],
                                    2
                                ); ?>
                            </td>

                            <td>

                                <a
                                    href="<?= BASE_URL ?>/venta/edit/<?= $venta['id_venta']; ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    Editar
                                </a>

                                <a
                                    href="<?= BASE_URL ?>/venta/delete/<?= $venta['id_venta']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Seguro que deseas eliminar esta venta?');"
                                >
                                    Eliminar
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>