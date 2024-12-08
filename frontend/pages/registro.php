<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Tutorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles/style_registro.css">
</head>
<body>
<div class="container mt-5">
    <?php
    session_start();
    if (isset($_SESSION['mensaje'])) {
        echo "<div class='alert alert-success text-center'>" . $_SESSION['mensaje'] . "</div>";
        unset($_SESSION['mensaje']);
    }
    ?>
    <h2 class="text-center">Registro para Tutorías</h2>
    <form action="../../backend/controllers/procesar_alta_usuario.php" method="POST">
        <div class="form-group mb-3">
            <label for="caja_usuario">Nombre de usuario:</label>
            <input type="text" id="caja_usuario" name="caja_usuario" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="caja_password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Tipo de Usuario:</label>
            <div class="form-check">
                <input type="radio" id="coordinador" name="tipo_usuario" value="coordinador" class="form-check-input" required>
                <label for="coordinador" class="form-check-label">Coordinador de Tutorías</label>
            </div>
            <div class="form-check">
                <input type="radio" id="tutor" name="tipo_usuario" value="tutor" class="form-check-input">
                <label for="tutor" class="form-check-label">Tutor</label>
            </div>
            <div class="form-check">
                <input type="radio" id="invitado" name="tipo_usuario" value="invitado" class="form-check-input">
                <label for="invitado" class="form-check-label">Invitado</label>
            </div>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Registrarme</button>
        </div>
    </form>
</div>
</body>
</html>
