<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main class="historia">

    <section class="banner">

        <div class="bannerI">

            <span class="subtitulo">
                Nuestra historia
            </span>

            <h1>
                ¿Quieres conocer toda nuestra historia?
            </h1>

            <p class="descripcion">
                Hoy en día nos preparamos para seguir nuestro camino,
                formando parte de tus recuerdos con productos
                personalizados hechos con cariño.
            </p>

            <a href="<?= BASE_URL ?>/home/historia" class="btnBanner">
                Sigue por aquí
            </a>

        </div>

        <div class="bannerImg">

            <img src="<?= BASE_URL ?>/resources/p(new).png" class="logoHistoria" alt="Logo Paluse">

            <img src="<?= BASE_URL ?>/resources/comboRebeca.jpg" class="imgHistoria" alt="Equipo de Paluse">

        </div>

    </section>


    <section class="proposito">

        <div class="nuestroProposito">

            <span>Nuestro propósito</span>
            <h2>Conoce los pilares de Paluse</h2>

            <p>
                Cada producto que realizamos representa el esfuerzo,
                creatividad y dedicación hacia nuestos clientes.
            </p>

        </div>


        <div class="cardsProposito">

            <article class="cardValores">
                <img src="<?= BASE_URL ?>/resources/mision.jpg" alt="Misión">

                <div class="cardBody">
                    <h3>Misión</h3>

                    <p>
                        Brindar productos de sublimación personalizados de alta calidad que reflejen la esencia y creatividad de cada cliente, ofreciendo un servicio cercano, confiable y accesible desde un entorno hogareño que prioriza la atención personalizada y la satisfacción total.
                    </p>
                </div>
            </article>

            <article class="cardValores">
                <img src="<?= BASE_URL ?>/resources/vision.png" alt="Visión">

                <div class="cardBody">
                    <h3>Visión</h3>
                    <p>
                        Ser reconocidos como una empresa referente en sublimación personalizada, destacando por la creatividad, calidad y compromiso con cada cliente, creciendo de manera sostenible sin perder la esencia cercana y artesanal que nos caracteriza.
                    </p>
                </div>
            </article>

            <article class="cardValores">
                <img src="<?= BASE_URL ?>/resources/valores.jpeg" alt="Valores">

                <div class="cardBody">
                    <h3>Valores</h3>
                    <p>
                        Compromiso con el cliente: Escuchamos y entendemos cada necesidad para superar expectativas.
                        Calidad: Cuidamos cada detalle en nuestros productos y procesos.
                        Creatividad: Transformamos ideas en diseños únicos y personalizados.
                    </p>
                </div>
            </article>


        </div>

    </section>

</main>

<?php require_once '../app/views/layouts/footer.php'; ?>