<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/headIndexUser.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<div class="page-header">
    <h2>Gestión de Roles</h2>
    <a href="<?= BASE_URL ?>/rol/create" class="btn btn-primary">
        + Nuevo Rol
    </a>
</div>

<?php if(isset($_SESSION['rol_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" style="width:90%; margin:0 auto 20px;">
        <?= $_SESSION['rol_success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['rol_success']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['rol_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" style="width:90%; margin:0 auto 20px;">
        <?= $_SESSION['rol_error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['rol_error']); ?>
<?php endif; ?>

<div class="glass-card mt-4">

    <table class="table">

        <thead>
            <tr>
                <th>ID</th>
                <th>Rol</th>
                <th>Fecha Creación</th>
                <th>Fecha Modificación</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if(empty($data['roles'])): ?>
                <tr>
                    <td colspan="5" class="text-center">No hay roles registrados.</td>
                </tr>
            <?php else: ?>

                <?php foreach($data['roles'] as $rol): ?>

                    <tr>

                        <td><?= $rol['id_rol'] ?></td>

                        <td>
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($rol['rol']) ?>
                            </span>
                        </td>

                        <td><?= $rol['fecha_creacion'] ?></td>

                        <td><?= $rol['fecha_modificacion'] ?></td>

                        <td class="actions">

                            <a href="<?= BASE_URL ?>/rol/edit/<?= $rol['id_rol'] ?>"
                               class="btn btn-sm btn-info">
                                <i class="fa-solid fa-pen"></i> Editar
                            </a>

                            <a href="<?= BASE_URL ?>/rol/delete/<?= $rol['id_rol'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('¿Seguro que deseas eliminar este rol?');">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>