<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que el usuario sea administrador
if (!isset($_SESSION['persona_id']) || empty($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
    header("Location: index.php"); // Redirigir si no es admin
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú Principal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .menu-container {
      padding-top: 80px;
    }
    .menu-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }
    .menu-button {
      width: 220px;
      height: 150px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      font-size: 1.3rem;
      font-weight: bold;
      border-radius: 15px;
      background-color: #0d6efd;
      color: white;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }
    .menu-button:hover {
      background-color: #084298;
    }
    .menu-button i {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    .logout-btn {
      margin-top: 40px;
      display: flex;
      justify-content: center;
    }
  </style>
</head>
<body>

<div class="container menu-container text-center">
  <h1 class="mb-5">Menú Principal - Administrador</h1>

  <div class="menu-grid">
    <a href="administracion.php" class="menu-button">
      <i class="bi bi-gear-fill"></i>
      Administración
    </a>
    <a href="actividades.php" class="menu-button">
      <i class="bi bi-list-check"></i>
      Actividades
    </a>
    <a href="taller.php" class="menu-button">
      <i class="bi bi-tools"></i>
      Taller
    </a>
    <a href="historial_cotizaciones.php" class="menu-button">
      <i class="bi bi-building"></i>
      Novopan
    </a>
    <a href="grupo_euro.php" class="menu-button">
      <i class="bi bi-people-fill"></i>
      Grupo Euro
    </a>
       <a href="calculo_precios.php" class="menu-button">
      <i class="bi bi-people-fill"></i>
      CALCULO PUERTAS ALUMX
    </a>

  </div>

  <div class="logout-btn">
    <a href="logout.php" class="btn btn-danger btn-lg mt-5">
      <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
    </a>
  </div>
</div>

</body>
</html>
