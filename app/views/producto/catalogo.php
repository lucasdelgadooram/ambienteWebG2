<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main class="container py-5">

    <div
        class="d-flex justify-content-between
        align-items-center mb-4"
    >
        <h1>Catálogo</h1>

        <a
            class="btn btn-outline-dark"
            href="<?= BASE_URL ?>/carrito/index"
        >
            Ver carrito
        </a>
    </div>

    <?php if (empty($data['productos'])): ?>

        <div class="alert alert-info">
            No hay productos disponibles.
        </div>

    <?php else: ?>

        <div class="row g-4">

            <?php foreach (
                $data['productos'] as $producto
            ): ?>

                <div class="col-sm-6 col-lg-4">

                    <div class="card h-100 shadow-sm">

                        <?php if (
                            !empty($producto['ruta_imagen'])
                        ): ?>

                            <img
                                class="card-img-top"
                                style="
                                    height: 240px;
                                    object-fit: cover;
                                "
                                src="<?=
                                    BASE_URL
                                ?>/<?=
                                    htmlspecialchars(
                                        $producto['ruta_imagen']
                                    )
                                ?>"
                                alt="<?=
                                    htmlspecialchars(
                                        $producto['descripcion']
                                    )
                                ?>"
                            >

                        <?php endif; ?>

                        <div
                            class="card-body d-flex
                            flex-column"
                        >

                            <h5>
                                <?= htmlspecialchars(
                                    $producto['descripcion']
                                ) ?>
                            </h5>

                            <p>
                                <?= htmlspecialchars(
                                    $producto['detalle'] ?? ''
                                ) ?>
                            </p>

                            <p class="mb-1">

                                <strong>
                                    ₡<?= number_format(
                                        $producto['precio'],
                                        2
                                    ) ?>
                                </strong>

                            </p>

                            <small class="mb-3">
                                Existencias:
                                <?= (int)
                                    $producto['existencias']
                                ?>
                            </small>

                            <a
                                class="btn btn-primary mt-auto"
                                href="<?=
                                    BASE_URL
                                ?>/carrito/agregar/<?=
                                    (int)
                                    $producto['id_producto']
                                ?>"
                            >
                                Agregar al carrito
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

<?php require_once '../app/views/layouts/footer.php'; ?>