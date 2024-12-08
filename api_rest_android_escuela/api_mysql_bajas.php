<?php
include_once("../database/conexion_bd_escuela.php");
$con = new conexionBDEscuela();

$conexion = $con -> getConexion();


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cadenaJSON = file_get_contents('php://input');

    if($cadenaJSON == false){

        echo "No hay cadena JSON";

    }else{

        $datos_alumno = json_decode($cadenaJSON, true);

        if(isset($datos_alumno['num_control'])){

            $num_control = $datos_alumno['num_control'];

            $sql = "DELETE FROM alumnos WHERE Num_Control = '$num_control'";
            $res = mysqli_query($conexion, $sql);

            if ($res) {

                $respuesta['consulta'] = "exito";
                $respuesta['mensaje'] = "El registro con numero de control $num_control fue eliminado.";

            } else {

                $respuesta['consulta'] = "error";
                $respuesta['mensaje'] = "Error al eliminar el registro: " . mysqli_error($conexion);

            }
        
        } else {

            $respuesta['consulta'] = "error";
            $respuesta['mensaje'] = "El campo 'num_control' es obligatorio.";

        }
    
        echo json_encode($respuesta);
    }

    } else {

        echo json_encode(["consulta" => "Metodo no permitido"]);

}

?>