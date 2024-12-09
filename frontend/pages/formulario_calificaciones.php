<?php
include_once('../../backend/controllers/controller_asignatura.php');
include_once('../../backend/controllers/controller_alumno.php');
session_start();

include '../../backend/scripts/auth.php';
if (empty($_GET['numeroDeControl'])) {
    $_SESSION['mensaje'] = "No se especificó un número de control.";
    header('Location: lista_alumnos.php');
    exit();
}

$numeroDeControl = $_GET['numeroDeControl'];
$alumnoDAO = new AlumnoDAO();
$alumno = $alumnoDAO->obtenerAlumnoPorNumeroDeControl($numeroDeControl);

// Validar si existe el alumno
if (!$alumno) {
    $_SESSION['mensaje'] = "El alumno no existe.";
    header('Location: lista_alumnos.php');
    exit();
}

// Obtener asignaturas y calificaciones del alumno
$asignaturaDAO = new AsignaturaDAO();
$asignaturas = $asignaturaDAO->obtenerAsignaturasPorCarreraYSemestre($alumno->getCarrera, $alumno->getSemestre);

$calificacionesRegistradas = $alumnoDAO->obtenerCalificacionesPorAlumno($numeroDeControl);
$calificacionesMap = [];
foreach ($calificacionesRegistradas as $calificacion) {
    $calificacionesMap[$calificacion['ID_asignatura']] = $calificacion['Puntaje'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Calificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style_form_cali.css">
    <style>
        .alert {
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
        <h2>Asignar Calificaciones a <?= htmlspecialchars($alumno->getNombre . " " . $alumno->getPrimerAp) ?></h2>

        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <form action="../../backend/controllers/procesar_calificaciones.php" method="POST">
            <input type="hidden" name="numeroDeControl" value="<?= htmlspecialchars($numeroDeControl) ?>">

            <?php 
            $faltanCalificaciones = false; 
            foreach ($asignaturas as $asignatura): 
                $idAsignatura = $asignatura['ID_asignatura'];
                $nombreAsignatura = htmlspecialchars($asignatura['Nombre_asignatura']);
                $calificacionExistente = $calificacionesMap[$idAsignatura] ?? null;
            ?>
                <div class="mb-3">
                    <label for="calificacion_<?= htmlspecialchars($idAsignatura) ?>" class="form-label">
                        <?= $nombreAsignatura ?>:
                    </label>
                    <?php if ($calificacionExistente !== null): ?>
                        <!-- Campo deshabilitado con calificación ya registrada -->
                        <input 
                            type="text" 
                            class="form-control" 
                            id="calificacion_<?= htmlspecialchars($idAsignatura) ?>" 
                            value="<?= htmlspecialchars($calificacionExistente) ?>" 
                            disabled>
                    <?php else: ?>
                        <!-- Campo habilitado para nueva calificación -->
                        <input 
                            type="number" 
                            step="1" 
                            min="0" 
                            max="100" 
                            class="form-control" 
                            id="calificacion_<?= htmlspecialchars($idAsignatura) ?>" 
                            name="calificaciones[<?= htmlspecialchars($idAsignatura) ?>]" 
                            placeholder="Calificación">
                        <?php $faltanCalificaciones = true; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="text-center">
                <?php if ($faltanCalificaciones): ?>
                    <button type="submit" class="btn btn-primary">Guardar Calificación Faltante</button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" disabled>Todos los datos están registrados</button>
                <?php endif; ?>
            </div>
        </form>
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
