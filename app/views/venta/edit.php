<?php require_once '../app/views/layouts/header.php'; ?>

<div class="container mt-4">

    <h2>Editar Venta</h2>

    <?php if (!empty($data['error'])) : ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($data['error']); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">

            <label for="id_factura" class="form-label">
                Factura
            </label>

            <select
                name="id_factura"
                id="id_factura"
                class="form-control"
                required
            >

                <?php foreach ($data['facturas'] as $factura) : ?>

                    <option
                        value="<?= $factura['id_factura']; ?>"
                        <?= (int) $factura['id_factura'] ===
                            (int) $data['venta']['id_factura']
                            ? 'selected'
                            : ''; ?>
                    >
                        Factura #<?= $factura['id_factura']; ?>
                        -
                        <?= htmlspecialchars(
                            $factura['nombre'] . ' ' .
                            $factura['apellidos']
                        ); ?>
                        -
                        <?= htmlspecialchars($factura['estado']); ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="mb-3">

            <label for="id_producto" class="form-label">
                Producto
            </label>

            <select
                name="id_producto"
                id="id_producto"
                class="form-control"
                required
            >

                <?php foreach ($data['productos'] as $producto) : ?>

                    <option
                        value="<?= $producto['id_producto']; ?>"
                        <?= (int) $producto['id_producto'] ===
                            (int) $data['venta']['id_producto']
                            ? 'selected'
                            : ''; ?>
                    >
                        <?= htmlspecialchars(
                            $producto['descripcion']
                        ); ?>
                        -
                        ₡<?= number_format(
                            $producto['precio'],
                            2
                        ); ?>
                        -
                        Existencias:
                        <?= $producto['existencias']; ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="mb-3">

            <label for="cantidad" class="form-label">
                Cantidad
            </label>

            <input
                type="number"
                name="cantidad"
                id="cantidad"
                class="form-control"
                min="1"
                value="<?= htmlspecialchars(
                    $data['venta']['cantidad']
                ); ?>"
                required
            >

        </div>

        <button
            type="submit"
            class="btn btn-primary"
        >
            Actualizar
        </button>

        <a
            href="<?= BASE_URL ?>/venta/index/<?= $data['venta']['id_factura']; ?>"
            class="btn btn-secondary"
        >
            Cancelar
        </a>

    </form>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>