<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/headFormsUser.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="form-wrapper mt-4">

    <div class="glass-card max-w-md mx-auto">

        <h2 class="mb-4">Crear Nueva Categoría</h2>

        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger">
                <?= $data['error'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/categoria/create" method="POST">

            <div class="form-group mb-3">
                <label>Descripción</label>
                <input
                    type="text"
                    name="descripcion"
                    class="form-control"
                    required
                    placeholder="Ej: Electrónicos">
            </div>

            <div class="form-group mb-4">
                <label>Imagen (URL)</label>
                <input
                    type="text"
                    name="ruta_imagen"
                    class="form-control"
                    placeholder="https://imagen.com/categoria.jpg">
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/categoria/index" class="btn btn-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar Categoría
                </button>
            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>