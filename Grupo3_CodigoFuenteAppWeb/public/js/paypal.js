// Manejador del formulario de pago
document.addEventListener('DOMContentLoaded', function() {
    const formPago = document.getElementById('formPago');
    const paypalContainer = document.getElementById('paypal-button-container');
    const paymentDetails = document.getElementById('paymentDetails');
    const direccionGroup = document.getElementById('direccionGroup');
    const btnConfirmarPago = document.getElementById('btnConfirmarPago');

    // Mostrar/ocultar campos según el método de pago
    document.querySelectorAll('input[name="metodoPago"]').forEach(radio => {
        radio.addEventListener('change', function() {
            paymentDetails.classList.remove('d-none');
            paypalContainer.classList.add('d-none');
            btnConfirmarPago.classList.remove('d-none');

            if (this.value === '3') { // PayPal
                paypalContainer.classList.remove('d-none');
                btnConfirmarPago.classList.add('d-none');
            }
        });
    });

    // Manejar envío del formulario para otros métodos de pago
    btnConfirmarPago.addEventListener('click', function() {
        const metodoPago = document.querySelector('input[name="metodoPago"]:checked');
        const direccion = document.getElementById('direccion').value;

        if (!metodoPago) {
            alert('Por favor seleccione un método de pago');
            return;
        }

        if (metodoPago.value === '3') { // Si es PayPal, no hacer nada aquí
            return;
        }

        if (!direccion.trim()) {
            alert('Por favor ingrese una dirección de envío');
            return;
        }

        // Crear FormData para enviar
        const formData = new FormData(formPago);

        // Enviar al backend
        const BASE = (window.BASE_URL || '/');
        fetch(BASE + 'pedido/procesarPago', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart total in header
                const cartTotalElement = document.querySelector('.cart-total');
                if (cartTotalElement) {
                    cartTotalElement.textContent = '$0.00';
                }
                window.location.href = '/Web/pedido/confirmacion/' + data.pedidoId;
            } else {
                alert(data.message || 'Error al procesar el pago');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar el pago. Por favor intente nuevamente.');
        });
    });

    // Prevenir el envío tradicional del formulario
    formPago.addEventListener('submit', function(e) {
        e.preventDefault();
    });
});

// PayPal Buttons: createOrder -> backend -> capture via backend
paypal.Buttons({
    style: {
        layout: 'vertical',
        color: 'blue',
        shape: 'rect',
    label: 'paypal',
    fundingSource: paypal.FUNDING.PAYPAL
    },

    createOrder: function(data, actions) {
        // Llama a nuestro backend para crear la orden (puede ser crearOrdenPaypal o testPaypal)
        console.log('[PayPal] createOrder -> calling backend to create order');
        const BASE = (window.BASE_URL || '/');
        return fetch(BASE + 'pedido/crearOrdenPaypal', {
            method: 'POST'
        }).then(function(res) {
            console.log('[PayPal] createOrder -> backend responded, status:', res.status);
            if (!res.ok) throw new Error('Error creando la orden, status=' + res.status);
            return res.json();
        }).then(function(json) {
            console.log('[PayPal] createOrder -> backend JSON:', json);
            // Backend debe devolver el objeto order de PayPal o al menos { id: '...'}
            if (json && json.id) return json.id;
            throw new Error('Respuesta inválida al crear orden: ' + JSON.stringify(json));
        }).catch(function(err) {
            console.error('createOrder error:', err);
            // Notificar sin bloquear (no usar alert())
            return null;
        });
    },

    onApprove: function(data, actions) {
        // Capturamos la orden mediante el backend para mantener las credenciales seguras
        console.log('[PayPal] onApprove -> orderID', data.orderID);
        const BASE = (window.BASE_URL || '/');
        return fetch(BASE + 'pedido/capturarOrdenPaypal', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderID: data.orderID })
        }).then(function(res) {
            console.log('[PayPal] onApprove -> capture response status:', res.status);
            if (!res.ok) throw new Error('Error al capturar la orden, status=' + res.status);
            return res.json();
        }).then(function(json) {
            console.log('[PayPal] onApprove -> capture JSON:', json);
            if (json.success) {
                // Redirigir a confirmación
                window.location.href = '/Web/pedido/confirmacion/' + json.pedidoId;
            } else {
                console.error('capturarOrdenPaypal response:', json);
                // Mostrar mensaje no intrusivo
                alert('El pago no pudo ser procesado: ' + (json.error || JSON.stringify(json.details)));
            }
        }).catch(function(err) {
            console.error('onApprove error:', err);
            alert('Ocurrió un error procesando el pago. Revise la consola para más detalles.');
        });
    },

    onError: function(err) {
        console.error('PayPal SDK error:', err);
        alert('Error en PayPal. Intente más tarde.');
    },

    onCancel: function(data) {
        console.log('Pago cancelado por el usuario', data);
        alert('Pago cancelado.');
    }

}).render('#paypal-button-container');
