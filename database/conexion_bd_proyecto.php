<?php
class ConexionBDEscuela {
    private static $instancia = null;
    private $conexion;
    private $host = "localhost";
    private $port = "3308";
    private $usuario = "victorcastro";
    private $password = "itsj";
    private $bd = "EscuelaBDProyecto";

    private function __construct() {
        try {
            $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->bd;charset=utf8";
            $this->conexion = new PDO($dsn, $this->usuario, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en conexión a la BD: " . $e->getMessage());
        }
    }

    // Evitar la clonación de la instancia
    private function __clone() {}

     function __wakeup() {}

    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    // Obtener la conexión de la instancia
    public function getConexion() {
        return $this->conexion;
    }
}
?>
