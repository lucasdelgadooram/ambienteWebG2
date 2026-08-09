<?php
require_once '../app/views/layouts/head.php';
require_once '../app/views/layouts/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/css/perfil.css">


<div class="perfil-container">

    <div class="perfil-card">

        <div class="perfil-header">

            <img src="<?= BASE_URL ?>/resources/profileImg.jpg" class="perfil-imagen" alt="Perfil">
            <div>
               
            </div>

        </div>


        <div class="perfil-datos">
            <div class="perfil-dato">
                <i class="fa-solid fa-user"></i>
                <div>
                    <span>Nombre</span>
                    <strong>
                        <?= htmlspecialchars($data['usuario']['nombre']) ?>
                        <?= htmlspecialchars($data['usuario']['apellidos']) ?>
                    </strong>
                </div>
            </div>


            <div class="perfil-dato">
                <i class="fa-solid fa-envelope"></i>
                <div>
                    <span>Correo electrónico</span>
                    <strong>
                        <?= htmlspecialchars($data['usuario']['correo']) ?>
                    </strong>
                </div>
            </div>


            <div class="perfil-dato">
                <i class="fa-solid fa-phone"></i>
                <div>
                    <span>Teléfono</span>
                    <strong>
                        <?= htmlspecialchars($data['usuario']['telefono']) ?>
                    </strong>
                </div>
            </div>


            <div class="perfil-dato">
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <span>Estado</span>

                    <?php if ($data['usuario']['activo']): ?>

                        <strong class="text-success">
                            Activo
                        </strong>

                    <?php else: ?>

                        <strong class="text-danger">
                            Inactivo
                        </strong>

                    <?php endif; ?>

                </div>
            </div>

        </div>


        <div class="perfil-footer">
           <a href="<?= BASE_URL ?>/user/editarPerfil" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Editar perfil</a>
        </div>

    </div>

</div>


<?php
require_once '../app/views/layouts/footer.php';
?>