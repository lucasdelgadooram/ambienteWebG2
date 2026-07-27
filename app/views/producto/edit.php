<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="form-wrapper mt-4">
    <div class="glass-card max-w-md mx-auto">
        <h2 class="mb-4">Editar Producto</h2>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger">
                <?= $data['error'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/producto/edit/<?= $data['producto']['id_producto'] ?>" method="POST">
            <div class="form-group mb-3">
                <label>Categoría</label>
                <select name="id_categoria" class="form-control" required>
                    <option value="">Selecciona una categoría</option>
                    <?php foreach ($data['categorias'] as $categoria): ?>
                        <option
                            value="<?= $categoria['id_categoria'] ?>"
                            <?= ($data['producto']['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['descripcion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Descripción</label>
                <input
                    type="text"
                    name="descripcion"
                    class="form-control"
                    value="<?= htmlspecialchars($data['producto']['descripcion']) ?>"
                    required>
            </div>

            <div class="form-group mb-3">
                <label>Detalle</label>
                <textarea name="detalle" class="form-control" rows="4"><?= htmlspecialchars($data['producto']['detalle']) ?></textarea>
            </div>

            <div class="form-group mb-3">
                <label>Precio</label>
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="precio"
                    class="form-control"
                    value="<?= htmlspecialchars($data['producto']['precio']) ?>"
                    required>
            </div>

            <div class="form-group mb-3">
                <label>Existencias</label>
                <input
                    type="number"
                    min="0"
                    name="existencias"
                    class="form-control"
                    value="<?= htmlspecialchars($data['producto']['existencias']) ?>"
                    required>
            </div>

            <div class="form-group mb-3">
                <label>Imagen (URL)</label>
                <input
                    type="text"
                    name="ruta_imagen"
                    class="form-control"
                    value="<?= htmlspecialchars($data['producto']['ruta_imagen']) ?>">
            </div>

            <div class="form-check mb-4">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="activo"
                    name="activo"
                    <?= !empty($data['producto']['activo']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="activo">Producto activo</label>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/producto/index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>