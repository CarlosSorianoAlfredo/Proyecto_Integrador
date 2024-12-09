<?php
include_once('../../backend/fachadas/fachada_alumno.php');
include_once('../../backend/models/model_alumno.php');
session_start();

// Recibir datos del formulario
$num_control = $_POST['caja_num_control'] ?? '';
$nombre = $_POST['caja_nombre'] ?? '';
$primerAp = $_POST['inputPrimerAp'] ?? '';
$segundoAp = $_POST['inputSegundoAp'] ?? '';
$fechaNacimiento = $_POST['inputFechaNacimiento'] ?? '';
$semestre = $_POST['inputSemestre'] ?? '';
$idCarrera = $_POST['inputCarrera'] ?? '';
$idTutor = $_POST['selectTutor'] ?? null; // Puede ser null si no se selecciona tutor

// Guardar datos en sesión para mantenerlos en caso de error
$_SESSION['nc'] = $num_control;
$_SESSION['nombre'] = $nombre;
$_SESSION['primerAp'] = $primerAp;
$_SESSION['segundoAp'] = $segundoAp;
$_SESSION['fechaNacimiento'] = $fechaNacimiento;
$_SESSION['semestre'] = $semestre;
$_SESSION['carrera'] = $idCarrera;
$_SESSION['id_tutor'] = $idTutor;

$datos_correctos = true;

// Validaciones
if (empty($num_control) || !preg_match('/^[a-zA-Z0-9]+$/', $num_control)) {
    $_SESSION['error_nc'] = "El número de control debe ser un valor alfanumérico válido.";
    $datos_correctos = false;
}

if (empty($nombre)) {
    $_SESSION['error_nombre'] = "El nombre no puede estar vacío.";
    $datos_correctos = false;
}

if (empty($primerAp)) {
    $_SESSION['error_primerAp'] = "El primer apellido no puede estar vacío.";
    $datos_correctos = false;
}

if (empty($segundoAp)) {
    $_SESSION['error_segundoAp'] = "El segundo apellido no puede estar vacío.";
    $datos_correctos = false;
}

if (empty($fechaNacimiento) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNacimiento)) {
    $_SESSION['error_fechaNacimiento'] = "La fecha de nacimiento debe tener el formato AAAA-MM-DD.";
    $datos_correctos = false;
}

if (empty($semestre) || !is_numeric($semestre) || $semestre < 1 || $semestre > 12) {
    $_SESSION['error_semestre'] = "El semestre debe estar entre 1 y 12.";
    $datos_correctos = false;
}

if (empty($idCarrera)) {
    $_SESSION['error_carrera'] = "Debe seleccionar una carrera.";
    $datos_correctos = false;
}

// Si hay errores, redirigir al formulario
if (!$datos_correctos) {
    header('Location: ../../frontend/pages/formulario_altas.php');
    exit();
}

// Crear el objeto Alumno
$alumno = new Alumno(
    $num_control,
    $nombre,
    $primerAp,
    $segundoAp,
    $fechaNacimiento,
    $semestre,
    $idCarrera,
    $idTutor
);

// Usar Fachada para registrar el alumno
$fachadaAlumno = new Fachada_Alumno();
try {
    $res = $fachadaAlumno->registrarAlumno($alumno);
    if ($res) {
        $_SESSION['mensaje'] = "Registro AGREGADO Correctamente!";
        // Limpiar errores y datos temporales de sesión
        unset(
            $_SESSION['error_nc'], $_SESSION['error_nombre'], $_SESSION['error_primerAp'], 
            $_SESSION['error_segundoAp'], $_SESSION['error_fechaNacimiento'], $_SESSION['error_semestre'], 
            $_SESSION['error_carrera'], $_SESSION['nc'], $_SESSION['nombre'], $_SESSION['primerAp'], 
            $_SESSION['segundoAp'], $_SESSION['fechaNacimiento'], $_SESSION['semestre'], $_SESSION['carrera'], 
            $_SESSION['id_tutor']
        );
    } else {
        $_SESSION['mensaje'] = "Error al agregar el registro.";
    }
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
}

// Redirigir al formulario
header('Location: ../../frontend/pages/formulario_altas.php');
exit();
?>
