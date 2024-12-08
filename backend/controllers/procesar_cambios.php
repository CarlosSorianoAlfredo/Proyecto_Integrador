<?php
include_once('../models/model_alumno.php');
include_once('../controllers/controller_alumno.php');

session_start(); // Iniciar sesión para almacenar mensajes de estado

// Verificar si los datos fueron enviados desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num_control = $_POST['numControl'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $primerAp = $_POST['primerAp'] ?? null;
    $segundoAp = $_POST['segundoAp'] ?? null;
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? null;
    $semestre = $_POST['semestre'] ?? null;
    $carrera_nombre = $_POST['carrera'] ?? null; // Nombre de la carrera recibido
    $enRiesgo = $_POST['enRiesgo'] ?? null;
    $tutor_nombre_completo = $_POST['tutor'] ?? null; // Nombre completo del tutor recibido

    // Validar datos básicos
    $datos_correctos = true;
    if (
        empty($num_control) || !is_numeric($num_control) ||
        empty($nombre) || empty($primerAp) || empty($segundoAp) ||
        empty($fechaNacimiento) || !strtotime($fechaNacimiento) ||
        empty($semestre) || !is_numeric($semestre) || $semestre < 1 || $semestre > 12 ||
        empty($carrera_nombre) || empty($enRiesgo) || empty($tutor_nombre_completo)
    ) {
        $datos_correctos = false;
    }

    if ($datos_correctos) {
        $alumnoDAO = new AlumnoDAO();
    
        // Obtener el ID de la carrera
        $idCarrera = $alumnoDAO->obtenerIdCarreraPorAbreviatura($carrera_nombre);
        
        // Obtener el ID del tutor
        $idTutor = $alumnoDAO->obtenerIdTutorPorNombre($tutor_nombre_completo);
    
        /*echo "Carrera ingresada: $carrera_nombre<br>";
        echo "Tutor ingresado: $tutor_nombre_completo<br>";
        echo "ID Carrera encontrado: " . var_export($idCarrera, true) . "<br>";
        echo "ID Tutor encontrado: " . var_export($idTutor, true) . "<br>";*/
    
        if ($idCarrera !== null && $idTutor !== null) {
            $alumno = new Alumno(
                $num_control,
                $nombre,
                $primerAp,
                $segundoAp,
                $fechaNacimiento,
                $semestre,
                $idCarrera,
                $idTutor,
                $enRiesgo
            );
    
            $res = $alumnoDAO->actualizarAlumno($alumno);
    
            if ($res) {
                $_SESSION['mensaje'] = "Registro actualizado correctamente.";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el registro. Por favor, inténtelo de nuevo.";
            }
        } else {
            $_SESSION['mensaje'] = "Error: No se pudo encontrar la carrera o el tutor.";
        }
    } else {
        $_SESSION['mensaje'] = "Datos enviados no son válidos. Verifica los datos ingresados.";
    }
} else {
    $_SESSION['mensaje'] = "Método de solicitud no válido.";
}

// Redirigir a la página con el modal
header('Location: ../../frontend/pages/bajas_cambios.php');
exit();
?>
