<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/headIndexUser.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Categorías Registradas</h2>
    <a href="<?= BASE_URL ?>/categoria/create" class="btn btn-primary">
        + Nueva Categoría
    </a>
</div>

<div class="glass-card mt-4 p-0">

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach($data['categorias'] as $categoria): ?>

                <tr>

                    <td>
                        <?= $categoria['id_categoria'] ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($categoria['descripcion']) ?>
                    </td>

                    <td>
                        <?php if(!empty($categoria['ruta_imagen'])): ?>
                            <img src="<?= htmlspecialchars($categoria['ruta_imagen']) ?>" alt="<?= htmlspecialchars($categoria['descripcion']) ?>"  width="60">
                        <?php else: ?>
                            Sin imagen
                        <?php endif; ?>
                    </td>

                    <td class="actions">

                        <a
                            href="<?= BASE_URL ?>/categoria/edit/<?= $categoria['id_categoria'] ?>"
                            class="btn btn-sm btn-info">
                            Editar
                        </a>

                        <a
                            href="<?= BASE_URL ?>/categoria/delete/<?= $categoria['id_categoria'] ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                            Eliminar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>