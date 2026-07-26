function iniciarContador() {
            const ahora = new Date();
            const fechaObjetivo = new Date(ahora);
            fechaObjetivo.setDate(ahora.getDate() + 7);

            function actualizarContador() {
                const ahoraActual = new Date();
                const diferencia = fechaObjetivo - ahoraActual;

                if (diferencia <= 0) {
                    document.getElementById('dias').textContent = '00';
                    document.getElementById('horas').textContent = '00';
                    document.getElementById('minutos').textContent = '00';
                    document.getElementById('segundos').textContent = '00';
                    return;
                }

                const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

                document.getElementById('dias').textContent = String(dias).padStart(2, '0');
                document.getElementById('horas').textContent = String(horas).padStart(2, '0');
                document.getElementById('minutos').textContent = String(minutos).padStart(2, '0');
                document.getElementById('segundos').textContent = String(segundos).padStart(2, '0');
            }

            actualizarContador();
            setInterval(actualizarContador, 1000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            iniciarContador();
            inicializarFiltros();
            inicializarFavoritos();
        });