<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main class="contacto-page">

    <nav class="breadcrumb-contacto" aria-label="breadcrumb">
        <a href="<?= BASE_URL ?>">Inicio</a>
        <span>/</span>
        <a href="<?= BASE_URL ?>/contacto/redes">Contacto</a>
        <span>/</span>
        <span class="breadcrumb-actual">Formulario Contacto</span>
    </nav>

    <h2 class="titulo-contacto">
        <i class="fa-solid fa-grip"></i>
        Formulario Contacto
    </h2>

    <div class="form-card">
        <h3>Contacta con nosotros</h3>
        <hr>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
        <?php endif; ?>

        <?php if (isset($data['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($data['success']) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/contacto/formulario" method="post">

            <div class="campo">
                <i class="fa-solid fa-user"></i>
                <input
                    type="text"
                    name="nombre"
                    placeholder="Ingrese su nombre completo"
                    value="<?= htmlspecialchars($data['values']['nombre'] ?? '') ?>"
                    required
                >
            </div>

            <div class="campo">
                <i class="fa-solid fa-envelope"></i>
                <input
                    type="email"
                    name="correo"
                    placeholder="Ingrese su correo electrónico"
                    value="<?= htmlspecialchars($data['values']['correo'] ?? '') ?>"
                    required
                >
            </div>

            <div class="campo">
                <i class="fa-solid fa-mobile-screen"></i>
                <input
                    type="tel"
                    name="telefono"
                    placeholder="Ingrese su número telefónico"
                    value="<?= htmlspecialchars($data['values']['telefono'] ?? '') ?>"
                >
            </div>

            <div class="campo textarea">
                <textarea
                    name="consulta"
                    placeholder="Ingrese su consulta"
                    required
                ><?= htmlspecialchars($data['values']['consulta'] ?? '') ?></textarea>
            </div>

            <div class="enviarBtn">
                <button type="submit" aria-label="Enviar mensaje">
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </button>
            </div>

        </form>
    </div>

</main>

<section class="proveedores">
    <p class="proveedores-titulo">Nuestros Proveedores</p>
    <div class="proveedores-logos">
        <span class="prov-epson">EPSON</span>
        <span class="prov-colormake">color<b>make</b></span>
        <span class="prov-hanrun"><b>H</b>anrun paper</span>
        <span class="prov-fauca">FAUCA</span>
        <span class="prov-xtool"><span class="prov-x">x</span>TOOL</span>
    </div>
</section>

<?php require_once '../app/views/layouts/footer.php'; ?>