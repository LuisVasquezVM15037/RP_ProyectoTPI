document.addEventListener("DOMContentLoaded", () => {
    const inputBuscar = document.querySelector('input[name="q"]');
    const resultadosDiv = document.getElementById("resultados-busqueda");

    if (!inputBuscar || !resultadosDiv) return;

    let timer = null;

    inputBuscar.addEventListener("input", () => {
        const query = inputBuscar.value.trim();

        // limpiar resultados si no hay texto
        if (query === "") {
            resultadosDiv.innerHTML = "";
            return;
        }

        // retrasar llamada para no saturar servidor
        clearTimeout(timer);
        timer = setTimeout(() => {
            fetch(`${document.body.dataset.baseurl}producto/buscarAjax?q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    resultadosDiv.innerHTML = html;
                })
                .catch(err => {
                    console.error("Error al buscar:", err);
                });
        }, 300); // espera 300 ms después de escribir
    });
});
