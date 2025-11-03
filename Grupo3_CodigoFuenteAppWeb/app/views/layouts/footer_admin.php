</main>
<!-- ======= FOOTER ======= -->
<footer class="admin-footer text-center py-3 mt-auto">
  <div>
    </span>
    <span class="text-muted">Panel Administrativo</span><span class="footer-brand"> | © <?= date('Y') ?> Jardinería Verde - Todos los derechos reservados - TPI115 - Ciclo 02
    <br>
    <small class="text-muted">Desarrollado por <strong>Luis Enrique Vásquez - VM15037</strong></small>
  </div>
</footer>
</div> <!-- /main-content -->
</div> <!-- /admin-wrapper -->

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
  integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
  integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

  <?php if (!defined('BASE_URL')) define('BASE_URL', 'http://localhost/Web/'); ?>

<script>
  window.BASE_URL = "<?= BASE_URL ?>";
</script>
<!-- Librería Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script personalizado -->
<script src="<?= BASE_URL ?>public/js/dashboard-charts.js"></script>
</body>

</html>