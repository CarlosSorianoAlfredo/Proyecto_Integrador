<?php
session_start();

$usuario = $_POST['caja_name'];
$password = $_POST['caja_password'];

include_once('../../database/conexion_bd_usuarios.php');
$con = new ConexionBDUsuarios();
$conexion = $con->getConexion();

if ($conexion) {
    $u_cifrado = sha1($usuario);
    $p_cifrado = sha1($password);
    $sql = "SELECT u.id_usuario, u.privilegio, p.acceso_menu 
            FROM usuarios u
            JOIN privilegios p ON u.privilegio = p.privilegio
            WHERE u.nombre = '$u_cifrado' AND u.password = '$p_cifrado'";
    $res = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
    
        $_SESSION['valida'] = true;
        $_SESSION['usuario'] = $usuario;
        $_SESSION['privilegio'] = $row['privilegio'];
        $_SESSION['acceso_menu'] = json_decode($row['acceso_menu'], true) ?? []; 
        header('Location: ../../frontend/pages/inicio.php');
    } else {
        $_SESSION['valida'] = false; 
        unset($_SESSION['acceso_menu']);
        $_SESSION['mensaje'] = 'Usuario o contraseña incorrectos. Por favor, intentalo de nuevo.';
        header('Location: ../../frontend/pages/login.php');
    }
} else {
    $_SESSION['mensaje'] = 'Error al conectar a la base de datos.';
    header('Location: ../../frontend/pages/inicio.php'); 
}
?>

