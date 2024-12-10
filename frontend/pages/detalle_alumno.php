<?php
// Incluir conexión y lógica de datos
include '../../backend/controllers/controller_alumno.php';

if (isset($_GET['nc'])) {
    $numControl = $_GET['nc']; // Obtener el número de control desde la URL
    $alumnoDAO = new AlumnoDAO();
    $alumno = $alumnoDAO->obtenerAlumnoPorNumControl2($numControl);
    $promedio = $alumnoDAO->obtenerPromedioAlumno($numControl); // Obtener el promedio

    if ($alumno) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalle del Alumno</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <div class="container mt-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-center">Detalle del Alumno</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th scope="row" class="bg-light">Número de Control</th>
                                <td><?= $alumno['Numero_Control'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Nombre</th>
                                <td><?= $alumno['Nombre_Alumno'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Primer Apellido</th>
                                <td><?= $alumno['Primer_Apellido_Alumno'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Segundo Apellido</th>
                                <td><?= $alumno['Segundo_Apellido_Alumno'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Fecha de Nacimiento</th>
                                <td><?= $alumno['Fecha_Nacimiento'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Semestre</th>
                                <td><?= $alumno['Semestre_Alumno'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Carrera</th>
                                <td><?= isset($alumno['Carrera']) ? $alumno['Carrera'] : 'Sin carrera asignada' ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Tutor</th>
                                <td><?= isset($alumno['Tutor']) ? $alumno['Tutor'] : 'Sin tutor asignado' ?></td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Materias y Calificaciones</th>
                                <td>
                                    <?php 
                                    if (isset($alumno['Materias_y_Calificaciones'])) {
                                        $materiasCalificaciones = explode(',', $alumno['Materias_y_Calificaciones']);
                                        foreach ($materiasCalificaciones as $materiaCalificacion) {
                                            echo $materiaCalificacion . '<br>';
                                        }
                                    } else {
                                        echo 'Sin materias registradas';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="bg-light">Promedio(Desde Function)</th>
                                <td><?= $promedio !== null ? number_format($promedio, 2) : 'Sin promedio disponible' ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-center">
                    <a href="bajas_cambios.php" class="btn btn-secondary">Cerrar</a>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "<div class='alert alert-danger text-center mt-4'>No se encontró información para el alumno con el número de control proporcionado.</div>";
    }
} else {
    echo "<div class='alert alert-warning text-center mt-4'>No se proporcionó un número de control válido.</div>";
}
?>
