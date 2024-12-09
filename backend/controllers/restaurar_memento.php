<?php
session_start();
include_once('../../database/conexion_bd_proyecto.php');

if (isset($_SESSION['ultimo_eliminado'])) {
    $alumno = $_SESSION['ultimo_eliminado'];

    $query = "INSERT INTO alumnos (Num_Control, Nombre, Primer_Apellido, Segundo_Apellido, Fecha_Nacimiento, Semestre, Carrera, Tutor_id, enRiesgo)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'sssssssss',
        $alumno['Num_Control'],
        $alumno['Nombre'],
        $alumno['Primer_Apellido'],
        $alumno['Segundo_Apellido'],
        $alumno['Fecha_Nacimiento'],
        $alumno['Semestre'],
        $alumno['Carrera'],
        $alumno['Tutor_id'],
        $alumno['enRiesgo']
    );

    if ($stmt->execute()) {
        unset($_SESSION['ultimo_eliminado']); // Limpiar el memento después de restaurar.
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "No hay datos para restaurar.";
}
?>
