// public/js/carrito.js
// JavaScript para manejar el carrito de compras con AJAX
document.addEventListener("DOMContentLoaded", () => {
    // Obtener la URL base desde un atributo data en el body
    const botonesAumentar = document.querySelectorAll(".cantidad-btn.aumentar"); //referencia a los botones de aumentar producto
    const botonesDisminuir = document.querySelectorAll(".cantidad-btn.disminuir"); //referencia a los botones de disminuir producto
    const botonesEliminar = document.querySelectorAll(".eliminar-item"); //referencia a los botones de eliminar producto
    const resumen = document.querySelector(".resumen-carrito"); // referencia al resumen del carrito
    const headerTotal = document.querySelector(".cart-total"); //referencia a la sumatoria total del header

    /**
     * Actualizar el total del carrito en el header
     */
    function actualizarHeaderTotal(total) {
        // Actualiza el total en el header del sitio
        if (headerTotal) {
            headerTotal.textContent = `$${total.toFixed(2)}`; // actualizar el texto
            headerTotal.classList.add("resaltar"); // animación rápida
            setTimeout(() => headerTotal.classList.remove("resaltar"), 400); // quitar animación después de un tiempo
        }
    }

    /**
     *Actualizar totales del carrito visualmente
     */
    function actualizarResumen(total) {
        // Calcula subtotal e IVA
        const subtotal = (total / 1.13).toFixed(2);
        const iva = (total * 0.13 / 1.13).toFixed(2);
        // Actualiza el resumen del carrito
        if (resumen) {
            // Actualiza el HTML del resumen
            resumen.innerHTML = `
                <p><strong>Subtotal:</strong> $${subtotal}</p>
                <p><strong>IVA (13%):</strong> $${iva}</p>
                <p><strong>Total:</strong> $${total.toFixed(2)}</p>
                <a href="${BASE_URL}carrito/confirmar" class="btn btn-primary">Procesar Pago</a>
            `;
        }
        //Actualiza el total en el header del sitio
        actualizarHeaderTotal(total);
    }

    //Animar fila al actualizar cantidad
    function animarElemento(el) {
        el.style.transition = "background 0.3s"; // transición suave
        el.style.background = "#e6ffe6"; // color de fondo temporal
        setTimeout(() => (el.style.background = "transparent"), 500); // volver al fondo original
    }

    /**
     *Actualizar cantidad con AJAX
     */
    function actualizarCantidad(id, accion) {
        // Realiza una solicitud fetch al servidor para actualizar la cantidad del producto en el carrito
        fetch(`${BASE_URL}carrito/${accion}Ajax/${id}`) 
            .then(res => res.json()) // Parsear la respuesta JSON
            .then(data => {
                if (!data.ok) return; // Si la respuesta no es OK, salir de la función
                // Actualizar la fila del producto en el carrito
                const fila = document.querySelector(`tr[data-id="${id}"]`);
                if (!fila) return; // Si no se encuentra la fila, salir de la función
                // Si la acción es disminuir y el producto ya no está en el carrito, eliminar la fila
                if (accion === "disminuir" && !data.carrito.some(p => p.id_producto == id)) {
                    fila.style.transition = "opacity 0.3s ease"; // transición suave
                    fila.style.opacity = "0"; // hacer que la fila desaparezca
                    setTimeout(() => fila.remove(), 300); // eliminar la fila después de la transición
                    // Si el carrito queda vacío, mostrar mensaje
                } else {
                    const item = data.carrito.find(p => p.id_producto == id); // encontrar el producto actualizado
                    // Actualizar cantidad y subtotal en la fila
                    if (item) {
                        fila.querySelector(".cantidad").textContent = item.cantidad; 
                        fila.querySelector(".subtotal").textContent = "$" + item.subtotal.toFixed(2); 
                        animarElemento(fila); // animar la fila para indicar el cambio
                    }
                }

                // Actualizar el resumen del carrito
                actualizarResumen(data.total);
                // Si el carrito está vacío, mostrar mensaje
                if (data.carrito.length === 0) {
                    const tabla = document.querySelector(".tabla-carrito"); // referencia a la tabla del carrito
                    //  Eliminar la tabla del carrito
                    if (tabla) tabla.remove();
                    const contenedor = document.querySelector(".container"); // referencia al contenedor principal
                    // Mostrar mensaje de carrito vacío 
                    contenedor.innerHTML += `<p style="margin-top:50px; text-align:center;">🛍 Tu carrito está vacío</p>`;
                }
            })
            .catch(err => console.error("Error actualizando cantidad:", err)); // Manejo de errores
    }

    /**
     *Eliminar producto con AJAX
     */
    function eliminarItem(id) {
        // Realiza una solicitud fetch al servidor para eliminar el producto del carrito
        fetch(`${BASE_URL}carrito/eliminarAjax/${id}`)
            .then(res => res.json())
            .then(data => {
                if (!data.ok) return;
                // Eliminar la fila del producto del carrito
                const fila = document.querySelector(`tr[data-id="${id}"]`);
                // Animar la eliminación de la fila
                if (fila) {
                    fila.style.transition = "opacity 0.3s ease";
                    fila.style.opacity = "0";
                    setTimeout(() => fila.remove(), 300);
                }
                // Actualizar el resumen del carrito
                actualizarResumen(data.total);
                // Si el carrito está vacío, mostrar mensaje
                if (data.carrito.length === 0) {
                    const tabla = document.querySelector(".tabla-carrito");
                    // Eliminar la tabla del carrito
                    if (tabla) tabla.remove();
                    const contenedor = document.querySelector(".container");
                    contenedor.innerHTML += `<p style="margin-top:50px; text-align:center;">🛍 Tu carrito está vacío</p>`;
                }
            })
            .catch(err => console.error("Error eliminando producto:", err)); // Manejo de errores
    }

    //Eventos para botones
    // Agregar eventos a los botones de aumentar, disminuir y eliminar
    botonesAumentar.forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            actualizarCantidad(btn.dataset.id, "aumentar");
        }); // fin del evento click
    });

    // Agregar eventos a los botones de disminuir
    botonesDisminuir.forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            actualizarCantidad(btn.dataset.id, "disminuir");
        });
    });
    // Agregar eventos a los botones de eliminar
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            const id = btn.dataset.id;
            // Confirmar antes de eliminar
            if (confirm("¿Deseas eliminar este producto del carrito?")) {
                eliminarItem(id);
            }
        });
    });
});
