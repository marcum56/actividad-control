<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = '';

if ($_POST) {
  $nombre = $_POST['nombre'];
  $clave = $_POST['clave'];

  $sql = "SELECT * FROM personas WHERE nombre = $1 AND clave = $2";
  $params = array($nombre, $clave);
  $res = pg_query_params($conn, $sql, $params);

  if ($persona = pg_fetch_assoc($res)) {
    $_SESSION['persona_id'] = $persona['id'];
    $_SESSION['es_admin'] = ($persona['es_admin'] ?? false) === 't';
    header("Location: actividades.php");
    exit;
  } else {
    $mensaje = "Nombre o clave incorrectos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2 class="mb-4">Iniciar Sesión</h2>
  <?php if ($mensaje): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>
  <form method="POST">
    <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
    <input name="clave" type="password" class="form-control mb-2" placeholder="Clave" required>
    <button class="btn btn-primary">Ingresar</button>
  </form>
</body>
</html>
