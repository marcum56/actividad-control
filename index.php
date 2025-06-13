<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensaje = '';

if ($_POST) {
  $persona_id = $_POST['persona_id'];
  $clave = $_POST['clave'];

  $sql = "SELECT * FROM personas WHERE id = $1 AND clave = $2";
  $params = array($persona_id, $clave);
  $res = pg_query_params($conn, $sql, $params);

  if ($persona = pg_fetch_assoc($res)) {
    $_SESSION['persona_id'] = $persona['id'];
    $_SESSION['es_admin'] = ($persona['es_admin'] ?? false) === 't';
  
    if (strtoupper($persona['nombre']) === 'MARIO') {
    header("Location: indexcursos.php");
  } elseif ($_SESSION['es_admin']) {
    header("Location: vista_admin.php");
  } else {
    header("Location: actividades.php");
  }
    exit;
  } else {
    $mensaje = "Clave incorrecta.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>
<body class="container py-5">
  <div class="row align-items-center" style="min-height: 60vh;">
    <div class="col-md-6 d-flex justify-content-center align-items-center">
      <img src="logo.jpeg" alt="Logo de la Empresa" class="logo-img">
    </div>

    <div class="col-md-6">
      <h2 class="mb-4">Iniciar Sesión</h2>
      <?php if ($mensaje): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>
      <form method="POST" class="w-75 mx-auto">
        <select name="persona_id" class="form-select mb-3" required>
          <option value="">-- Selecciona tu nombre --</option>
          <?php
          $res = pg_query($conn, "SELECT id, nombre FROM personas ORDER BY nombre");
          while ($row = pg_fetch_assoc($res)) {
            echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
          }
          ?>
        </select>
        <input name="clave" type="password" class="form-control mb-3" placeholder="Password" required>
        <button class="btn btn-primary">Ingresar</button>
      </form>
    </div>
  </div>
</body>
</html>
