<?php
require 'db.php'; // conexión a PostgreSQL

$mensaje = '';

// Guardar o actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $tipo_perfil = $_POST['perfil'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $proyecto = $_POST['proyecto'] ?? '';

    if ($tipo_perfil && $cantidad && $proyecto) {
        if ($id) {
            // Actualizar
            $query = "UPDATE control_perfiles SET tipo_perfil=$1, cantidad=$2, proyecto=$3 WHERE id=$4";
            $res = pg_query_params($conn, $query, [$tipo_perfil, $cantidad, $proyecto, $id]);
            $mensaje = $res ? '<div class="alert alert-success">Registro actualizado.</div>'
                            : '<div class="alert alert-danger">Error al actualizar.</div>';
        } else {
            // Insertar nuevo
            $query = "INSERT INTO control_perfiles (tipo_perfil, cantidad, proyecto) VALUES ($1, $2, $3)";
            $res = pg_query_params($conn, $query, [$tipo_perfil, $cantidad, $proyecto]);
            $mensaje = $res ? '<div class="alert alert-success">Registro guardado.</div>'
                            : '<div class="alert alert-danger">Error al guardar.</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning">Todos los campos son obligatorios.</div>';
    }
}

// Eliminar
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    pg_query_params($conn, "DELETE FROM control_perfiles WHERE id = $1", [$id]);
    header("Location: control_perfiles.php");
    exit;
}

// Cargar para edición
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = pg_query_params($conn, "SELECT * FROM control_perfiles WHERE id = $1", [$id]);
    $editData = pg_fetch_assoc($res);
}

// Obtener todos los registros
$registros = pg_query($conn, "SELECT * FROM control_perfiles ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Control de Perfiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">🏗️ Control de Perfiles de Aluminio</h2>

  <?= $mensaje ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5><?= $editData ? 'Editar Perfil' : 'Registrar Nuevo Perfil' ?></h5>
      <form method="POST">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="mb-3">
          <label for="perfil" class="form-label">Tipo de perfil</label>
          <input type="text" class="form-control" name="perfil" id="perfil" value="<?= $editData['tipo_perfil'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
          <label for="cantidad" class="form-label">Cantidad disponible</label>
          <input type="number" class="form-control" name="cantidad" id="cantidad" value="<?= $editData['cantidad'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
          <label for="proyecto" class="form-label">Proyecto asignado</label>
          <input type="text" class="form-control" name="proyecto" id="proyecto" value="<?= $editData['proyecto'] ?? '' ?>" required>
        </div>
        <button type="submit" class="btn btn-dark"><?= $editData ? 'Actualizar' : 'Registrar' ?></button>
        <?php if ($editData): ?>
          <a href="control_perfiles.php" class="btn btn-secondary">Cancelar</a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <h5>📋 Lista de Perfiles</h5>
  <table class="table table-bordered table-striped bg-white">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tipo Perfil</th>
        <th>Cantidad</th>
        <th>Proyecto</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while($fila = pg_fetch_assoc($registros)): ?>
        <tr>
          <td><?= $fila['id'] ?></td>
          <td><?= htmlspecialchars($fila['tipo_perfil']) ?></td>
          <td><?= $fila['cantidad'] ?></td>
          <td><?= htmlspecialchars($fila['proyecto']) ?></td>
          <td>
            <a href="?edit=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="?delete=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Deseas eliminar este registro?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
