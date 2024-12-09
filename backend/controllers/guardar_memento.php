<?php
// Mostrar errores en pantalla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once('../../database/conexion_bd_proyecto.php');

// Crear una instancia de la conexión PDO
$conexionBD = new ConexionBDEscuela();
$conn = $conexionBD->getConexion();

if (!$conn) {
    die("Error en la conexión a la base de datos: " . $conn->errorInfo()[2]);
}

// Procesar solo solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['numControl'])) {
        $numControl = $_POST['numControl'];

        // Guardar los datos del alumno eliminado en la sesión
        $_SESSION['ultimo_eliminado'] = [
            'Num_Control' => $numControl,
            'Nombre' => $_POST['nombre'] ?? null,
            'Primer_Apellido' => $_POST['primerAp'] ?? null,
            'Segundo_Apellido' => $_POST['segundoAp'] ?? null,
            'Fecha_Nacimiento' => $_POST['fecha'] ?? null,
            'Semestre' => $_POST['semestre'] ?? null,
            'Carrera' => $_POST['carrera'] ?? null,
            'Tutor_id' => $_POST['tutorId'] ?? null,
            'enRiesgo' => $_POST['enRiesgo'] ?? null,
        ];

        // Eliminar el alumno de la base de datos utilizando PDO
        $query = "DELETE FROM alumno WHERE Num_Control = :numControl";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bindParam(':numControl', $numControl, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Alumno eliminado con éxito.";
            } else {
                echo "Error al ejecutar la consulta: " . implode(" - ", $stmt->errorInfo());
            }
        } else {
            echo "Error al preparar la consulta: " . implode(" - ", $conn->errorInfo());
        }
    } else {
        echo "No se recibió un número de control.";
    }
} else {
    echo "Método no permitido.";
}

// Cerrar la conexión PDO
$conn = null;
?>
