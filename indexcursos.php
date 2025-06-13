<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cursos Disponibles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-img-top { height: 180px; object-fit: cover; }
  </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">🎓 Cursos Disponibles</h2>
    <div class="row">
      <?php
      $res = pg_query($conn, "SELECT * FROM cursos ORDER BY id DESC");
      while ($curso = pg_fetch_assoc($res)):
      ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <img src="<?= htmlspecialchars($curso['imagen_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($curso['titulo']) ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($curso['titulo']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($curso['descripcion']) ?></p>
            <div class="mt-auto">
              <a href="curso.php?id=<?= $curso['id'] ?>" class="btn btn-outline-primary w-100 mt-2">Ver Curso</a>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
