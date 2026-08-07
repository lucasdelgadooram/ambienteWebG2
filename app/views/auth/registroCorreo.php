<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="form-wrapper">

    <div class="glass-card max-w-md mx-auto">

        <h2>Registro</h2>

        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger">
                <?= $data['error'] ?>
            </div>
        <?php endif; ?>

        <?php if(isset($data['success'])): ?>
            <div class="alert alert-success">
                <?= $data['success'] ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/solicitarRegistro" method="POST">

            <div class="form-group mb-3">
                <label>Correo electrónico</label>

                <input
                    type="email"
                    name="correo"
                    class="form-control"
                    required
                >
            </div>

            <button class="btn btn-primary w-100">
                Enviar correo de verificación
            </button>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>