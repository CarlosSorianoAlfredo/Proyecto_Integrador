<?php
// Validar sesión
include '../../backend/scripts/auth.php';
include_once('../../backend/controllers/controller_alumno.php');
include_once('menu_principal.php');

$alumnoDAO = new AlumnoDAO();
$tutores = $alumnoDAO->obtenerTutores();
$carreras = $alumnoDAO->obtenerCarreras(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style_altas.css">

    <style>.alert {
    transition: opacity 1s ease-out;
    }

    .alert.fade-out {
        opacity: 0;
    }
    </style>
 </head>
<body>
    <?php include_once('menu_principal.php'); ?>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="text-center mb-4">Registrar Nuevo Alumno</h2>

            <!-- Mostrar mensajes de éxito o error -->
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-info text-center"><?= $_SESSION['mensaje'] ?></div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>

            <form action="../../backend/controllers/procesar_alta_alumno.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="caja_num_control" class="form-label">Número de Control</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="caja_num_control" 
                            name="caja_num_control" 
                            placeholder="Solo números"
                            value="<?= $_SESSION['nc'] ?? '' ?>"
                            required>
                        <div class="text-danger"><?= $_SESSION['error_nc'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="caja_nombre" class="form-label">Nombre</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="caja_nombre" 
                            name="caja_nombre" 
                            value="<?= $_SESSION['nombre'] ?? '' ?>"
                            required>
                        <div class="text-danger"><?= $_SESSION['error_nombre'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="inputPrimerAp" class="form-label">Primer Apellido</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="inputPrimerAp" 
                            name="inputPrimerAp" 
                            value="<?= $_SESSION['primerAp'] ?? '' ?>"
                            required>
                        <div class="text-danger"><?= $_SESSION['error_primerAp'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="inputSegundoAp" class="form-label">Segundo Apellido</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="inputSegundoAp" 
                            name="inputSegundoAp" 
                            value="<?= $_SESSION['segundoAp'] ?? '' ?>"
                            required>
                        <div class="text-danger"><?= $_SESSION['error_segundoAp'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="inputFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="inputFechaNacimiento" 
                            name="inputFechaNacimiento" 
                            value="<?= $_SESSION['fechaNacimiento'] ?? '' ?>"
                            required>
                        <div class="text-danger"><?= $_SESSION['error_fechaNacimiento'] ?? '' ?></div>
                    </div>

                    <div class="col-md-6">
                        <label for="inputSemestre" class="form-label">Semestre</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="inputSemestre" 
                            name="inputSemestre" 
                            min="1" 
                            max="12" 
                            placeholder="Semestre"
                            value="<?= $_SESSION['semestre'] ?? '' ?>"
                            required>
                        <div class="text-danger"><?= $_SESSION['error_semestre'] ?? '' ?></div>
                    </div>

                    <div class="col-md-12">
                        <label for="inputCarrera" class="form-label">Carrera</label>
                        <select class="form-select" id="inputCarrera" name="inputCarrera" required>
                        <option value="" disabled selected>Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): ?>
                         <option value="<?= $carrera['Id_carrera'] ?>" <?= (isset($_SESSION['carrera']) && $_SESSION['carrera'] == $carrera['Id_carrera']) ? 'selected' : '' ?>>
                         <?= $carrera['Nombre_carrera'] ?>
                         </option>
                         <?php endforeach; ?>
                        </select>
                        <div class="text-danger"><?= $_SESSION['error_carrera'] ?? '' ?></div>
                    </div>

                    <div class="col-md-12">
                        <label for="selectTutor" class="form-label">Tutor Asignado</label>
                        <?php if (!empty($tutores)): ?>
                            <select class="form-select" id="selectTutor" name="selectTutor">
                                <option value="" selected disabled>Seleccione un tutor</option>
                                <?php foreach ($tutores as $tutor): ?>
                                    <option value="<?= $tutor['id_tutor'] ?>" <?= (isset($_SESSION['id_tutor']) && $_SESSION['id_tutor'] == $tutor['id_tutor']) ? 'selected' : '' ?>>
                                    <?= $tutor['titulo'] ?><?= $tutor['nombre'] ?> <?= $tutor['primer_apellido'] ?> <?= $tutor['segundo_apellido'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p class="text-warning">No hay tutores disponibles en la base de datos.</p>
                        <?php endif; ?>
                        <div class="text-danger"><?= $_SESSION['error_tutor'] ?? '' ?></div>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-submit mt-3">Registrar Alumno</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const alertElement = document.querySelector(".alert");

        if (alertElement) {
            setTimeout(() => {
                alertElement.classList.add("fade-out");
                setTimeout(() => {
                    alertElement.remove();
                }, 1000);
            }, 5000); 
        }
    });
</script>

</body>
</html>
