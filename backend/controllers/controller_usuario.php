<?php
include_once('../../database/conexion_bd_usuarios.php');
include_once("../models/model_usuario.php");

class UsuarioDAO {
    private $conexion;
     
    public function __construct() {
        $this->conexion = ConexionBDEscuela::getInstancia()->getConexion();
    }

    //===========================METODOS ABCC(CRUD)====================================

    //----------------------------ALTAS---------------------------------
    public function agregarUsuario($usuario) {
        $nombreUsuario = $usuario->getNombreUsuario();
        $password = $usuario->getPassword();
        $privilegio = $usuario->getPrivilegio();
    
        $sql = "INSERT INTO usuarios (nombre, password, privilegio) 
        VALUES (SHA1(?), SHA1(?), ?)";
    
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("sss", $nombreUsuario, $password, $privilegio);
    
        $res = $stmt->execute();
        $stmt->close();
    
        return $res;
    }
    
    //----------------------------BAJAS---------------------------------
    
    //----------------------------CAMBIOS---------------------------------
   
    //----------------------------CONSULTAS---------------------------------
    
}
?>
