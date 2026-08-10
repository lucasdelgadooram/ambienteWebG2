<?php require_once __DIR__ . '/../layouts/head.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="home-page">

    <section class="home-carrusel">
        <div class="carrusel-container">
            <div class="carrusel-track" id="carruselTrack">
                <?php if (!empty($data['productosRecientes'])): ?>
                    <?php foreach ($data['productosRecientes'] as $index => $producto): ?>
                        <div class="carrusel-slide <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
                            <div class="carrusel-content">
                                <div class="carrusel-texto">
                                    <span class="carrusel-etiqueta">Nuevo producto</span>
                                    <h2><?= htmlspecialchars($producto['descripcion']) ?></h2>
                                    <p><?= htmlspecialchars(substr($producto['detalle'] ?? '', 0, 120)) ?>...</p>
                                    <div class="carrusel-precio">
                                        ₡<?= number_format($producto['precio'], 0, ',', '.') ?>
                                    </div>
                                    <a href="<?= BASE_URL ?>/producto/detalle/<?= $producto['id_producto'] ?>" class="btn-carrusel">
                                        Ver producto <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="carrusel-imagen">
                                    <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                                         alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carrusel-slide active" data-index="0">
                        <div class="carrusel-content">
                            <div class="carrusel-texto">
                                <span class="carrusel-etiqueta">Bienvenido a Paluse</span>
                                <h2>Productos personalizados</h2>
                                <p>Explora nuestra colección de productos únicos y personalizados.</p>
                                <a href="<?= BASE_URL ?>/producto/catalogo" class="btn-carrusel">
                                    Ver catálogo <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                            <div class="carrusel-imagen">
                                <img src="<?= BASE_URL ?>/resources/logoPaluseNew.png" alt="Paluse">
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <button class="carrusel-btn carrusel-prev" id="carruselPrev">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="carrusel-btn carrusel-next" id="carruselNext">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="carrusel-indicadores" id="carruselIndicadores">
                <?php if (!empty($data['productosRecientes'])): ?>
                    <?php foreach ($data['productosRecientes'] as $index => $producto): ?>
                        <span class="indicador <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>"></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="indicador active" data-index="0"></span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="home-categorias">
        <div class="seccion-header">
            <h2>Categorías</h2>
            <a href="<?= BASE_URL ?>/producto/catalogo">Ver todas <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="categorias-grid">
            <?php if (!empty($data['categorias'])): ?>
                <?php foreach ($data['categorias'] as $categoria): ?>
                    <?php if ($categoria['activo'] == 1): ?>
                        <a href="<?= BASE_URL ?>/producto/categoria/<?= urlencode($categoria['descripcion']) ?>" class="categoria-card">
                            <div class="categoria-icono">
                                <?php
                                $iconos = [
                                    'Ropa' => 'tshirt',
                                    'Accesorios' => 'gem',
                                    'Envoltorios' => 'gift',
                                    'Papeleria' => 'pen-ruler',
                                    'Personalizados' => 'paintbrush',
                                    'Otros' => 'boxes'
                                ];
                                $icono = $iconos[$categoria['descripcion']] ?? 'cube';
                                ?>
                                <i class="fa-solid fa-<?= $icono ?>"></i>
                            </div>
                            <h3><?= htmlspecialchars($categoria['descripcion']) ?></h3>
                            <span>Ver productos →</span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="home-productos">
        <div class="seccion-header">
            <h2>Productos destacados</h2>
            <a href="<?= BASE_URL ?>/producto/catalogo">Ver todos <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="productos-grid">
            <?php if (!empty($data['productosRecientes'])): ?>
                <?php 
                $destacados = array_slice($data['productosRecientes'], 0, 4);
                foreach ($destacados as $producto): 
                ?>
                    <article class="producto-card">
                        <div class="producto-header"></div>
                        <img src="<?= BASE_URL ?>/resources/<?= !empty($producto['ruta_imagen']) ? $producto['ruta_imagen'] : 'default-product.jpg' ?>" 
                             alt="<?= htmlspecialchars($producto['descripcion']) ?>">
                        <div class="producto-body">
                            <h3><?= htmlspecialchars($producto['descripcion']) ?></h3>
                            <p><?= htmlspecialchars(substr($producto['detalle'] ?? '', 0, 60)) ?>...</p>
                            <span class="estado"><?= $producto['existencias'] > 0 ? 'Disponible' : 'Agotado' ?></span>
                            <div class="producto-footer">
                                <strong>₡<?= number_format($producto['precio'], 0, ',', '.') ?></strong>
                                <div>
                                    <a href="<?= BASE_URL ?>/producto/detalle/<?= $producto['id_producto'] ?>" class="btn-ver">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/carrito/agregar/<?= $producto['id_producto'] ?>" class="btn-carrito">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="sin-productos">
                    <i class="fa-regular fa-face-frown"></i>
                    <h3>No hay productos disponibles</h3>
                    <p>Pronto tendremos nuevos productos.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="home-ofertas-banner">
        <div class="ofertas-banner-content">
            <div class="ofertas-banner-texto">
                <span class="ofertas-etiqueta"><i class="fa-solid fa-bolt"></i> Ofertas especiales</span>
                <h2>¡Aprovecha nuestras promociones!</h2>
                <p>Productos personalizados con descuentos increíbles por tiempo limitado.</p>
                <a href="<?= BASE_URL ?>/producto/ofertas" class="btn-ofertas">
                    Ver ofertas <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="ofertas-banner-imagen">
                <i class="fa-solid fa-percent"></i>
            </div>
        </div>
    </section>

    <section class="home-suscripcion">
        <div class="suscripcion-content">
            <div class="suscripcion-texto">
                <i class="fa-regular fa-bell"></i>
                <div>
                    <h2>¡No te pierdas nada!</h2>
                    <p>Suscríbete para recibir ofertas exclusivas y novedades.</p>
                </div>
            </div>
            <form class="suscripcion-form" action="<?= BASE_URL ?>/home/suscribir" method="POST">
                <input type="email" name="email" placeholder="Tu correo electrónico" required>
                <button type="submit">Suscribirme</button>
            </form>
        </div>
    </section>

</main>

<script>
(function() {
    var slides = [];
    var currentIndex = 0;
    var intervalo = null;
    var isTransitioning = false;

    function obtenerSlides() {
        var track = document.getElementById('carruselTrack');
        if (!track) return [];
        return track.querySelectorAll('.carrusel-slide');
    }

    function actualizarCarrusel() {
        slides = obtenerSlides();
        if (slides.length === 0) return;
        
        if (isTransitioning) return;
        isTransitioning = true;

        for (var i = 0; i < slides.length; i++) {
            slides[i].style.display = 'none';
            slides[i].classList.remove('active');
            slides[i].style.opacity = '0';
        }
        
        if (slides[currentIndex]) {
            slides[currentIndex].style.display = 'block';
            slides[currentIndex].style.opacity = '1';
            slides[currentIndex].classList.add('active');
        }
        
        var indicadores = document.querySelectorAll('.indicador');
        for (var j = 0; j < indicadores.length; j++) {
            if (j === currentIndex) {
                indicadores[j].classList.add('active');
            } else {
                indicadores[j].classList.remove('active');
            }
        }

        setTimeout(function() {
            isTransitioning = false;
        }, 500);
    }

    function carruselSiguiente() {
        slides = obtenerSlides();
        if (slides.length === 0 || isTransitioning) return;
        currentIndex++;
        if (currentIndex >= slides.length) {
            currentIndex = 0;
        }
        actualizarCarrusel();
        reiniciarAutoSlide();
    }

    function carruselAnterior() {
        slides = obtenerSlides();
        if (slides.length === 0 || isTransitioning) return;
        currentIndex--;
        if (currentIndex < 0) {
            currentIndex = slides.length - 1;
        }
        actualizarCarrusel();
        reiniciarAutoSlide();
    }

    function irSlide(index) {
        slides = obtenerSlides();
        if (slides.length === 0 || isTransitioning) return;
        if (index >= 0 && index < slides.length) {
            currentIndex = index;
            actualizarCarrusel();
            reiniciarAutoSlide();
        }
    }

    function reiniciarAutoSlide() {
        if (intervalo) {
            clearInterval(intervalo);
            intervalo = null;
        }
        slides = obtenerSlides();
        if (slides.length > 1) {
            intervalo = setInterval(carruselSiguiente, 5000);
        }
    }

    function iniciarCarrusel() {
        slides = obtenerSlides();
        if (slides.length === 0) return;
        
        for (var i = 0; i < slides.length; i++) {
            slides[i].style.display = 'none';
            slides[i].classList.remove('active');
            slides[i].style.opacity = '0';
        }
        
        currentIndex = 0;
        if (slides[0]) {
            slides[0].style.display = 'block';
            slides[0].style.opacity = '1';
            slides[0].classList.add('active');
        }
        
        var indicadores = document.querySelectorAll('.indicador');
        for (var j = 0; j < indicadores.length; j++) {
            if (j === 0) {
                indicadores[j].classList.add('active');
            } else {
                indicadores[j].classList.remove('active');
            }
        }
        
        reiniciarAutoSlide();
        console.log('Carrusel iniciado con ' + slides.length + ' slides');
    }

    function configurarEventos() {
        var prevBtn = document.getElementById('carruselPrev');
        var nextBtn = document.getElementById('carruselNext');
        var indicadores = document.querySelectorAll('.indicador');
        var container = document.querySelector('.carrusel-container');

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                carruselAnterior();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                carruselSiguiente();
            });
        }

        indicadores.forEach(function(ind, index) {
            ind.addEventListener('click', function() {
                irSlide(index);
            });
        });

        if (container) {
            container.addEventListener('mouseenter', function() {
                if (intervalo) {
                    clearInterval(intervalo);
                    intervalo = null;
                }
            });
            container.addEventListener('mouseleave', function() {
                reiniciarAutoSlide();
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight') {
                carruselSiguiente();
            } else if (e.key === 'ArrowLeft') {
                carruselAnterior();
            }
        });

        var touchStartX = 0;
        if (container) {
            container.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            container.addEventListener('touchend', function(e) {
                var touchEndX = e.changedTouches[0].screenX;
                var diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        carruselSiguiente();
                    } else {
                        carruselAnterior();
                    }
                }
            }, { passive: true });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            iniciarCarrusel();
            configurarEventos();
        });
    } else {
        iniciarCarrusel();
        configurarEventos();
    }

    window.addEventListener('load', function() {
        iniciarCarrusel();
        configurarEventos();
    });
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>