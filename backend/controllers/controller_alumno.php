<?php
include('../../database/conexion_bd_escuela.php');

class AlumnoDAO {
    private $conexion;

    public function __construct() {   
        $this->conexion = new ConexionBDEscuela(); // Asignación correcta a $this->conexion
    }

    //===========================METODOS ABCC(CRUD)====================================

    //----------------------------ALTAS---------------------------------
    public function agregarAlumno($nc) {
        $sql = "INSERT INTO alumnos VALUES('$nc', '1', '1', '1', '1', '1', '1')";
        $res = mysqli_query($this->conexion->getConexion(), $sql);
        return $res;
    }
    //----------------------------BAJAS---------------------------------
    public function eliminarAlumno($nc) {
        $sql = "DELETE FROM alumnos WHERE Num_Control = '$nc'";
        $res = mysqli_query($this->conexion->getConexion(),$sql);
        return $res;
    }
    //----------------------------CAMBIOS---------------------------------
    //----------------------------CONSULTAS---------------------------------
    public function mostrarAlumnos($filtro){
        $sql="SELECT * FROM  alumnos";
        $res = mysqli_query($this->conexion->getConexion(),$sql);
        return $res;

    }
}
?>
