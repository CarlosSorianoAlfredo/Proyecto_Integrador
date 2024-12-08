<?php

include_once('../database/conexion_bd_escuela.php');

$con = new conexionBDEscuela();

$conexion = $con -> getConexion();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cadenaJSON = file_get_contents('php://input');

    if($cadenaJSON == false){

        echo "No hay cadena JSON";

    }else{

        $datos_alumno = json_decode($cadenaJSON, true);

        $nc_cambios = $datos_alumno['nc'];
        $n_cambios = $datos_alumno['n'];
        $pa_cambios = $datos_alumno['pa'];
        $sa_cambios= $datos_alumno['sa'];
        $e_cambios= $datos_alumno['e'];
        $s_cambios= $datos_alumno['s'];
        $c_cambios= $datos_alumno['c'];

        

        $dato_correctos = false;

        if (isset($nc_cambios, $n_cambios, $pa_cambios, $sa_cambios, $e_cambios, $s_cambios, $c_cambios) && 
        !empty($nc_cambios) && !empty($n_cambios) && !empty($pa_cambios) && !empty($sa_cambios) && !empty($e_cambios) &&
         !empty($s_cambios) && !empty($c_cambios) && is_numeric($nc_cambios) && is_numeric($e_cambios) && is_numeric($s_cambios)) {

        $dato_correctos=true;

        }

        if($dato_correctos){

            $sql = "UPDATE alumnos SET Nombre = '$n_cambios', Primer_Ap = '$pa_cambios', Segundo_Ap = '$sa_cambios', Edad = '$e_cambios', Semestre = '$s_cambios', Carrera = '$c_cambios' WHERE Num_Control = '$nc_cambios';";
            $res = mysqli_query($conexion, $sql);
            
            if($res){

                $respuesta['cambios'] = "exito";
                $respuesta['mensaje'] = "El registro con numero de control $nc_cambios fue modificado de manera correcta.";

            }else {

                $respuesta['cambios'] = "error";
                $respuesta['mensaje'] = "El registro no pudo ser modificado.";

            }
        }
    }

    echo json_encode($respuesta);

}

?>