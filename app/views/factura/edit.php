<?php require_once '../app/views/layouts/header.php'; ?>

<div class="container mt-4">

<h2>Editar Factura</h2>

<form method="POST">

<div class="mb-3">

<label>Usuario</label>

<select name="id_usuario" class="form-control">

<?php foreach($data['usuarios'] as $usuario): ?>

<option
value="<?= $usuario['id_usuario']; ?>"

<?= $usuario['id_usuario']==$data['factura']['id_usuario'] ? 'selected' : ''; ?>>

<?= $usuario['nombre']; ?>

<?= $usuario['apellidos']; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="mb-3">

<label>Estado</label>

<select name="estado" class="form-control">

<?php foreach($data['estados'] as $estado): ?>

<option
value="<?= $estado; ?>"

<?= $estado==$data['factura']['estado'] ? 'selected' : ''; ?>>

<?= $estado; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<button class="btn btn-primary">

Actualizar

</button>

<a
href="<?= BASE_URL ?>/factura/index"
class="btn btn-secondary">

Cancelar

</a>

</form>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>