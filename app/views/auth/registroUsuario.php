<?php require_once '../app/views/layouts/headRegistroCorreo.php'; ?>

<div class="register-container">

    <div class="glass-card">

        <h2>Completar registro</h2>

        <p class="register-description">
            Completa los siguientes datos para crear tu cuenta.
        </p>

        <?php if(isset($data['error'])): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($data['error']) ?>
            </div>

        <?php endif; ?>


        <form action="<?= BASE_URL ?>/auth/registrarUsuario" method="POST">

            <input type="hidden" name="token" value="<?= htmlspecialchars($data['token']) ?>">

            <div class="form-group">

                <label for="correo">
                    Correo electrónico
                </label>
                <input type="email"  id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($data['correo']) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="username">
                    Usuario
                </label>
                <input type="text" id="username" name="username" class="form-control"  required  placeholder="Ej: juan123" >
            </div>

            <div class="form-group">
                <label for="nombre">
                    Nombre
                </label>
                <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Ej: Juan" >
            </div>

            <div class="form-group">
                <label for="apellidos">
                    Apellidos
                </label>
                <input type="text" id="apellidos"  name="apellidos" class="form-control" required placeholder="Ej: Pérez Castro" >
            </div>

            <div class="form-group">
                <label for="telefono">
                    Teléfono
                </label>
                <input type="text"  id="telefono" name="telefono"  class="form-control" placeholder="8888-8888">
            </div>

            <div class="form-group">
                <label for="password">
                    Contraseña
                </label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Contraseña">
            </div>

            <div class="form-group">
                <label for="password_confirm">
                    Confirmar contraseña
                </label>
                <input type="password" id="password_confirm" name="password_confirm" class="form-control" required placeholder="Repite tu contraseña">
            </div>

            <div class="register-buttons">
                <a href="<?= BASE_URL ?>/auth/index" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    Crear cuenta
                </button>
            </div>

        </form>

    </div>

</div>


