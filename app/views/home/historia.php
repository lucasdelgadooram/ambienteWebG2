<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main class="sobreNosotros">

    <section class="banner">

        <div class="bannerI">

            <span class="subtitulo">
                Sobre Nosotros
            </span>

            <h1>Descubre el camino que ha recorrido Paluse</h1>

            <p class="descripcion">
                Desde nuestros primeros productos hasta convertirnos
                en una empresa dedicada a crear recuerdos únicos,
                cada paso ha sido posible gracias a nuestros clientes.
            </p>

            <a href="<?= BASE_URL ?>/producto/catalogo" class="btnBanner">
                Ver catálogo
            </a>

        </div>

        <div class="bannerImg">

            <img src="<?= BASE_URL ?>/resources/p(new).png" class="logoHistoria" alt="Logo Paluse">

            <img src="<?= BASE_URL ?>/resources/nosotrosBanner.jpg" class="imgHistoria" alt="Historia de Paluse">

        </div>

    </section>


    <section class="historiaTiempo">

        <div class="tituloTiempo">

            <span>Nuestra historia</span>

            <h2>Un recorrido por Paluse</h2>

            <p>
                Estos son algunos de los momentos que marcaron el crecimiento
                de nuestra empresa.
            </p>

        </div>

        <div class="cartas">

            <article class="carta">

                <div class="anio">
                    2016
                </div>

                <h3>Los primeros pasos</h3>

                <p>
                    Iniciamos como un pequeño emprendimiento realizando
                    regalos personalizados para familiares y amigos.
                </p>

            </article>

            <article class="carta">

                <div class="anio">
                    2025
                </div>

                <h3>Expansión</h3>

                <p>
                    Incorporamos nuevos productos, maquinaria y proveedores
                    para brindar un mejor servicio.
                </p>

            </article>

            <article class="carta">

                <div class="anio">
                    2026
                </div>

                <h3>Actualidad</h3>

                <p>
                    Seguimos creciendo con nuevos diseños,
                    tecnología y una comunidad que confía
                    en nuestro trabajo.
                </p>

            </article>

        </div>

    </section>

</main>

<?php require_once '../app/views/layouts/footer.php'; ?>