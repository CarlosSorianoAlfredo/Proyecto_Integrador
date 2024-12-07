<?php
$usuario = $_POST['caja_usuario'];
$password = $_POST['caja_password'];

echo $usuario;
echo $password;


//Proceso de validacion 


//Proceso de VERIFICACION de U y P en BD
include_once('../../database/Conexion_BD_Usuarios.php');
$con = new ConexionBDUsuarios();
$conexion = $con->getConexion();

//var_dump($conexion);


if($conexion){

   
    // $sql = "SELECT * FROM usuarios WHERE Nombre_Usuario='$usuario' AND Password='$password'";     SIN CIFRAR
    $u_cifrado = sha1($usuario);
    $p_cifrado = sha1($password);

    $sql = "SELECT * FROM usuarios WHERE Nombre_Usuario='$u_cifrado' AND Password='$p_cifrado'"; 
   $res = mysqli_query($conexion,$sql);

    if(mysqli_num_rows($res)==1){
        // echo "Usuario encontrado";

        session_start(); //SIEMPRE INVOCAR ANTES DE TODO CODIGO HTML

        //echo session_id();

        $_SESSION['valida'] = true;
        $_SESSION['usuario'] = $usuario;

        header('location: ../pages/Menu_principal.php');

    }else{
        echo "Usuario NO encontrado";
    }

}else{
    echo "Error en la conexion ";
}
?>