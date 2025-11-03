document.addEventListener("DOMContentLoaded", async () => {
  try {
    const response = await fetch(BASE_URL + "dashboard/datos");
    const data = await response.json();

    console.log("📊 Datos recibidos:", data);

    // === Función auxiliar para evitar duplicados ===
    const destroyChartIfExists = (id) => {
      const canvas = document.getElementById(id);
      if (!canvas) return null;

      const chart = Chart.getChart(canvas);
      if (chart) {
        chart.destroy(); // 👈 elimina gráfico previo
      }

      return canvas.getContext("2d");
    };

    // === VENTAS DIARIAS ===
    const ctxVentas = destroyChartIfExists("chartVentas");
    const dias = data.ventasDiarias.map(d => d.dia);
    const ventas = data.ventasDiarias.map(d => parseFloat(d.total_ventas));
    const pedidos = data.ventasDiarias.map(d => parseInt(d.pedidos));

    new Chart(ctxVentas, {
      type: "bar",
      data: {
        labels: dias,
        datasets: [
          { label: "Facturación ($)", data: ventas, backgroundColor: "#2e8b57" },
          { label: "Pedidos", data: pedidos, backgroundColor: "#9bd083" }
        ]
      },
      options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // === PEDIDOS SEMANALES ===
    const ctxPedidos = destroyChartIfExists("chartPedidos");
    const semanas = data.pedidosSemanales.map(s => s.semana);
    const pedidosSemana = data.pedidosSemanales.map(s => parseInt(s.pedidos));

    new Chart(ctxPedidos, {
      type: "line",
      data: {
        labels: semanas,
        datasets: [{
          label: "Pedidos",
          data: pedidosSemana,
          borderColor: "#216644",
          backgroundColor: "#2e8b57",
          fill: true,
          tension: 0.3
        }]
      },
      options: { responsive: true }
    });

    // === VENTAS MENSUALES ===
    const ctxMensual = destroyChartIfExists("chartMensual");
    const meses = data.ventasMensuales.map(m => m.mes);
    const ventasMes = data.ventasMensuales.map(m => parseFloat(m.total_ventas));
    const pedidosMes = data.ventasMensuales.map(m => parseInt(m.pedidos));

    new Chart(ctxMensual, {
      type: "bar",
      data: {
        labels: meses,
        datasets: [
          { label: "Ventas ($)", data: ventasMes, backgroundColor: "#2e8b57" },
          { label: "Pedidos", data: pedidosMes, backgroundColor: "#9bd083" }
        ]
      },
      options: { responsive: true }
    });

    // === VENTAS ANUALES ===
    const ctxAnual = destroyChartIfExists("chartAnual");
    const anios = data.ventasAnuales.map(a => a.anio);
    const ventasAnio = data.ventasAnuales.map(a => parseFloat(a.total_ventas));

    new Chart(ctxAnual, {
      type: "line",
      data: {
        labels: anios,
        datasets: [{
          label: "Ventas Totales ($)",
          data: ventasAnio,
          borderColor: "#2e8b57",
          backgroundColor: "rgba(46,139,87,0.3)",
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          tooltip: {
            callbacks: {
              label: ctx => "$" + ctx.formattedValue
            }
          }
        },
        scales: { y: { beginAtZero: true, ticks: { callback: v => "$" + v } } }
      }
    });

  } catch (error) {
    console.error("Error cargando gráficos:", error);
  }
});
