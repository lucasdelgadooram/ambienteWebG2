<?php require_once '../app/views/layouts/headRegistroCorreo.php'; ?>
<body>
<div class="glass-card">

    <h2>Olvidé mi contraseña</h2>

    <p>
        Ingresa tu correo electrónico y te enviaremos
        un enlace para restablecer tu contraseña.
    </p>

<!-- Mensajes -->
    <?php if (isset($data['error'])): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($data['error']) ?>
        </div>

    <?php endif; ?>

    <?php if (isset($data['success'])): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($data['success']) ?>
        </div>

    <?php endif; ?>


    <form action="<?= BASE_URL ?>/auth/recuperarSoli" method="POST">

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control" placeholder="Ingrese su correo electrónico" required>
        </div>

        <button type="submit" class="btn btn-primary w-100"> Enviar enlace</button>

    </form>

    <div class="text-center mt-3">
        <a href="<?= BASE_URL ?>/auth/index">
            Volver al inicio de sesión
        </a>

    </div>

</div>
    </body>