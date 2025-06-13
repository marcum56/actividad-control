<?php
include 'db.php';
$curso_id = $_POST['curso_id'];
$nombre = $_POST['nombre'];
$preguntas = pg_query($conn, "SELECT * FROM preguntas WHERE curso_id = $curso_id");

$total = 0;
$correctas = 0;

while ($p = pg_fetch_assoc($preguntas)) {
  $pid = $p['id'];
  if (isset($_POST["respuesta_$pid"])) {
    $oid = $_POST["respuesta_$pid"];
    $esCorrecta = pg_fetch_result(pg_query($conn, "SELECT es_correcta FROM opciones WHERE id = $oid"), 0, 0);
    $correctas += $esCorrecta ? 1 : 0;
    $total++;
  }
}

pg_query($conn, "INSERT INTO resultados (curso_id, nombre_estudiante, puntaje) VALUES ($curso_id, '$nombre', $correctas)");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Resultado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h3>🎉 Resultado del Examen</h3>
  <p><strong>Estudiante:</strong> <?= htmlspecialchars($nombre) ?></p>
  <p><strong>Puntaje:</strong> <?= $correctas ?> de <?= $total ?></p>
  <a href="index.php" class="btn btn-primary">Volver a Cursos</a>
</div>
</body>
</html>
