<?php
include_once('../../database/conexion_bd_usuarios.php');
include_once("../models/model_usuario.php");

class UsuarioDAO {
    private $conexion;

    // Constructor que obtiene la conexión desde el Singleton
    public function __construct() {
        $this->conexion = ConexionBDUsuarios::getInstancia()->getConexion();
    }

    //----------------------------ALTAS---------------------------------
    public function agregarUsuario($usuario) {
        $nombreUsuario = $usuario->getNombreUsuario();
        $password = $usuario->getPassword();
        $privilegio = $usuario->getPrivilegio();

        $sql = "INSERT INTO usuarios (nombre, password, privilegio) 
                VALUES (SHA1(:nombre), SHA1(:password), :privilegio)";

        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':nombre', $nombreUsuario, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':privilegio', $privilegio, PDO::PARAM_STR);

            $res = $stmt->execute();
            return $res;
        } catch (PDOException $e) {
            // Manejo de errores
            error_log("Error en agregarUsuario: " . $e->getMessage());
            return false;
        }
    }

    
    //----------------------------BAJAS---------------------------------
    
    //----------------------------CAMBIOS---------------------------------
   
    //----------------------------CONSULTAS---------------------------------
    
}
?>
