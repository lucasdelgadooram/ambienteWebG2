<?php require_once '../app/views/layouts/headLogin.php'; ?>
<?php require_once '../app/views/layouts/header.php'; ?>


<main class="contenedor">

    <section class="twoHalf">
        <img src="<?= BASE_URL ?>/resources/p(new).png" alt="Logo">
    </section>


    <section class="cards-login">
        <form class="card-login" action="<?= BASE_URL ?>/auth/login" method="POST">
            <div class="top">
                <h2>¡Bienvenido de vuelta!</h2>
                <p>
                    Si aún no tienes una cuenta, puedes registrarte.
                    Es gratis y rápido.
                </p>
            </div>

            <?php if(isset($data['error'])): ?>
                <div class="alert alert-danger">
                    <?= $data['error'] ?>
                </div>
            <?php endif; ?>



            <div class="medio">
                <div class="campo">

                    <label for="username">
                        <i class="fa fa-user"></i>Usuario
                    </label>
                    <input type="text" name="username" id="username"  class="input-login" required>

                </div>



                <div class="campo">
                    <label for="password">
                        <i class="fa fa-lock"></i>Contraseña
                    </label>


                    <input type="password" name="password" id="password" class="input-login" required>

                </div>



                <div class="junto">
                    <input type="checkbox" name="remember" id="remember">

                    <label for="remember">Recuérdame
                    </label>

                </div>

            </div>

            <div class="bottom-card">


                <button type="submit" class="button-login">
                    Iniciar Sesión
                </button>



                <div class="links">
                    <a href="<?= BASE_URL ?>/auth/forgotPassword">
                        ¿Has olvidado tu contraseña?
                    </a>
                    <a href="<?= BASE_URL ?>/usuario/create">
                        ¿Aún no tienes cuenta?
                    </a>
                </div>


            </div>

        </form>

    </section>


</main>



<?php require_once '../app/views/layouts/footer.php'; ?>