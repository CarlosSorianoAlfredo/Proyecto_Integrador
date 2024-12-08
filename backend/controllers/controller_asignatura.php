<?php
include_once('../../database/conexion_bd_proyecto.php');
class AsignaturaDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBDEscuela(); 
    }

    public function obtenerAsignaturasPorCarreraYSemestre($idCarrera, $semestre) {
        $sql = "SELECT ID_asignatura, Nombre_asignatura 
                FROM asignatura 
                WHERE Id_carrera = $idCarrera AND Semestre = $semestre";
    
        $res = mysqli_query($this->conexion->getConexion(), $sql);
    
        $asignaturas = [];
        while ($fila = mysqli_fetch_assoc($res)) {
            $asignaturas[] = $fila;
        }
        return $asignaturas;
    }
    
}
?>