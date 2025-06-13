<?php
include "db.php";
session_start();

// Validar y castear parámetros
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$persona_id = isset($_GET['persona_id']) ? (int) $_GET['persona_id'] : 0;
$es_admin = $_SESSION['es_admin'] ?? false; // Verifica si es administrador

if ($_POST) {
  $persona_id_post = $es_admin ? (int) $_POST['persona_id'] : $persona_id; // Si es admin, obtiene el ID del usuario seleccionado
  $titulo = $_POST['titulo'];
  $descripcion = $_POST['descripcion'];
  $fecha = $_POST['fecha'];
  $prioridad = $_POST['prioridad'];

  // Usamos pg_query_params para evitar SQL injection
  $query = "UPDATE actividades SET persona_id = $1, titulo = $2, descripcion = $3, fecha = $4, prioridad = $5 WHERE id = $6";
  $params = array($persona_id_post, $titulo, $descripcion, $fecha, $prioridad, $id);

  $result = pg_query_params($conn, $query, $params);

  if (!$result) {
    die("Error al actualizar la actividad: " . pg_last_error($conn));
  }

  // Redirigir de vuelta a la lista de actividades
  header("Location: actividades.php?persona_id=" . $persona_id_post);
  exit;
}

// Obtener la actividad a editar usando pg_query_params
$query = "SELECT * FROM actividades WHERE id = $1";
$params = array($id);
$act_result = pg_query_params($conn, $query, $params);

if (!$act_result) {
    die("Error al obtener la actividad: " . pg_last_error($conn));
}

$act = pg_fetch_assoc($act_result);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Actividad</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Editar Actividad</h2>
  <form method="POST">
    <?php if ($es_admin): ?>
      <label for="persona_id" class="form-label">Asignar a:</label>
      <select name="persona_id" class="form-select mb-2" required>
        <option value="">-- Selecciona un usuario --</option>
        <?php
          $usuarios_sql = pg_query($conn, "SELECT id, nombre FROM personas ORDER BY nombre ASC");
          while ($usuario = pg_fetch_assoc($usuarios_sql)):
        ?>
          <option value="<?= $usuario['id'] ?>" <?= $act['persona_id'] == $usuario['id'] ? 'selected' : '' ?>><?= htmlspecialchars($usuario['nombre']) ?></option>
        <?php endwhile; ?>
      </select>
    <?php endif; ?>

    <input name="titulo" value="<?= htmlspecialchars($act['titulo']) ?>" class="form-control mb-2" required>
    <textarea name="descripcion" class="form-control mb-2"><?= htmlspecialchars($act['descripcion']) ?></textarea>
    <input name="fecha" type="date" value="<?= htmlspecialchars($act['fecha']) ?>" class="form-control mb-2" required>
    <select name="prioridad" class="form-select mb-2">
      <option value="alta" <?= $act['prioridad'] == 'alta' ? 'selected' : '' ?>>Alta</option>
      <option value="media" <?= $act['prioridad'] == 'media' ? 'selected' : '' ?>>Media</option>
      <option value="baja" <?= $act['prioridad'] == 'baja' ? 'selected' : '' ?>>Baja</option>
    </select>
    <button class="btn btn-success">Guardar</button>
    <a href="actividades.php?persona_id=<?= $persona_id ?>" class="btn btn-secondary">Cancelar</a>
  </form>
</body>
</html>
