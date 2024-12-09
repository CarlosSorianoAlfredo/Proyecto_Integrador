<?php
include_once('controller_calificaciones.php');
session_start();

$numeroDeControl = $_POST['numeroDeControl'] ?? '';
$calificaciones = $_POST['calificaciones'] ?? [];

// Verificar datos enviados
if (empty($numeroDeControl) || empty($calificaciones)) {
    $_SESSION['mensaje'] = "Faltan datos para procesar.";
    header('Location: ../pages/formulario_calificaciones.php?numeroDeControl=' . urlencode($numeroDeControl));
    exit();
}

$calificacionDAO = new CalificacionDAO();

// Obtener calificaciones ya registradas
$calificacionesExistentes = $calificacionDAO->obtenerCalificacionesPorAlumno($numeroDeControl);
$calificacionesMap = [];
foreach ($calificacionesExistentes as $calificacion) {
    $calificacionesMap[$calificacion['ID_asignatura']] = $calificacion['Puntaje'];
}

$nuevasCalificaciones = [];
foreach ($calificaciones as $idAsignatura => $puntaje) {
    if (!empty($puntaje) && !isset($calificacionesMap[$idAsignatura])) {
        // Solo insertar si la calificación no existe
        $nuevasCalificaciones[$idAsignatura] = $puntaje;
    }
}


if (!empty($nuevasCalificaciones)) {
    foreach ($nuevasCalificaciones as $idAsignatura => $puntaje) {
        $calificacionDAO->guardarCalificacion($numeroDeControl, $idAsignatura, $puntaje); 
    }
    $_SESSION['mensaje'] = "Calificaciones guardadas correctamente.";
} else {
    $_SESSION['mensaje'] = "No se ingresaron nuevas calificaciones.";
}

// Redirigir de nuevo al formulario
header('Location: ../../frontend/pages/formulario_calificaciones.php?numeroDeControl=' . urlencode($numeroDeControl));
exit();
?>
