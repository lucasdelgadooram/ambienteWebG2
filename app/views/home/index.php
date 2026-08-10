<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="home-page">

    <section class="home-carrusel">
        <div class="carrusel-container">
            <div class="carrusel-track" id="carruselTrack">
                <?php if (!empty($data['productosRecientes'])): ?>
                    <?php foreach ($data['productosRecientes'] as $index => $producto): ?>
                        <div class="carrusel-slide <?= $index === 0 ? 'active' : '' ?>">
                            <div class="carrusel-content">
                                <div class="carrusel-texto">
                                    <span class="carrusel-etiqueta">Nuevo producto</span>
                                    <h2><?= htmlspecialchars($producto['descripcion']) ?></h2>
                                    <p><?= htmlspecialchars(substr($producto['detalle'] ?? '', 0, 120)) ?>...</p>
                                    <div class="carrusel-precio">
                                        ₡<?= number_format($producto['precio'], 0, ',', '.') ?>
                                    </div>
                                    <a href="<?= BASE_URL ?>/producto/detalle/<?= $producto['id_producto'] ?>" class="btn-carrusel">
                                        Ver producto <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="carrusel-imagen">
                                    <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                                         alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carrusel-slide active">
                        <div class="carrusel-content">
                            <div class="carrusel-texto">
                                <span class="carrusel-etiqueta">Bienvenido a Paluse</span>
                                <h2>Productos personalizados</h2>
                                <p>Explora nuestra colección de productos únicos y personalizados.</p>
                                <a href="<?= BASE_URL ?>/producto/catalogo" class="btn-carrusel">
                                    Ver catálogo <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="carrusel-imagen">
                                <img src="<?= BASE_URL ?>/resources/logoPaluseNew.png" alt="Paluse">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <button class="carrusel-btn carrusel-prev" id="carruselPrev">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="carrusel-btn carrusel-next" id="carruselNext">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="carrusel-indicadores" id="carruselIndicadores">
                <?php if (!empty($data['productosRecientes'])): ?>
                    <?php foreach ($data['productosRecientes'] as $index => $producto): ?>
                        <span class="indicador <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>"></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="indicador active" data-index="0"></span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="home-categorias">
        <div class="seccion-header">
            <h2>Categorías</h2>
            <a href="<?= BASE_URL ?>/producto/catalogo">Ver todas <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="categorias-grid">
            <?php if (!empty($data['categorias'])): ?>
                <?php foreach ($data['categorias'] as $categoria): ?>
                    <?php if ($categoria['activo'] == 1): ?>
                        <a href="<?= BASE_URL ?>/producto/categoria/<?= urlencode($categoria['descripcion']) ?>" class="categoria-card">
                            <div class="categoria-icono">
                                <i class="fa-solid fa-<?= $this->getCategoriaIcono($categoria['descripcion']) ?>"></i>
                            </div>
                            <h3><?= htmlspecialchars($categoria['descripcion']) ?></h3>
                            <span>Ver productos →</span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="home-productos">
        <div class="seccion-header">
            <h2>Productos destacados</h2>
            <a href="<?= BASE_URL ?>/producto/catalogo">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="productos-grid">
            <?php if (!empty($data['productosRecientes'])): ?>
                <?php 
                $destacados = array_slice($data['productosRecientes'], 0, 4);
                foreach ($destacados as $producto): 
                ?>
                    <article class="producto-card">
                        <div class="producto-header"></div>
                        <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                             alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                        <div class="producto-body">
                            <h3><?= htmlspecialchars($producto['descripcion']) ?></h3>
                            <p><?= htmlspecialchars(substr($producto['detalle'] ?? '', 0, 60)) ?>...</p>
                            <span class="estado"><?= $producto['existencias'] > 0 ? 'Disponible' : 'Agotado' ?></span>
                            <div class="producto-footer">
                                <strong>₡<?= number_format($producto['precio'], 0, ',', '.') ?></strong>
                                <div>
                                    <a href="<?= BASE_URL ?>/producto/detalle/<?= $producto['id_producto'] ?>" class="btn-ver">
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
            <?php else: ?>
                <div class="sin-productos">
                    <i class="fa-regular fa-face-frown"></i>
                    <h3>No hay productos disponibles</h3>
                    <p>Pronto tendremos nuevos productos.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="home-ofertas-banner">
        <div class="ofertas-banner-content">
            <div class="ofertas-banner-texto">
                <span class="ofertas-etiqueta"><i class="fa-solid fa-bolt"></i> Ofertas especiales</span>
                <h2>¡Aprovecha nuestras promociones!</h2>
                <p>Productos personalizados con descuentos increíbles por tiempo limitado.</p>
                <a href="<?= BASE_URL ?>/producto/ofertas" class="btn-ofertas">
                    Ver ofertas <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="ofertas-banner-imagen">
                <i class="fa-solid fa-percent"></i>
            </div>
        </div>
    </section>

    <section class="home-suscripcion">
        <div class="suscripcion-content">
            <div class="suscripcion-texto">
                <i class="fa-regular fa-bell"></i>
                <div>
                    <h2>¡No te pierdas nada!</h2>
                    <p>Suscríbete para recibir ofertas exclusivas y novedades.</p>
                </div>
            </div>
            <form class="suscripcion-form" action="<?= BASE_URL ?>/home/suscribir" method="POST">
                <input type="email" name="email" placeholder="Tu correo electrónico" required>
                <button type="submit">Suscribirme</button>
            </form>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>