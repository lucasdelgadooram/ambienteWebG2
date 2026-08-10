<?php require_once APPROOT . '/views/layouts/head.php'; ?>
<?php require_once APPROOT . '/views/layouts/header.php'; ?>

<main class="ofertas-page">

    <section class="ofertas-hero">
        <div class="hero-contenido">
            <span class="hero-etiqueta"><i class="fa-solid fa-bolt"></i> Promociones activas</span>
            <h1>Ofertas y Promociones</h1>
            <p class="hero-descripcion">
                Aprovecha nuestros descuentos especiales y lleva los mejores productos personalizados al mejor precio.
                ¡Ofertas por tiempo limitado!
            </p>
        </div>
    </section>

    <section class="ofertas-grid">
        <?php if (empty($data['productos'])): ?>
            <div class="sin-productos">
                <i class="fa-regular fa-face-frown"></i>
                <h3>No hay ofertas disponibles</h3>
                <p>Pronto tendremos nuevas promociones. ¡Síguenos en redes sociales para enterarte primero!</p>
                <a href="<?= BASE_URL ?>/producto/catalogo" class="btn-volver">
                    Ver catálogo completo
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($data['productos'] as $producto): ?>
                <article class="producto-oferta">
                    <div class="producto-oferta-imagen">
                        <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                             alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                        <span class="badge-descuento">-<?= $producto['porcentaje_descuento'] ?? 20 ?>%</span>
                    </div>
                    <div class="producto-oferta-body">
                        <h3><?= htmlspecialchars($producto['descripcion']) ?></h3>
                        <p><?= htmlspecialchars(substr($producto['detalle'] ?? '', 0, 60)) ?>...</p>
                        <div class="precios-oferta">
                            <span class="precio-original">₡<?= number_format($producto['precio'], 0, ',', '.') ?></span>
                            <span class="precio-oferta">₡<?= number_format($producto['precio_oferta'] ?? ($producto['precio'] * 0.8), 0, ',', '.') ?></span>
                        </div>
                        <div class="producto-oferta-footer">
                            <span class="stock-disponible"><i class="fa-solid fa-check-circle"></i> Disponible</span>
                            <div class="acciones-oferta">
                                <a href="<?= BASE_URL ?>/producto/detalle/<?= $producto['id_producto'] ?>" class="btn-ver">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/carrito/agregar/<?= $producto['id_producto'] ?>" class="btn-carrito-oferta">
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

<?php require_once APPROOT . '/views/layouts/footer.php'; ?>