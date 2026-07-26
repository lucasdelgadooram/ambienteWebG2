function mostrarFecha() {
    const ahora = new Date();
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const fechaFormateada = ahora.toLocaleDateString('es-ES', opciones);
    document.getElementById('fechaActual').textContent = fechaFormateada;
}

document.addEventListener('DOMContentLoaded', function () {
    mostrarFecha();
});