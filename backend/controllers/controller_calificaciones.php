<?php
include_once('../../database/conexion_bd_proyecto.php');

class CalificacionDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBDEscuela();
    }

    public function guardarCalificacion($numeroDeControl, $idAsignatura, $puntaje) {
        $sql = "INSERT INTO calificacion (Numero_de_control, ID_asignatura, Puntaje) 
                VALUES (:numeroDeControl, :idAsignatura, :puntaje)";
        
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bindParam(':numeroDeControl', $numeroDeControl, PDO::PARAM_STR);
        $stmt->bindParam(':idAsignatura', $idAsignatura, PDO::PARAM_INT);
        $stmt->bindParam(':puntaje', $puntaje, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function obtenerCalificacionesPorAlumno($numeroDeControl) {
        $sql = "SELECT ID_asignatura, Puntaje 
                FROM calificacion 
                WHERE Numero_de_control = :numeroDeControl";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bindParam(':numeroDeControl', $numeroDeControl, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
