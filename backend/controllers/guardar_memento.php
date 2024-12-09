<?php
// Mostrar errores en pantalla (deshabilitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar la sesión
session_start();

// Incluir la conexión a la base de datos
include_once('../../database/conexion_bd_proyecto.php');

// Crear una instancia de la conexión PDO a través del Singleton
$conn = ConexionBDEscuela::getInstancia()->getConexion();

// Habilitar modo de errores en PDO
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Verificar que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que se envíen los datos necesarios
    if (!empty($_POST['numControl'])) {
        $numControl = $_POST['numControl'];

        try {
            // Iniciar la transacción
            $conn->beginTransaction();

            // Obtener los datos del alumno antes de eliminarlo
            $querySelect = "SELECT * FROM alumno WHERE Num_Control = :numControl";
            $stmtSelect = $conn->prepare($querySelect);
            $stmtSelect->bindParam(':numControl', $numControl, PDO::PARAM_STR);
            $stmtSelect->execute();

            $alumno = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if (!$alumno) {
                throw new Exception("El número de control no existe en la base de datos.");
            }

            // Guardar los datos en la sesión
            $_SESSION['respaldo_alumno'] = $alumno;

            // Eliminar el registro
            $queryDelete = "DELETE FROM alumno WHERE Num_Control = :numControl";
            $stmtDelete = $conn->prepare($queryDelete);
            $stmtDelete->bindParam(':numControl', $numControl, PDO::PARAM_STR);

            if ($stmtDelete->execute()) {
                // Confirmar la transacción
                $conn->commit();

                // Responder con éxito
                echo json_encode(['status' => 'success', 'message' => 'Alumno eliminado correctamente.']);
                
            } else {
                throw new Exception("Error al eliminar el alumno.");
            }
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se recibió un número de control válido.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}

// Cerrar la conexión
$conn = null;
?>
