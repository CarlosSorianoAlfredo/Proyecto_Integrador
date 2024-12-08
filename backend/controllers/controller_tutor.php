<?php
include_once('../../database/conexion_bd_proyecto.php');

class TutorDAO {
    private $conexion;

    public function __construct() {
        // Crear una nueva conexión a la base de datos
        $this->conexion = new ConexionBDEscuela(); 
    }

    public function obtenerTutorPorId($id_tutor) {
        $sql = "SELECT id_tutor, nombre, primer_apellido, segundo_apellido, titulo FROM tutor WHERE id_tutor = ?";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id_tutor);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_assoc();
    }
    // Función para obtener los tutores con el título
    public function obtenerTutores() {
        $sql = "SELECT Id_tutor, Nombre, Primer_Apellido, Segundo_Apellido, Titulo 
                FROM tutor";
        
        $res = mysqli_query($this->conexion->getConexion(), $sql);

        $tutores = [];

        // Recorrer las filas obtenidas de la consulta
        while ($fila = mysqli_fetch_assoc($res)) {
            $tutores[] = $fila;
        }

        // Devolver el arreglo con los tutores
        return $tutores;
    }
}
?>
