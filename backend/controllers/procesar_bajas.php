<?php

include ('controller_alumno.php');
$alumnoDAO = new AlumnoDAO();
//var_dump($_GET['nc']);
if($alumnoDAO->eliminarAlumno($_GET['nc'])){
   // echo"EXITO!!!!!";
   header('location:../pages/bajas_cambios.php');
}else{
   echo "Mejor me voy a LA =(";
}

?>