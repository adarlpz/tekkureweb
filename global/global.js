document.addEventListener("DOMContentLoaded", function () {
    cargarContenido("/global/global.html", "header-container", "header");
    cargarContenido("/global/global.html", "footer-container", "footer");
    cargarLinksGlobales("/global/global.html");

    setTimeout(() => {
        document.getElementById("year").textContent = new Date().getFullYear();
    }, 25);
});
function cargarContenido(url, contenedorId, selector) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            let tempDiv = document.createElement("div");
            tempDiv.innerHTML = data;

            let elemento = tempDiv.querySelector(selector);
            if (elemento) {
                document.getElementById(contenedorId).innerHTML = elemento.outerHTML;
            }
        })
        .catch(error => console.error("Error cargando " + selector, error));
}
function cargarLinksGlobales(url) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            let tempDiv = document.createElement("div");
            tempDiv.innerHTML = data;
            let links = tempDiv.querySelectorAll('link[rel="stylesheet"], link[rel="preconnect"], link[rel="shortcut icon"]');

            links.forEach(link => {
                if (!document.head.querySelector(`link[href="${link.href}"]`)) {
                    document.head.appendChild(link.cloneNode(true)); // Agregar solo si no existe
                }
            });
        })
        .catch(error => console.error("Error cargando links globales", error));
}