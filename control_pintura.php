<?php
require 'db.php'; // conexión a PostgreSQL

$mensaje = '';

// Guardar o actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nombre_trabajo = $_POST['nombreTrabajo'] ?? '';
    $materiales = $_POST['materiales'] ?? '';
    $tiempo = $_POST['tiempo'] ?? '';

    if ($nombre_trabajo && $materiales && $tiempo) {
        if ($id) {
            $query = "UPDATE control_pintura SET nombre_trabajo = $1, materiales = $2, tiempo = $3 WHERE id = $4";
            $res = pg_query_params($conn, $query, [$nombre_trabajo, $materiales, $tiempo, $id]);
            $mensaje = $res ? '<div class="alert alert-success">Registro actualizado.</div>'
                            : '<div class="alert alert-danger">Error al actualizar.</div>';
        } else {
            $query = "INSERT INTO control_pintura (nombre_trabajo, materiales, tiempo) VALUES ($1, $2, $3)";
            $res = pg_query_params($conn, $query, [$nombre_trabajo, $materiales, $tiempo]);
            $mensaje = $res ? '<div class="alert alert-success">Trabajo registrado.</div>'
                            : '<div class="alert alert-danger">Error al guardar.</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning">Todos los campos son obligatorios.</div>';
    }
}

// Eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    pg_query_params($conn, "DELETE FROM control_pintura WHERE id = $1", [$id]);
    header("Location: control_pintura.php");
    exit;
}

// Editar
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = pg_query_params($conn, "SELECT * FROM control_pintura WHERE id = $1", [$id]);
    $editData = pg_fetch_assoc($res);
}

// Obtener todos los registros
$trabajos = pg_query($conn, "SELECT * FROM control_pintura ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de Pintura</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">🖌️ Control de Pintura</h2>

    <?= $mensaje ?>

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h5><?= $editData ? 'Editar Trabajo' : 'Registrar Trabajo de Pintura' ?></h5>
        <form method="POST">
          <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
          <div class="mb-3">
            <label for="nombreTrabajo" class="form-label">Nombre del trabajo</label>
            <input type="text" class="form-control" name="nombreTrabajo" id="nombreTrabajo" value="<?= $editData['nombre_trabajo'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label for="materiales" class="form-label">Materiales usados</label>
            <textarea class="form-control" name="materiales" id="materiales" rows="3" required><?= $editData['materiales'] ?? '' ?></textarea>
          </div>
          <div class="mb-3">
            <label for="tiempo" class="form-label">Tiempo estimado (hrs)</label>
            <input type="number" class="form-control" name="tiempo" id="tiempo" value="<?= $editData['tiempo'] ?? '' ?>" required>
          </div>
          <button type="submit" class="btn btn-primary"><?= $editData ? 'Actualizar' : 'Guardar' ?></button>
          <?php if ($editData): ?>
            <a href="control_pintura.php" class="btn btn-secondary">Cancelar</a>
            <a href="taller.php" class="btn btn-secondary mb-3">← Volver al Menú Principal</a>
          <?php endif; ?>
        </form>
      </div>
    </div>

    <h5>📋 Trabajos Registrados</h5>
    <table class="table table-striped table-bordered bg-white">
      <thead>
        <tr>
          <th>ID</th>
          <th>Trabajo</th>
          <th>Materiales</th>
          <th>Tiempo (hrs)</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while($t = pg_fetch_assoc($trabajos)): ?>
        <tr>
          <td><?= $t['id'] ?></td>
          <td><?= htmlspecialchars($t['nombre_trabajo']) ?></td>
          <td><?= htmlspecialchars($t['materiales']) ?></td>
          <td><?= $t['tiempo'] ?></td>
          <td><?= $t['creado_en'] ?></td>
          <td>
            <a href="?edit=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="?delete=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este trabajo?')">Eliminar</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
