<?php include 'db.php';
$id = $_GET['id'];
$preguntas = pg_query($conn, "SELECT * FROM preguntas WHERE curso_id = $id");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Examen del Curso</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h3>🧪 Examen del Curso</h3>
  <form action="resultado.php" method="POST">
    <input type="hidden" name="curso_id" value="<?= $id ?>">
    <div class="mb-3">
      <label class="form-label">Tu nombre</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>
    <?php $i = 1; while ($p = pg_fetch_assoc($preguntas)): ?>
      <div class="mb-4">
        <p><strong><?= $i++ ?>. <?= $p['pregunta'] ?></strong></p>
        <?php
        $opciones = pg_query($conn, "SELECT * FROM opciones WHERE pregunta_id = {$p['id']}");
        while ($o = pg_fetch_assoc($opciones)): ?>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="respuesta_<?= $p['id'] ?>" value="<?= $o['id'] ?>" required>
            <label class="form-check-label"><?= $o['texto'] ?></label>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endwhile; ?>
    <button type="submit" class="btn btn-primary">Enviar Examen</button>
  </form>
</div>
</body>
</html>
