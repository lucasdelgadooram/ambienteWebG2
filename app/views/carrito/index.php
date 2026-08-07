<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container py-5">

    <div
        class="d-flex justify-content-between
        align-items-center mb-4"
    >
        <h1>Mi carrito</h1>

        <a
            class="btn btn-outline-primary"
            href="<?= BASE_URL ?>/producto/catalogo"
        >
            Seguir comprando
        </a>
    </div>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($data['error']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($data['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($data['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($data['items'])): ?>

        <div class="alert alert-info">
            El carrito está vacío.
        </div>

    <?php else: ?>

        <form
            action="<?= BASE_URL ?>/carrito/actualizar"
            method="POST"
        >

            <div class="table-responsive">

                <table
                    class="table table-bordered align-middle"
                >

                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach (
                        $data['items'] as $item
                    ): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    $item['descripcion']
                                ) ?>
                            </td>

                            <td>
                                ₡<?= number_format(
                                    $item['precio'],
                                    2
                                ) ?>
                            </td>

                            <td style="max-width: 130px;">

                                <input
                                    class="form-control"
                                    type="number"
                                    min="1"
                                    max="<?=
                                        (int) $item['existencias']
                                    ?>"
                                    name="cantidades[
                                        <?= (int)
                                            $item['id_producto']
                                        ?>
                                    ]"
                                    value="<?=
                                        (int)
                                        $item['cantidad_carrito']
                                    ?>"
                                >

                            </td>

                            <td>
                                ₡<?= number_format(
                                    $item['subtotal'],
                                    2
                                ) ?>
                            </td>

                            <td>

                                <a
                                    class="btn btn-danger btn-sm"
                                    href="<?=
                                        BASE_URL
                                    ?>/carrito/eliminar/<?=
                                        (int)
                                        $item['id_producto']
                                    ?>"
                                >
                                    Eliminar
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

            <div
                class="d-flex justify-content-between
                align-items-center mb-3"
            >

                <a
                    class="btn btn-outline-danger"
                    href="<?= BASE_URL ?>/carrito/vaciar"
                >
                    Vaciar carrito
                </a>

                <button
                    class="btn btn-secondary"
                    type="submit"
                >
                    Actualizar cantidades
                </button>

            </div>

        </form>

        <div class="text-end">

            <h3>
                Total:
                ₡<?= number_format(
                    $data['total'],
                    2
                ) ?>
            </h3>

            <form
                action="<?= BASE_URL ?>/carrito/finalizar"
                method="POST"
                onsubmit="
                    return confirm(
                        '¿Deseas finalizar la compra?'
                    );
                "
            >

                <button
                    class="btn btn-success btn-lg"
                    type="submit"
                >
                    Finalizar compra
                </button>

            </form>

        </div>

    <?php endif; ?>

</main>

<?php require_once '../app/views/layouts/footer.php'; ?>