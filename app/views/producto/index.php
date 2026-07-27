<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<?php
function productoBadgeClass($categoria) {
    $normalized = strtolower(trim($categoria));

    switch ($normalized) {
        case 'ropa':
            return 'badge-ropa';
        case 'accesorios':
            return 'badge-accesorios';
        case 'envoltorios':
            return 'badge-envoltorios';
        default:
            return 'badge-otros';
    }
}
?>

<main class="admin-page">
    <section class="admin-header">
        <div class="admin-header-texto">
            <nav class="breadcrumb-admin" aria-label="breadcrumb">
                <a href="<?= BASE_URL ?>">Inicio</a>
                <span>/</span>
                <span>Gestionar Productos</span>
            </nav>
            <h1>
                <i class="fa-solid fa-box"></i>
                Gestionar Productos
            </h1>
            <p>Agrega, edita o desactiva productos del catálogo desde la estructura PHP actual.</p>
        </div>
    </section>

    <div class="gp-toolbar">
        <div class="gp-busqueda">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="filtroProducto" placeholder="Filtrar productos..." oninput="filtrarTabla()">
        </div>

        <div class="gp-toolbar-derecha">
            <select id="filtroCategoria" class="gp-select" onchange="filtrarTabla()">
                <option value="">Todas las categorías</option>
                <?php foreach ($data['categorias'] as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria['descripcion']) ?>">
                        <?= htmlspecialchars($categoria['descripcion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <a href="<?= BASE_URL ?>/producto/create" class="btn-agregar">
                <i class="fa-solid fa-plus"></i>
                Agregar Producto
            </a>
        </div>
    </div>

    <div class="gp-tabla-contenedor">
        <table class="gp-tabla" id="tablaProductos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Existencias</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['productos'] as $producto): ?>
                    <tr>
                        <td><?= $producto['id_producto'] ?></td>
                        <td>
                            <?php if (!empty($producto['ruta_imagen'])): ?>
                                <img
                                    src="<?= htmlspecialchars($producto['ruta_imagen']) ?>"
                                    class="gp-img-miniatura"
                                    alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                            <?php else: ?>
                                <span>Sin imagen</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-descripcion"><?= htmlspecialchars($producto['descripcion']) ?></td>
                        <td>
                            <span class="gp-badge <?= productoBadgeClass($producto['categoria_descripcion']) ?>">
                                <?= htmlspecialchars($producto['categoria_descripcion']) ?>
                            </span>
                        </td>
                        <td>₡<?= number_format((float) $producto['precio'], 2) ?></td>
                        <td><?= (int) $producto['existencias'] ?></td>
                        <td>
                            <span class="gp-activo <?= !empty($producto['activo']) ? 'activo-si' : 'activo-no' ?>">
                                <?= !empty($producto['activo']) ? 'Sí' : 'No' ?>
                            </span>
                        </td>
                        <td>
                            <div class="gp-acciones">
                                <a
                                    href="<?= BASE_URL ?>/producto/edit/<?= $producto['id_producto'] ?>"
                                    class="btn-editar"
                                    title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a
                                    href="<?= BASE_URL ?>/producto/delete/<?= $producto['id_producto'] ?>"
                                    class="btn-eliminar"
                                    title="Eliminar"
                                    onclick="return confirm('¿Seguro que deseas desactivar este producto?');">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="gp-contador">
            <span id="contadorProductos">Mostrando <?= count($data['productos']) ?> productos</span>
        </div>
    </div>
</main>

<script>
function filtrarTabla() {
    const textFilter = document.getElementById('filtroProducto').value.toLowerCase();
    const categoryFilter = document.getElementById('filtroCategoria').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaProductos tbody tr');
    let visibleRows = 0;

    rows.forEach((row) => {
        const description = row.querySelector('.col-descripcion')?.textContent.toLowerCase() ?? '';
        const category = row.querySelector('.gp-badge')?.textContent.toLowerCase().trim() ?? '';
        const matchesText = description.includes(textFilter);
        const matchesCategory = !categoryFilter || category === categoryFilter;

        if (matchesText && matchesCategory) {
            row.style.display = '';
            visibleRows += 1;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('contadorProductos').textContent = `Mostrando ${visibleRows} productos`;
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>