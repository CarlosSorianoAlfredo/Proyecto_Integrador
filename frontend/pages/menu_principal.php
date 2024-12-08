<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validar si la sesión es válida
if (!isset($_SESSION['valida']) || !$_SESSION['valida']) {
    header('Location: login.php');
    exit();
}

$acceso_menu = $_SESSION['acceso_menu'] ?? []; // Lista de menús accesibles, por defecto un array vacío
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/style_menu.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Gestión de Alumnos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="mx-auto d-flex gap-3">
        <?php if (is_array($acceso_menu) && in_array("1", $acceso_menu)): ?>
          <form action="formulario_altas.php" method="GET">
            <button type="submit" class="btn btn-light btn-sm">Agregar</button>
          </form>
        <?php endif; ?>
        <?php if (is_array($acceso_menu) && in_array("2", $acceso_menu)): ?>
          <form action="bajas_cambios.php" method="GET">
            <button type="submit" class="btn btn-light btn-sm">Eliminar/Modificar</button>
          </form>
        <?php endif; ?>
        <?php if (is_array($acceso_menu) && in_array("3", $acceso_menu)): ?>
          <form action="ventana_consultas.php" method="GET">
            <button type="submit" class="btn btn-light btn-sm">Consulta</button>
          </form>
        <?php endif; ?>
        <?php if (is_array($acceso_menu) && in_array("4", $acceso_menu)): ?>
          <form action="lista_alumnos.php" method="GET">
            <button type="submit" class="btn btn-light btn-sm">Calificaciones</button>
          </form>
        <?php endif; ?>
      </div>
      <div class="d-flex align-items-center">
        <i class="bi bi-person-circle text-light me-2" style="font-size: 1.5em;"></i>
        <span class="me-3 text-light fw-bold">Bienvenido, <?php echo $_SESSION['usuario']; ?></span>
        <form action="../../backend/scripts/cerrar_sesion.php" method="POST">
          <button class="btn btn-warning btn-sm" type="submit">Cerrar Sesión</button>
        </form>
      </div>
    </div>
  </div>
</nav>


</body>
</html>
