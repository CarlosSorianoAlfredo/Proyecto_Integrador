<?php

    include_once('../database/conexion_bd_escuela.php');

    $con = new conexionBDEscuela();

    $conexion = $con -> getConexion();

    //var_dump($conexion);

    //METODOS HTTP DE PETICION: POST, GET, PUT, PATCH, DELETE
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //
        $cadenaJSON = file_get_contents('php://input');//Extraer la cadena JSON de la peticion

        if($cadenaJSON == false){

            echo "No hay cadena JSON";

        }else{
            
            $datos_alumno = json_decode($cadenaJSON, true); // se carga la cadena JSON que viene desde los datos de android

            $nc = $datos_alumno['nc'];
            $n = $datos_alumno['n'];
            $pa = $datos_alumno['pa'];
            $sa= $datos_alumno['sa'];
            $e= $datos_alumno['e'];
            $s= $datos_alumno['s'];
            $c= $datos_alumno['c'];

            //insercion directa del modelo DAO
            $sql = "INSERT INTO alumnos VALUES ('$nc','$n','$pa' ,'$sa' ,'$e' ,'$s' ,'$c')";
            $res = mysqli_query($conexion, $sql);

            //configurar RESPUESTA JSON (RESPONSE)
            $respuesta = array();
            if($res){
                $respuesta['alta'] = 'exito';
                
            }else{
                $respuesta['alta'] = 'error';
                    
            }

            $respuestaJSON = json_encode($respuesta);

            echo $respuestaJSON;

        }

    }

?>