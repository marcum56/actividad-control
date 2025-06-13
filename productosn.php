<?php
include "db.php";
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Comprobación de sesión
if (!isset($_SESSION['persona_id']) || $_SESSION['es_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Filtro
$termino = isset($_GET['termino']) ? trim($_GET['termino']) : '';
$sql = "SELECT * FROM productosn";
$params = [];

if ($termino !== '') {
    $sql .= " WHERE codigo ILIKE $1 OR producto ILIKE $1 OR descripcion ILIKE $1";
    $params[] = "%$termino%";
    $result = pg_query_params($conn, $sql, $params);
} else {
    $result = pg_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos Novopan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 30px; background-color: #f2f2f2; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0">Productos Novopan</h2>
  <div>
    <a href="vista_admin.php" class="btn btn-outline-dark me-2">← Menú Principal</a>
    <a href="historial_cotizaciones.php" class="btn btn-info">Ver Cotizaciones</a>
  </div>
</div>

<form method="GET" class="mb-4">
  <div class="input-group w-50">
    <input type="text" name="termino" class="form-control" placeholder="Buscar ..." value="<?= htmlspecialchars($termino) ?>">
    <button type="submit" class="btn btn-primary">Buscar</button>
    <a href="productosn.php" class="btn btn-secondary">Limpiar</a>
  </div>
</form>

<form method="POST" action="cotizar.php">
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-primary">
      <tr>
        <th>Seleccionar</th>
        <th>Código</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
      </tr>
    </thead>
    <tbody>
      <?php if (pg_num_rows($result) > 0): ?>
        <?php while ($row = pg_fetch_assoc($result)): ?>
          <tr>
            <td><input type="checkbox" name="productos_seleccionados[]" value="<?= $row['id'] ?>"></td>
            <td><?= htmlspecialchars($row['codigo']) ?></td>
            <td><?= htmlspecialchars($row['producto']) ?></td>
            <td><?= htmlspecialchars($row['descripcion']) ?></td>
            <td>$<?= number_format($row['precio_usd'], 3) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No se encontraron productos.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <button type="submit" class="btn btn-success">Enviar a Cotización</button>
</form>

</body>
</html>
