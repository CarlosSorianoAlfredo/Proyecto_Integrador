<?php
include_once('../../backend/controllers/controller_alumno.php');
session_start();
include '../../backend/scripts/auth.php';

$alumnoDAO = new AlumnoDAO();
$alumnos = $alumnoDAO->mostrarAlumnos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style _lista_alumnos.css">

</head>
<body>
    <?php include_once('menu_principal.php'); ?>

    <div class="container mt-5">
        <h2>Lista de Alumnos</h2>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número de Control</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= $alumno['Num_Control'] ?></td>
                    <td><?= $alumno['Nombre'] ?> <?= $alumno['Primer_Apellido'] ?> <?= $alumno['Segundo_Apellido'] ?></td>
                    <td>
                        <a href="formulario_calificaciones.php?numeroDeControl=<?= $alumno['Num_Control'] ?>" class="btn btn-primary btn-sm">
                            Asignar Calificaciones
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
