<?php
session_start();

// Guardar los datos enviados en la sesión.
if (isset($_POST['numControl'])) {
    $_SESSION['ultimo_eliminado'] = [
        'Num_Control' => $_POST['numControl'],
        'Nombre' => $_POST['nombre'],
        'Primer_Apellido' => $_POST['primerAp'],
        'Segundo_Apellido' => $_POST['segundoAp'],
        'Fecha_Nacimiento' => $_POST['fecha'],
        'Semestre' => $_POST['semestre'],
        'Carrera' => $_POST['carrera'],
        'Tutor_id' => $_POST['tutorId'],
        'enRiesgo' => $_POST['enRiesgo']
    ];
    echo "success";
} else {
    echo "error";
}
?>
