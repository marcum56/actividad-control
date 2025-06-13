<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirigir si no está logueado
if (!isset($_SESSION['persona_id'])) {
  header("Location: index.php");
  exit;
}

// Validar persona_id
$persona_id = $_SESSION['persona_id'];
$es_admin = $_SESSION['es_admin'] ?? false;

require "mover_incompletas.php";

if ($es_admin) {
  $persona = ['nombre' => 'Administrador'];
  $actividades_sql = pg_query($conn, "SELECT actividades.*, personas.nombre AS nombre_persona 
    FROM actividades 
    JOIN personas ON personas.id = actividades.persona_id 
    WHERE borrado = FALSE AND completada = TRUE
    ORDER BY 
      CASE prioridad 
        WHEN 'alta' THEN 1 
        WHEN 'media' THEN 2 
        WHEN 'baja' THEN 3 
        ELSE 4 
      END, fecha ASC");
} else {
  $persona_sql = pg_query_params($conn, "SELECT nombre FROM personas WHERE id = $1", array($persona_id));
  $persona = pg_fetch_assoc($persona_sql);
  $actividades_sql = pg_query_params($conn, "SELECT * FROM actividades 
    WHERE persona_id = $1 AND borrado = FALSE AND completada = TRUE
    ORDER BY 
      CASE prioridad 
        WHEN 'alta' THEN 1 
        WHEN 'media' THEN 2 
        WHEN 'baja' THEN 3 
        ELSE 4 
    END, fecha ASC", array($persona_id));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actividades de <?= htmlspecialchars($persona['nombre']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h2>Actividades de <?= htmlspecialchars($persona['nombre']) ?></h2>
  <a href="actividades.php" class="btn btn-sm btn-outline-danger float-end">Regresar</a>
  <table class="table table-bordered">
   <thead>
  <tr>
    <?php if ($es_admin): ?><th>Persona</th><?php endif; ?>
    <th>Título</th>
    <th>Descripción</th>
    <th>Fecha</th>
    <th>Prioridad</th>
    <th>Completada</th>
  </tr>
</thead>
<tbody>
<?php while ($row = pg_fetch_assoc($actividades_sql)): ?>
<tr class="table-success">
  <?php if ($es_admin): ?><td><?= htmlspecialchars($row['nombre_persona']) ?></td><?php endif; ?>
  <td><?= htmlspecialchars($row['titulo']) ?></td>
  <td><?= htmlspecialchars($row['descripcion']) ?></td>
  <td><?= htmlspecialchars($row['fecha']) ?></td>
  <td><?= ucfirst($row['prioridad']) ?></td>
  <td>✅</td>
</tr>
<?php endwhile; ?>
</tbody>
  </table>
</body>
</html>
