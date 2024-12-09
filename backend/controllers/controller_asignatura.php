<?php
include_once('../../database/conexion_bd_proyecto.php');
class AsignaturaDAO {
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


    public function obtenerAsignaturasPorCarreraYSemestre($idCarrera, $semestre) {
        $sql = "SELECT ID_asignatura, Nombre_asignatura 
                FROM asignatura 
                WHERE Id_carrera = :idCarrera AND Semestre = :semestre";
        
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bindParam(':idCarrera', $idCarrera, PDO::PARAM_INT);
        $stmt->bindParam(':semestre', $semestre, PDO::PARAM_INT);
        $stmt->execute();
        
        $asignaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $asignaturas;
    }
}
?>
