<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/headIndexUser.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="page-header"> 
    <h2>Usuarios Registrados</h2>
    <a href="<?= BASE_URL ?>/user/create" class="btn btn-primary">+ Nuevo Usuario</a>
</div>

<div class="glass-card mt-4 p-0">
    <table class="table">

    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Rol</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($data['users'] as $user): ?>
        <tr>
            <td>
                <?= $user['id_usuario'] ?>
            </td>
            <td>
                <?= htmlspecialchars($user['username']) ?>
            </td>
            <td>
                <?= htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) ?>
            </td>

            <td>
                <?= htmlspecialchars($user['nombre_rol']) ?>
            </td>

            <td>
                <?= htmlspecialchars($user['correo']) ?>
            </td>

            <td class="actions">
                <a  href="<?= BASE_URL ?>/user/edit/<?= $user['id_usuario'] ?>" class="btn btn-sm btn-info">
                    Editar
                </a>

                <?php if($user['id_usuario'] != $_SESSION['user_id']): ?>

                    <a 
                        href="<?= BASE_URL ?>/user/delete/<?= $user['id_usuario'] ?>" 
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('¿Seguro que deseas eliminar este usuario?');"
                    >
                        Eliminar
                    </a>

                <?php endif; ?>


            </td>


        </tr>


    <?php endforeach; ?>


    </tbody>

</table>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
