<?php
require 'db.php'; // conexión $conn

$mensaje = '';

// Crear o actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $herramienta = $_POST['herramienta'] ?? '';
    $responsable = $_POST['responsable'] ?? '';
    $fecha = $_POST['fechaPrestamo'] ?? '';

    if ($herramienta && $responsable && $fecha) {
        if ($id) {
            // Actualizar
            $query = "UPDATE control_herramientas SET herramienta=$1, responsable=$2, fecha_prestamo=$3 WHERE id=$4";
            $result = pg_query_params($conn, $query, [$herramienta, $responsable, $fecha, $id]);
            $mensaje = $result ? '<div class="alert alert-success">Registro actualizado.</div>' 
                               : '<div class="alert alert-danger">Error al actualizar.</div>';
        } else {
            // Insertar nuevo
            $query = "INSERT INTO control_herramientas (herramienta, responsable, fecha_prestamo) VALUES ($1, $2, $3)";
            $result = pg_query_params($conn, $query, [$herramienta, $responsable, $fecha]);
            $mensaje = $result ? '<div class="alert alert-success">Registro guardado.</div>' 
                               : '<div class="alert alert-danger">Error al guardar.</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning">Completa todos los campos.</div>';
    }
}

// Eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    pg_query_params($conn, "DELETE FROM control_herramientas WHERE id=$1", [$id]);
    header("Location: control_herramientas.php");
    exit;
}

// Editar: traer datos existentes
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = pg_query_params($conn, "SELECT * FROM control_herramientas WHERE id=$1", [$id]);
    $editData = pg_fetch_assoc($result);
}

// Obtener todos los registros
$registros = pg_query($conn, "SELECT * FROM control_herramientas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de Herramientas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">🔧 Control de Herramientas</h2>

  <?= $mensaje ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5><?= $editData ? 'Editar Registro' : 'Nuevo Registro' ?></h5>
      <form method="POST">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="mb-3">
          <label for="herramienta" class="form-label">Nombre de herramienta</label>
          <input type="text" name="herramienta" class="form-control" id="herramienta"
                 value="<?= $editData['herramienta'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
          <label for="responsable" class="form-label">Responsable</label>
          <input type="text" name="responsable" class="form-control" id="responsable"
                 value="<?= $editData['responsable'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
          <label for="fechaPrestamo" class="form-label">Fecha de préstamo</label>
          <input type="date" name="fechaPrestamo" class="form-control" id="fechaPrestamo"
                 value="<?= $editData['fecha_prestamo'] ?? '' ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><?= $editData ? 'Actualizar' : 'Guardar' ?></button>
        <?php if ($editData): ?>
          <a href="control_herramientas.php" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <h5>📋 Registros</h5>
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Herramienta</th>
        <th>Responsable</th>
        <th>Fecha Préstamo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while($fila = pg_fetch_assoc($registros)): ?>
        <tr>
          <td><?= $fila['id'] ?></td>
          <td><?= htmlspecialchars($fila['herramienta']) ?></td>
          <td><?= htmlspecialchars($fila['responsable']) ?></td>
          <td><?= $fila['fecha_prestamo'] ?></td>
          <td>
            <a href="?edit=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="?delete=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este registro?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
