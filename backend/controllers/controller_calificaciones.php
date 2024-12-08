<?php
include_once('../../database/conexion_bd_proyecto.php');
class CalificacionDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new ConexionBDEscuela();
    }

    public function guardarCalificacion($numeroDeControl, $idAsignatura, $puntaje) {
        $sql = "INSERT INTO calificacion (Numero_de_control, ID_asignatura, Puntaje) 
                VALUES (?, ?, ?)";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("sid", $numeroDeControl, $idAsignatura, $puntaje); // Cambiado a "sid"
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
    
}
?>
