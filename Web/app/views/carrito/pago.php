<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Finalizar Compra</h2>
                </div>
                <div class="card-body">
                    <p class="text-center">Total a pagar: <strong>$<?php echo number_format($total ?? 0, 2); ?></strong></p>
                    <hr>
                    <p class="text-center">Seleccione su mtodo de pago:</p>
                    
                    <!-- Contenedor para el botn de PayPal -->
                    <div id="paypal-button-container" class="mt-4"></div>

                </div>
                <div class="card-footer text-center">
                    <small>Sers redirigido a PayPal para un pago seguro.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SDK de PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=USD"></script>

<!-- Nuestro script para manejar la lgica de PayPal -->
<script src="<?php echo BASE_URL; ?>public/js/paypal.js?v=<?php echo time(); ?>"></script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>