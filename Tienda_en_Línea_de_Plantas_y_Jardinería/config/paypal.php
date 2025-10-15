<?php
/**
 * Configuración de PayPal Sandbox
 * Tienda en Línea de Plantas y Jardinería
 */

return [
    'sandbox' => true,
    'client_id' => 'TU_CLIENT_ID_AQUI',
    'client_secret' => 'TU_CLIENT_SECRET_AQUI',
    'return_url' => 'http://localhost/tienda-plantas/payment/success',
    'cancel_url' => 'http://localhost/tienda-plantas/payment/cancel',
    'currency' => 'MXN',
    'locale' => 'es_MX'
];
