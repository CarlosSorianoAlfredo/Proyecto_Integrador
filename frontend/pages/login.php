

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style_login.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6LfXUJYqAAAAAGwoAAiLTXXbvhagcjNCWwusY5yR"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6LfXUJYqAAAAAGwoAAiLTXXbvhagcjNCWwusY5yR', { action: 'login' }).then(function (token) {
                document.getElementById('recaptchaResponse').value = token;
            });
        });
    </script>
</head>
<body>
    <div class="container">
    <?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'sesion_expirada') {
    echo "<div class='alert alert-warning'>Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.</div>";
}
?>
        <?php
        session_start();
        if (isset($_SESSION['mensaje'])) {
            echo "<div id='alertMessage' class='alert alert-success text-center'>" . $_SESSION['mensaje'] . "</div>";
            unset($_SESSION['mensaje']); 
        }
        ?>

        <div class="card">
            <div class="card-header">
                <h4>Iniciar Sesión</h4>
            </div>
            <div class="card-body">
                <form action="../../backend/controllers/validar_usuario.php" method="POST">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Ingresa tu nombre de usuario" name="caja_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Contraseña" name="caja_password" required>
                    </div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    <div class="form-group form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Recuérdame</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </form>
            </div>
            <div class="card-footer">
                <p class="mb-0">¿No tienes una cuenta? <a href="Registro.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>

    <?php
    if (isset($_SESSION['mensaje'])) {
        echo "<script type='text/javascript'>
                setTimeout(function() {
                    document.getElementById('alertMessage').style.display = 'none';
                }, 5000);
              </script>";
        unset($_SESSION['mensaje']); 
    }
    ?>
</body>
</html>
