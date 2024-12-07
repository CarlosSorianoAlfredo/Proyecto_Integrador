<?php
include ('controller_alumno.php');
echo"ALTAS";
//1.-Obtener informacion de las cajas
$num_control=$_POST['caja_num_control'];
//2.-Validar la informacion
$dato_correctos = false;
if(isset($num_control)&& !empty($num_control)&&is_numeric($num_control)){
    $dato_correctos=true;
}
//3.-mandarseloas al controlador
if($dato_correctos){
    $alumnoDAO = new  AlumnoDAO();
    $res = $alumnoDAO->agregarAlumno($num_control);

    if($res)
    //echo"Registro Agregado Correctamente";
    header('location:../pages/formulario_altas.php');
    else
    echo"Mejor me dedico a las redes =(";

}



?>