<?php
include('conexion_bd_escuela.php');
$conexion = new ConexionBDEscuela();
$con=$conexion->getConexion();

var_dump($con);
?>