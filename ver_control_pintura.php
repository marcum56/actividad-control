<?php
require 'db.php'; // Aquí se incluye tu archivo de conexión

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die("ID inválido.");
}

$trabajo = pg_fetch_assoc(pg_query($conn, "SELECT * FROM control_pintura WHERE id = $id"));
$perfiles = pg_query($conn, "SELECT * FROM perfiles_control_pintura WHERE pintura_id = $id");
$tiraderas = pg_query($conn, "SELECT * FROM tiraderas_control_pintura WHERE pintura_id = $id");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle Control Pintura</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
  <div class="container">
    <h2 class="mb-4">Detalle del Trabajo: <?= htmlspecialchars($trabajo['nombre_trabajo']) ?></h2>
    <p><strong>Materiales:</strong> <?= nl2br(htmlspecialchars($trabajo['materiales'])) ?></p>
    <p><strong>Tiempo estimado:</strong> <?= $trabajo['tiempo'] ?> horas</p>
    <p><strong>Fecha:</strong> <?= $trabajo['creado_en'] ?></p>

    <h4 class="mt-4">Perfiles</h4>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Tipo</th>
          <th>Cantidad</th>
          <th>Sobrante</th>
          <th>Color</th>
        </tr>
      </thead>
      <tbody>
        <?php while($p = pg_fetch_assoc($perfiles)): ?>
        <tr>
          <td><?= htmlspecialchars($p['tipo_perfil']) ?></td>
          <td><?= $p['cantidad'] ?></td>
          <td><?= $p['sobrante'] ?></td>
          <td><?= htmlspecialchars($p['color']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <h4 class="mt-4">Tiraderas Perfil Manija</h4>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Tipo</th>
          <th>Cantidad</th>
          <th>Sobrante</th>
          <th>Color</th>
        </tr>
      </thead>
      <tbody>
        <?php while($t = pg_fetch_assoc($tiraderas)): ?>
        <tr>
          <td><?= htmlspecialchars($t['tipo_tiradera']) ?></td>
          <td><?= $t['cantidad'] ?></td>
          <td><?= $t['sobrante'] ?></td>
          <td><?= htmlspecialchars($t['color']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="listado_control_pintura.php" class="btn btn-secondary mt-3">Volver</a>
  </div>
</body>
</html>
