<?php require_once '../app/views/layouts/head.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>

<main class="contacto-page">

    <nav class="breadcrumb-contacto" aria-label="breadcrumb">
        <a href="<?= BASE_URL ?>">Inicio</a>
        <span>/</span>
        <a href="<?= BASE_URL ?>/contacto/redes">Contacto</a>
        <span>/</span>
        <span class="breadcrumb-actual">Redes</span>
    </nav>

    <h2 class="titulo-contacto">
        <i class="fa-solid fa-grip"></i>
        Contacto
    </h2>

    <div class="redes-container">

        <a
            href="https://instagram.com/paluse-cr"
            target="_blank"
            rel="noopener noreferrer"
            class="red-card card-instagram"
        >
            <div class="red-izq">
                <i class="fa-brands fa-instagram red-icono"></i>
                <span class="red-nombre">Paluse-CR</span>
            </div>
            <p class="red-der-texto">Pásate por aquí y<br>síguenos</p>
        </a>

        <a
            href="https://facebook.com/paluse-cr"
            target="_blank"
            rel="noopener noreferrer"
            class="red-card card-facebook"
        >
            <p class="red-izq-texto">¡Únete hoy y sé parte<br>de la familia Paluse!</p>
            <div class="red-der">
                <span class="red-nombre">Paluse-CR</span>
                <div class="red-circulo red-circulo-fb">
                    <i class="fa-brands fa-facebook-f"></i>
                </div>
            </div>
        </a>

        <div class="red-card card-email">
            <div class="red-izq">
                <i class="fa-solid fa-envelope red-icono"></i>
                <a href="mailto:celinaCornejo@gmail.com" class="email-link">celinaCornejo@gmail.com</a>
            </div>
            <div class="red-der">
                <span class="telefono-texto">8888-8888</span>
                <div class="red-circulo red-circulo-wa">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
            </div>
        </div>

    </div>

</main>

<div class="resenas-wave" aria-hidden="true"></div>

<section class="resenas-section">
    <h2 class="resenas-titulo">Reseñas</h2>

    <div class="resenas-grid">

        <article class="resena-card">
            <p class="resena-texto">
                Me encantaron los productos de Paluse, pedí una sueta para mis hijos y
                fueron de una muy buena calidad y con un diseño nada parecido a las
                demás, ¡gracias!
            </p>
            <div class="resena-autor">
                <img src="<?= BASE_URL ?>/resources/profileImg.jpg" class="resena-avatar" alt="Frenkie D Jong">
                <div>
                    <span class="resena-nombre">Frenkie D Jong</span>
                    <div class="resena-estrellas">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
            </div>
        </article>

        <article class="resena-card">
            <p class="resena-texto">
                Los productos de Paluse fueron recibidos con una calidad impresionante,
                sin embargo su servicio de envío tardó un poco, pero lo recompensaron.
            </p>
            <div class="resena-autor">
                <img src="<?= BASE_URL ?>/resources/profileImg.jpg" class="resena-avatar" alt="Jorge Gonzalez">
                <div>
                    <span class="resena-nombre">Jorge Gonzalez</span>
                    <div class="resena-estrellas">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                    </div>
                </div>
            </div>
        </article>

        <article class="resena-card">
            <p class="resena-texto">
                Pedí productos con temática de videojuegos y el resultado superó mis
                expectativas. La personalización fue excelente.
            </p>
            <div class="resena-autor">
                <img src="<?= BASE_URL ?>/resources/profileImg.jpg" class="resena-avatar" alt="Shigeru Miyamoto">
                <div>
                    <span class="resena-nombre">Shigeru Miyamoto</span>
                    <div class="resena-estrellas">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>
            </div>
        </article>

    </div>

</section>

<?php require_once '../app/views/layouts/footer.php'; ?>