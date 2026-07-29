<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/headFormsUser.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="form-wrapper mt-4">

    <div class="glass-card max-w-md mx-auto" style="width: 60%; padding: 40px;">

        <h2 class="mb-4" style="color: #2c3e50; font-weight: bold;">Crear Nuevo Rol</h2>

        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger">
                <?= $data['error'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/rol/create" method="POST">

            <div class="form-group mb-3">
                <label style="font-weight: 600; color: #2c3e50;">Nombre del Rol</label>
                <input
                    type="text"
                    name="rol"
                    class="form-control"
                    required
                    placeholder="Ej: GERENTE, SUPERVISOR"
                    value="<?= htmlspecialchars($_POST['rol'] ?? '') ?>"
                    style="border-radius: 10px; padding: 12px;"
                >
                <small class="text-muted">Solo letras mayúsculas y guiones bajos. Ej: ADMIN, VENDEDOR, USER</small>
            </div>

            <div class="d-flex justify-content-between" style="margin-top: 30px;">
                <a href="<?= BASE_URL ?>/rol/index" class="btn btn-secondary" style="border-radius: 10px; padding: 12px 24px;">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 12px 24px; background: #3daaf3; border: none;">
                    Guardar Rol
                </button>
            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>