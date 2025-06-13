<?php
if (!isset($conn)) {
    include "db.php";
}
if (!isset($h)) {
    $h = pg_query($conn, "SELECT requerimiento, fecha, productos, subtotal, iva, total FROM cotizaciones ORDER BY fecha DESC, requerimiento DESC");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Cotizaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 40px;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn-custom {
      min-width: 120px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold">Historial de Cotizaciones</h2>
    <div>
      <a href="productosn.php" class="btn btn-primary me-2 btn-custom">+ Nueva Cotización</a>
      <a href="vista_admin.php" class="btn btn-outline-secondary btn-custom">Regresar</a>
    </div>
  </div>

  <div class="card p-4">
    <?php if ($h && pg_num_rows($h) > 0): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-info">
            <tr>
              <th>Req.</th>
              <th>Fecha</th>
              <th>Productos</th>
              <th>Subtotal</th>
              <th>IVA</th>
              <th>Total</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          <?php while($c = pg_fetch_assoc($h)): ?>
            <?php $items = json_decode($c['productos'], true); ?>
            <tr id="cotizacion-<?= $c['requerimiento'] ?>">
              <td><?= $c['requerimiento'] ?></td>
              <td><?= date('d-M-Y', strtotime($c['fecha'])) ?></td>
              <td>
                <ul class="mb-0">
                <?php foreach($items as $item): ?>
                  <li><?= htmlspecialchars($item['descripcion']) ?> (x<?= htmlspecialchars($item['cantidad']) ?>)</li>
                <?php endforeach; ?>
                </ul>
              </td>
              <td>$<?= number_format($c['subtotal'],2) ?></td>
              <td>$<?= number_format($c['iva'],2) ?></td>
              <td><strong>$<?= number_format($c['total'],2) ?></strong></td>
              <td>
                <div class="btn-group" role="group">
                  <a href="editar_cotizacion.php?requerimiento=<?= $c['requerimiento'] ?>" class="btn btn-sm btn-warning">Editar</a>
                  <button type="button" class="btn btn-sm btn-danger" onclick="eliminarCotizacion(<?= $c['requerimiento'] ?>)">Eliminar</button>
                  <a href="generar_pdf.php?requerimiento=<?= $c['requerimiento'] ?>" class="btn btn-sm btn-secondary">PDF</a>
                  <a href="generar_excel.php?requerimiento=<?= $c['requerimiento'] ?>" class="btn btn-sm btn-success">Excel</a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted">No hay cotizaciones registradas.</p>
    <?php endif; ?>
  </div>
</div>

<script>
function eliminarCotizacion(requerimiento) {
  if (confirm("¿Estás seguro de eliminar esta cotización?")) {
    fetch("eliminar_cotizacion.php?requerimiento=" + requerimiento)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const fila = document.getElementById("cotizacion-" + requerimiento);
          if (fila) fila.remove();
        } else {
          alert("Error al eliminar: " + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert("Error de red o del servidor.");
      });
  }
}
</script>

</body>
</html>
