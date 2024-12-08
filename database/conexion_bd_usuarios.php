<?php
class ConexionBDUsuarios{
    private $conexion;
    private $host = "localhost:3308";
    private $usuario="victorcastro";
    private $password ="itsj";
    private $bd = "bd_usuarios_proyecto";

    public function __construct(){
        $this->conexion=mysqli_connect($this->host,$this->usuario,$this->password,$this->bd);

        if(!$this->conexion)
        die("Error en conexion a la BD: ".mysqli_connect_error());
    }


    public function getConexion(){
        return $this->conexion;
    }
}

?>