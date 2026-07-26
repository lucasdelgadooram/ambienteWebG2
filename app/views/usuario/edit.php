<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/headFormsUser.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>


<div class="form-wrapper mt-4">

    <div class="glass-card max-w-md mx-auto">

        <h2 class="mb-4">
            Editar Usuario
        </h2>

        <?php if(isset($data['error'])): ?>

            <div class="alert alert-danger">
                <?= $data['error'] ?>
            </div>

        <?php endif; ?>

        <form action="<?= BASE_URL ?>/user/edit/<?= $data['user']['id_usuario'] ?>" method="POST">

            <div class="form-group mb-3">
                <label> Usuario</label>
                <input  type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['user']['username']) ?>" required>
            </div>

            <div class="form-group mb-3">
                <label>Nombre </label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($data['user']['nombre']) ?>" required>
            </div>

            <div class="form-group mb-3">
                <label> Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($data['user']['apellidos']) ?>" required>
            </div>

            <div class="form-group mb-3">
                <label> Correo</label>
                <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($data['user']['correo']) ?>"required>
            </div>

            <div class="form-group mb-3">
                <label>Teléfono </label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($data['user']['telefono']) ?>">
            </div>

            <div class="form-group mb-3">
                <label> Imagen (URL)</label>
                <input type="text" name="ruta_imagen" class="form-control" value="<?= htmlspecialchars($data['user']['ruta_imagen']) ?>">
            </div>

            <div class="form-group mb-4">
                <label> Contraseña (dejar vacío para mantener la actual) </label>
                <input type="password" name="password" class="form-control" placeholder="Nueva contraseña">
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/user/index" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary"> Actualizar</button>
            </div>

        </form>

    </div>

</div>


<?php require_once '../app/views/layouts/footer.php'; ?>