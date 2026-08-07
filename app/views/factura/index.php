<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Gestión de Facturas</h2>

        <a href="<?= BASE_URL ?>/factura/create" class="btn btn-success">
            Nueva Factura
        </a>

    </div>

    <?php if (empty($data['facturas'])) : ?>

        <div class="alert alert-info">
            No existen facturas registradas.
        </div>

    <?php else : ?>

        <table class="table table-bordered table-hover">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Productos</th>
                    <th width="220">Acciones</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach($data['facturas'] as $factura): ?>

                <tr>

                    <td><?= $factura['id_factura']; ?></td>

                    <td>
                        <?= $factura['nombre']; ?>
                        <?= $factura['apellidos']; ?>
                    </td>

                    <td><?= $factura['fecha']; ?></td>

                    <td>
                        ₡ <?= number_format($factura['total'],2); ?>
                    </td>

                    <td><?= $factura['estado']; ?></td>

                    <td><?= $factura['cantidad_productos']; ?></td>

                    <td>

                        <a
                            href="<?= BASE_URL ?>/venta/index/<?= $factura['id_factura']; ?>"
                            class="btn btn-primary btn-sm">

                            Ventas

                        </a>

                        <a
                            href="<?= BASE_URL ?>/factura/edit/<?= $factura['id_factura']; ?>"
                            class="btn btn-warning btn-sm">

                            Editar

                        </a>

                        <a
                            href="<?= BASE_URL ?>/factura/delete/<?= $factura['id_factura']; ?>"
                            onclick="return confirm('¿Eliminar esta factura?')"
                            class="btn btn-danger btn-sm">

                            Eliminar

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>