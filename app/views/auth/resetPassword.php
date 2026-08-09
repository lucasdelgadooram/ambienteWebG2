<?php require_once '../app/views/layouts/headRegistroCorreo.php'; ?>
<body>
<div class="glass-card">

    <h2>Nueva contraseña</h2>
    <p>ngresa tu nueva contraseña y confírmala para actualizar tu cuenta.</p>

    <?php if (isset($data['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($data['error']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/auth/actualizarPassword" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($data['token']) ?>" >
        <div class="mb-3">
            <label for="password" class="form-label"> Nueva contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>

        </div>

        <div class="mb-3">

            <label for="password_confirm" class="form-label">
                Confirmar contraseña
            </label>

            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required >

        </div>

        <button type="submit" class="btn btn-primary w-100" >
            Cambiar contraseña
        </button>

    </form>

</div>

    </body>