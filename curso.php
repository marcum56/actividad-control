<?php include 'db.php';
$id = $_GET['id'];
$curso = pg_fetch_assoc(pg_query($conn, "SELECT * FROM cursos WHERE id = $id"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $curso['titulo'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2><?= $curso['titulo'] ?></h2>
  <p><?= $curso['descripcion'] ?></p>
  <div><?= nl2br($curso['contenido']) ?></div>
  <a href="quiz.php?id=<?= $curso['id'] ?>" class="btn btn-success mt-4">📋 Realizar Examen</a>
  <a href="index.php" class="btn btn-secondary mt-4">← Volver</a>
</div>
</body>
</html>
