<?php
require_once '../app/views/layouts/head.php';
require_once '../app/views/layouts/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/css/perfil.css">


<div class="perfil-container">



    <div class="perfil-card">


        <?php if (isset($data['error'])): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($data['error']) ?>
            </div>

        <?php endif; ?>

        <div class="perfil-header">

            <img src="<?= BASE_URL ?>/resources/profileImg.jpg" class="perfil-imagen" alt="Perfil">
            

        </div>

        <form action="<?= BASE_URL ?>/user/editarPerfil" method="POST">

            <div class="mb-3">
                <label for="username" class="form-label">
                    Usuario
                </label>
                <input type="text" id="username" name="username" class="form-control"  value="<?= htmlspecialchars($data['usuario']['username']) ?>" required>
            </div>


            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($data['usuario']['nombre']) ?>" required>
            </div>


            <div class="mb-3">
                <label for="apellidos" class="form-label">
                    Apellidos
                </label>
                <input type="text" id="apellidos" name="apellidos" class="form-control" value="<?= htmlspecialchars($data['usuario']['apellidos']) ?>" required >
            </div>


            <div class="mb-3">
                <label for="correo" class="form-label">
                    Correo electrónico
                </label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?= htmlspecialchars($data['usuario']['correo']) ?>" required >
            </div>


            <div class="mb-3">
                <label for="telefono" class="form-label">
                    Teléfono
                </label>
                <input type="text" id="telefono" name="telefono" class="form-control"value="<?= htmlspecialchars($data['usuario']['telefono']) ?>">
            </div>

            <div class="mb-3">
                <label for="ruta_imagen" class="form-label">
                    Ruta de imagen
                </label>
                <input type="text" id="ruta_imagen" name="ruta_imagen" class="form-control" value="<?= htmlspecialchars($data['usuario']['ruta_imagen'] ?? '') ?>">
            </div>


            <div class="d-flex gap-2">
                <button type="submit"class="btn btn-primary">
                <i class="fa-solid fa-save"></i> Guardar cambios </button>
                <a href="<?= BASE_URL ?>/user/perfil" class="btn btn-secondary"> Cancelar</a>
            </div>

        </form>

    </div>

</div>

