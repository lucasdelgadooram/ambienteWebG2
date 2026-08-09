<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main>

    <div class="contacto-page">

        <section class="contacto-hero">
            <div>
                <div class="junto">
                    <i class="fa-solid fa-table"></i>
                    <p class="subtitulo">Deja tu pregunta y visita nuestras redes</p>
                </div>

                <h1>Soporte Paluse</h1>
                <p class="descripcion">
                    Pásate por alguno de nuestros canales y envíanos tus comentarios.
                </p>
            </div>
        </section>

    </div>

    <div class="contacto-banners">

        <section class="banner">
            <div>
                <div class="junto">
                    <i class="fa-brands fa-instagram"></i>
                    <p class="subtitulo">Instagram</p>
                </div>
                <h1>Paluse-CR</h1>
                <p class="descripcion">
                    Pasa adelante, síguenos y mantente al tanto de lo nuevo en Paluse.
                </p>
                <a class="btn btn-link p-0" href="https://instagram.com/paluse-cr" target="_blank" rel="noopener noreferrer">
                    Ir a Instagram
                </a>
            </div>
        </section>

        <section class="banner">
            <div>
                <div class="junto">
                    <i class="fa-brands fa-facebook"></i>
                    <p class="subtitulo">Facebook</p>
                </div>
                <h1>Paluse-CR</h1>
                <p class="descripcion">
                    Únete hoy y sé parte de la familia Paluse.
                </p>
                <a class="btn btn-link p-0" href="https://facebook.com/paluse-cr" target="_blank" rel="noopener noreferrer">
                    Ir a Facebook
                </a>
            </div>
        </section>

        <section class="banner">
            <div>
                <div class="junto">
                    <i class="fa-solid fa-at"></i>
                    <p class="subtitulo">Correo</p>
                </div>
                <h1>celinaCornejo@gmail.com</h1>
                <p class="descripcion">
                    Si necesitas algo formal o más detallado, escríbenos aquí.
                </p>
                <a class="btn btn-link p-0" href="mailto:celinaCornejo@gmail.com">
                    Enviar correo
                </a>
            </div>
        </section>

    </div>

    <section class="contacto-formulario">

        <h2>
            <i class="fa-solid fa-table-cells-large"></i>
            Formulario Contacto
        </h2>

        <div class="form-card">

            <h3>Contacta con nosotros</h3>
            <hr>

            <form action="<?= BASE_URL ?>/contacto/formulario" method="get">

                <div class="campo">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" placeholder="Ingrese su nombre completo" disabled>
                </div>

                <div class="campo">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" placeholder="Ingrese su correo electrónico" disabled>
                </div>

                <div class="campo">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <input type="tel" placeholder="Ingrese su número telefónico" disabled>
                </div>

                <div class="campo textarea">
                    <textarea placeholder="Ingrese su consulta" disabled></textarea>
                </div>

                <div class="enviarBtn">
                    <button type="submit" aria-label="Ir al formulario de contacto">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>

            </form>

        </div>

    </section>

</main>

<?php require_once '../app/views/layouts/footer.php'; ?>