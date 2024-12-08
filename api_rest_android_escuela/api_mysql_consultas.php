<?php

include_once("../database/conexion_bd_escuela.php");

$con = new ConexionBDEscuela();
$conexion = $con->getConexion();

if($_SERVER['REQUEST_METHOD']=='POST'){

    $cadenaJSON = file_get_contents('php://input');

   //if($cadenaJSON== false){
     //   echo"NO hay cadena JSON";
    //}else{
        //$consulta_filtros = json_decode($cadenaJSON,true);
        //$filtro_nc=$consulta_filtros['filtro_nc'];
        //$filtro_n=$consulta_filtros['filtro_n'];
        //$filtro_pa=$consulta_filtros['filtro_pa'];
        //$filtro_sa=$consulta_filtros['filtro_sa'];
        //$filtro_e=$consulta_filtros['filtro_e'];
        //$filtro_s=$consulta_filtros['filtro_s'];
        //$filtro_c=$consulta_filtros['filtro_c'];
        

        $sql = "SELECT * FROM ALUMNOS";
        $res = mysqli_query($conexion, $sql);

        $respuesta ['alumnos'] = array();
        
        if($res){

            while($fila = mysqli_fetch_assoc($res)){
                $alum = array();
                $alumno['nc']= $fila['Num_control'];
                $alumno['n'] = $fila['Nombre'];
                $alumno['pa'] = $fila['Primer_Ap'];
                $alumno['sa'] = $fila['Segundo_Ap'];  
                $alumno['e'] = $fila['Edad'];   
                $alumno['s'] = $fila['Semestre'];   
                $alumno['c'] = $fila['Carrera']; 
                array_push($respuesta['alumnos'],$alumno);
            }
            $respuesta['consulta']='exito';
        } else{
            $respuesta['consulta']='no hay registros';
        }
        $respuestaJSON = json_encode($respuesta);

        echo $respuestaJSON;

   // }
}
?>