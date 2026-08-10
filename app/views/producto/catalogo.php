<?php 
require_once __DIR__ . '/../layouts/head.php'; 
?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="catalogo-page">

    <section class="catalogo-hero">
        <div>
            <p class="subtitulo">Productos personalizados</p>
            <h1>Catálogo Paluse</h1>
            <p class="descripcion">
                Explora nuestros productos personalizados y encuentra el regalo perfecto.
                <?php if (isset($data['categoriaNombre']) && !empty($data['categoriaNombre'])): ?>
                    <br><strong>Mostrando: <?= htmlspecialchars($data['categoriaNombre']) ?></strong>
                <?php endif; ?>
                <?php if (isset($data['busqueda']) && !empty($data['busqueda'])): ?>
                    <br><strong>Resultados para: "<?= htmlspecialchars($data['busqueda']) ?>"</strong>
                <?php endif; ?>
            </p>
        </div>
    </section>

    <section class="productos-grid">
        <?php if (empty($data['productos'])): ?>
            <div class="sin-productos">
                <i class="fa-regular fa-face-frown"></i>
                <h3>No hay productos disponibles</h3>
                <p>Pronto tendremos nuevos productos en esta categoría.</p>
                <a href="<?= BASE_URL ?>/producto/catalogo" class="btn-volver">
                    Ver todos los productos
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($data['productos'] as $producto): ?>
                <article class="producto-card">
                    <div class="producto-header"></div>
                    <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                         alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                    <div class="producto-body">
                        <h3><?= htmlspecialchars($producto['descripcion']) ?></h3>
                        <p><?= htmlspecialchars(substr($producto['detalle'] ?? '', 0, 80)) ?>...</p>
                        <span class="estado"><?= $producto['existencias'] > 0 ? 'Disponible' : 'Agotado' ?></span>
                        <div class="producto-footer">
                            <strong>
                                ₡<?= number_format($producto['precio'], 0, ',', '.') ?>
                            </strong>
                            <div>
                                <button class="btn-favorito" data-id="<?= $producto['id_producto'] ?>">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <a href="<?= BASE_URL ?>/producto/detalle/<?= $producto['id_producto'] ?>">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/carrito/agregar/<?= $producto['id_producto'] ?>" class="btn-carrito">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>