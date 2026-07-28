<?php require_once '../app/views/layouts/header.php'; ?>

<div class="container mt-4">

    <h2>Registrar Venta</h2>

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

                <option value="">
                    Seleccione una factura...
                </option>

                <?php foreach ($data['facturas'] as $factura) : ?>

                    <?php
                    $seleccionada =
                        (int) ($data['venta']['id_factura'] ?? 0) ===
                        (int) $factura['id_factura'];
                    ?>

                    <option
                        value="<?= $factura['id_factura']; ?>"
                        <?= $seleccionada ? 'selected' : ''; ?>
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

                <option value="">
                    Seleccione un producto...
                </option>

                <?php foreach ($data['productos'] as $producto) : ?>

                    <?php
                    $seleccionado =
                        (int) ($data['venta']['id_producto'] ?? 0) ===
                        (int) $producto['id_producto'];
                    ?>

                    <option
                        value="<?= $producto['id_producto']; ?>"
                        <?= $seleccionado ? 'selected' : ''; ?>
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
                    $data['venta']['cantidad'] ?? 1
                ); ?>"
                required
            >

        </div>

        <button
            type="submit"
            class="btn btn-success"
        >
            Guardar
        </button>

        <?php if (!empty($data['venta']['id_factura'])) : ?>

            <a
                href="<?= BASE_URL ?>/venta/index/<?= $data['venta']['id_factura']; ?>"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        <?php else : ?>

            <a
                href="<?= BASE_URL ?>/venta/index"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        <?php endif; ?>

    </form>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>