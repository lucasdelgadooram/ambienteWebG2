<?php require_once '../app/views/layouts/headRegistroCorreo.php'; ?>
<body>
<div class="form-wrapper">

    <div class="glass-card">

        <h2>Crear una cuenta</h2>

        <p>
            Ingresa tu correo electrónico para comenzar el registro.
            Te enviaremos un enlace para verificarlo.
        </p>

<!-- MENSAJES-->
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


        <form action="<?= BASE_URL ?>/auth/solicitarRegistro" method="POST">

            <div class="mb-3">

                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo"  placeholder="ejemplo@email.com" required>

            </div>

            <button type="submit">
                Continuar
            </button>

        </form>

        <div class="mt-3">

            <a href="<?= BASE_URL ?>/auth/login.php">
                Ya tengo una cuenta
            </a>

        </div>

    </div>

</div>
        </body>