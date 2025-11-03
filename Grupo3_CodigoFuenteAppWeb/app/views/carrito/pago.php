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
                    <p class="text-center">Seleccione su método de pago:</p>
                    
                    <!-- Formulario para otros métodos de pago -->
                    <form id="formPago" action="<?php echo BASE_URL; ?>pedido/procesarPago" method="POST">
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metodoPago" id="efectivo" value="0" required>
                                <label class="form-check-label" for="efectivo">Efectivo al recibir</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metodoPago" id="tarjeta" value="1">
                                <label class="form-check-label" for="tarjeta">Tarjeta de crédito/débito</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metodoPago" id="transferencia" value="2">
                                <label class="form-check-label" for="transferencia">Transferencia bancaria</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metodoPago" id="paypal" value="3">
                                <label class="form-check-label" for="paypal">PayPal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metodoPago" id="credito" value="4">
                                <label class="form-check-label" for="credito">Crédito (a plazo)</label>
                            </div>
                        </div>

                        <div id="paymentDetails" class="d-none">
                            <div class="mb-3" id="direccionGroup">
                                <label for="direccion" class="form-label">Dirección de envío</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" id="btnConfirmarPago">Confirmar Pago</button>
                    </form>

                    <!-- Contenedor para el botón de PayPal -->
                    <div id="paypal-button-container" class="mt-4 d-none"></div>
                </div>
                <div class="card-footer text-center">
                    <small>Sus datos están protegidos y el pago es seguro.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SDK de PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=USD"></script>

<script>
  window.BASE_URL = <?php echo json_encode(BASE_URL); ?>;
</script>

<!-- Nuestro script para manejar la lgica de PayPal -->
<script src="<?php echo BASE_URL; ?>public/js/paypal.js?v=<?php echo time(); ?>"></script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>