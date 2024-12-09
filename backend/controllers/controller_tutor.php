<?php
include_once('../../database/conexion_bd_proyecto.php');

class TutorDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = ConexionBDEscuela::getInstancia()->getConexion();
    }
    public static function getInstancia() {
        if (!isset(self::$instancia)) {
            self::$instancia = new AlumnoDAO();
        }
        return self::$instancia;
    }


    public function obtenerTutorPorId($id_tutor) {
        $sql = "SELECT id_tutor, nombre, primer_apellido, segundo_apellido, titulo FROM tutor WHERE id_tutor = :id_tutor";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bindParam(':id_tutor', $id_tutor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTutores() {
        $sql = "SELECT id_tutor, nombre, primer_apellido, segundo_apellido, titulo FROM tutor";
        $stmt = $this->conexion->getConexion()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
