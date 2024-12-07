<!--   
   Funciones MYSQLi

   Funciones PDO
   Permiten cambiar la conectividad a diversos gestores de BD

-->
<?php

class ConexionBDEscuela{
    private $conexion;
    private $host = "localhost:3308";
    private $usuario="root";
    private $password ="";
    private $bd = "BD_Escuela_Web_2024";

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