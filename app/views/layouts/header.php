<header>

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid">

            <!-- Logo -->
            <a href="<?= BASE_URL ?>">
                <img src="<?= BASE_URL ?>/resources/logoPaluseNew.png" id="logo" alt="Paluse">
            </a>

            <div id="comment">
                <span>Envíos en toda Costa Rica</span>
                <i class="fa-solid fa-truck iconoNav"></i>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrincipal" 
                    aria-controls="navbarPrincipal" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarPrincipal">

                <!-- Barra de búsqueda -->
                <form class="d-flex" id="barraBusqueda" action="<?= BASE_URL ?>/producto/buscar" method="GET">
                    <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar" id="barra">
                    <button class="btn" type="submit" id="buscarBtn">Buscar</button>
                </form>

                <ul class="navbar-nav ms-auto">
                    <!-- Favoritos -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/favoritos">
                            <i class="fa-solid fa-heart iconoNav"></i>
                        </a>
                    </li>

                    <!-- Carrito -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/carrito">
                            <i class="fa-solid fa-cart-shopping iconoNav"></i>
                        </a>
                    </li>

                    <?php
                        $rutaImagen = BASE_URL . '/resources/profileImg.jpg';

                        if (isset($_SESSION['ruta_imagen']) && !empty($_SESSION['ruta_imagen'])) {
                            $rutaImagen = BASE_URL . '/' . $_SESSION['ruta_imagen'];
                        }
                    ?>

                    <!-- Usuario -->
                    <li class="nav-item">

                        <?php if(isset($_SESSION['user_id'])): ?>

                            <a class="nav-link" href="<?= BASE_URL ?>/usuario/perfil">

                                <img src="<?= $rutaImagen ?>" id="userProfile" alt="Perfil">

                                <span id="correoUser">
                                    <?= $_SESSION['username']; ?>
                                </span>

                            </a>

                        <?php else: ?>

                        <a class="nav-link" href="<?= BASE_URL ?>/auth/index">
                            <img src="<?= $rutaImagen ?>" id="userProfile" alt="Perfil">
                            <span id="correoUser">Iniciar sesión</span>
                        </a>

                        <?php endif; ?>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

</header>

<!-- Segunda barra -->

<nav class="navbar navbar-expand-lg" id="secondNavbar">

    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSecundaria" aria-controls="navbarSecundaria" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSecundaria">
            <ul class="navbar-nav mx-auto" id="lista">

                <!-- Catálogo -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-layer-group iconoNav2"></i>Catálogo
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/producto/categoria/Ropa">
                                Ropa
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/producto/categoria/Accesorios">
                                Accesorios
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/producto/categoria/Envoltorios">
                                Envoltorios
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/producto/categoria/Otros">
                                Otros
                            </a>
                        </li>
                    </ul>

                </li>

                <!-- Acerca -->
                <li class="nav-item">
                    <i class="fa-solid fa-info iconoNav2"></i>
                    <a class="nav-link" href="<?= BASE_URL ?>/home/about">
                        Acerca de nosotros
                    </a>
                </li>

                <!-- Contacto -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-phone iconoNav2"></i>Contacto
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/contacto/redes">
                                Redes
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>/contacto/formulario">
                                Formulario de contacto
                            </a>
                        </li>

                    </ul>

                </li>

                <!-- Soporte -->
                <li class="nav-item">
                    <i class="fa-solid fa-headphones iconoNav2"></i>
                    <a class="nav-link" href="<?= BASE_URL ?>/home/soporte">
                        Soporte
                    </a>

                </li>

                <!-- Opciones para usuarios con sesión iniciada -->
<?php if(isset($_SESSION['user_id'])): ?>

    <!-- Administración de ventas -->
    <li class="nav-item dropdown">

        <a
            class="nav-link dropdown-toggle"
            href="#"
            role="button"
            data-bs-toggle="dropdown"
        >
            <i class="fa-solid fa-cash-register iconoNav2"></i>
            Administración
        </a>

        <ul class="dropdown-menu">

            <li>
                <a
                    class="dropdown-item"
                    href="<?= BASE_URL ?>/factura/index"
                >
                    <i class="fa-solid fa-file-invoice"></i>
                    Facturas
                </a>
            </li>

            <li>
                <a
                    class="dropdown-item"
                    href="<?= BASE_URL ?>/venta/index"
                >
                    <i class="fa-solid fa-cart-shopping"></i>
                    Ventas
                </a>
            </li>

        </ul>

    </li>

    <!-- Cerrar sesión -->
    <li class="nav-item">

        <i class="fa-solid fa-door-closed iconoNav2"></i>

        <a
            class="nav-link"
            href="<?= BASE_URL ?>/auth/logout"
        >
            Cerrar sesión
        </a>

    </li>

<?php endif; ?>
            </ul>

        </div>

    </div>

</nav>