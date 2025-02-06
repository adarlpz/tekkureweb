const currentYear = new Date().getFullYear();
document.getElementById('year').textContent = currentYear;

document.addEventListener("DOMContentLoaded", function () {
    cargarContenido("global.html", "header-container", "header");
    cargarContenido("global.html", "footer-container", "footer");
});

function cargarContenido(url, contenedorId, selector) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            // Crear un elemento temporal para extraer solo el header o footer
            let tempDiv = document.createElement("div");
            tempDiv.innerHTML = data;

            let elemento = tempDiv.querySelector(selector);
            if (elemento) {
                document.getElementById(contenedorId).innerHTML = elemento.outerHTML;
            }
        })
        .catch(error => console.error("Error cargando " + selector, error));
}
