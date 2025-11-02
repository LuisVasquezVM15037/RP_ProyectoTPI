
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
        return fetch('/Web/pedido/crearOrdenPaypal', {
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
        return fetch('/Web/pedido/capturarOrdenPaypal', {
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
