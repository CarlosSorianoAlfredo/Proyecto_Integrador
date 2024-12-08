<?php
include_once('controller_usuario.php');
include_once("../models/model_usuario.php");

$nombre_usuario = $_POST['caja_usuario'];
$password = $_POST['caja_password'];
$tipo_usuario = $_POST['tipo_usuario']; 

$datos_correctos = false;
if (
    isset($nombre_usuario, $password, $tipo_usuario) &&
    !empty($nombre_usuario) && !empty($password) && !empty($tipo_usuario)
) {
    $datos_correctos = true;
}

session_start();

if ($datos_correctos) {
    $usuario = new Usuario($nombre_usuario, $password, $tipo_usuario); 
    $usuarioDAO = new UsuarioDAO();
    $res = $usuarioDAO->agregarUsuario($usuario);

    if ($res) {
        $_SESSION['mensaje'] = "Usuario AGREGADO Correctamente!";
        header('Location: ../../frontend/pages/login.php'); 
    } else {
        $_SESSION['mensaje'] = "Error al agregar usuario";
        header('Location: ../../frontend/pages/Registro.php'); 
    }
    exit();
} else {
    $_SESSION['mensaje'] = "Datos de usuario incompletos o incorrectos";
    header('Location: ../../frontend/pages/Registro.php'); 
    exit();
}
?>
