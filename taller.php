<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Taller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            transition: transform 0.2s ease;
            border-radius: 16px;
        }
        .card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .section-header {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center section-header">
        <h1 class="display-5">Panel de Control del Taller</h1>
        <p class="text-muted">Seleccione un área para gestionar</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Control de Pintura</h5>
                    <p class="card-text">Gestione las órdenes y materiales usados en trabajos de pintura.</p>
                    <a href="control_pintura.php" class="btn btn-primary">Ingresar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Control de Herramientas</h5>
                    <p class="card-text">Registre el uso, préstamo y mantenimiento de herramientas.</p>
                    <a href="control_herramientas.php" class="btn btn-primary">Ingresar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Control de Perfiles de Aluminio</h5>
                    <p class="card-text">Controle el stock y corte de perfiles de aluminio por proyecto.</p>
                    <a href="control_perfiles.php" class="btn btn-primary">Ingresar</a>
                </div>
            </div>
        </div>

        <!-- Puedes agregar más tarjetas aquí -->
    </div>
</div>

</body>
</html>
