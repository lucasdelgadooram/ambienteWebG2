<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="catalogo-page">

    <a href="<?= BASE_URL ?>/producto/catalogo" class="volver-link">
        <i class="fa-solid fa-arrow-left"></i> Volver al catálogo
    </a>

    <?php if (!empty($data['producto'])): ?>
        <?php $producto = $data['producto']; ?>
        <section class="detalle-producto">

            <div class="detalle-imagen">
                <div class="producto-header"></div>
                <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                     alt="<?= htmlspecialchars($producto['descripcion']) ?>">
            </div>

            <div class="detalle-info">
                <p class="subtitulo">Producto personalizado</p>
                <h1><?= htmlspecialchars($producto['descripcion']) ?></h1>

                <div class="estrellas">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <span> 5.0</span>
                </div>

                <p class="precio-detalle">₡<?= number_format($producto['precio'], 0, ',', '.') ?></p>
                <p class="estado detalle-estado"><?= $producto['existencias'] > 0 ? 'Disponible' : 'Agotado' ?></p>

                <p class="descripcion-detalle">
                    <?= htmlspecialchars($producto['detalle'] ?? 'Producto personalizado de alta calidad.') ?>
                </p>

                <div class="cantidad-box">
                    <span>Cantidad:</span>
                    <div>
                        <button onclick="cambiarCantidad(-1)">-</button>
                        <strong id="cantidadProducto">1</strong>
                        <button onclick="cambiarCantidad(1)">+</button>
                    </div>
                </div>

                <label class="label-detalle">Descripción de personalización</label>
                <textarea placeholder="Ejemplo: Quiero una taza con el nombre Ana y una foto familiar."></textarea>

                <a href="<?= BASE_URL ?>/carrito/agregar/<?= $producto['id_producto'] ?>" class="btn-agregar">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Agregar al carrito
                </a>
            </div>

        </section>

        <section class="extra-detalle">
            <h2>Características del producto</h2>

            <div class="caracteristicas-grid">
                <div><i class="fa-solid fa-check"></i> Producto personalizable</div>
                <div><i class="fa-solid fa-check"></i> Diseño único</div>
                <div><i class="fa-solid fa-check"></i> Entrega rápida</div>
                <div><i class="fa-solid fa-check"></i> Elaborado por Paluse</div>
            </div>
        </section>

    <?php else: ?>
        <div class="sin-productos">
            <i class="fa-regular fa-face-frown"></i>
            <h3>Producto no encontrado</h3>
            <p>El producto que buscas no existe o ha sido eliminado.</p>
            <a href="<?= BASE_URL ?>/producto/catalogo" class="btn-volver">
                Volver al catálogo
            </a>
        </div>
    <?php endif; ?>

</main>

<script>
function cambiarCantidad(valor) {
    var cantidadElement = document.getElementById('cantidadProducto');
    var cantidad = parseInt(cantidadElement.textContent) + valor;
    if (cantidad < 1) cantidad = 1;
    cantidadElement.textContent = cantidad;
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>