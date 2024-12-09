<?php
session_start();

$usuario = $_POST['caja_name'] ?? null;
$password = $_POST['caja_password'] ?? null;
$captchaResponse = $_POST['recaptcha_response'] ?? null;

if (!$captchaResponse) {
    $_SESSION['mensaje'] = 'Error: No se recibió el token del CAPTCHA.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Clave secreta de reCAPTCHA v3
$secretKey = "6LfXUJYqAAAAAOoCX_7McRwuX5EamOR3mBBgAbsw";
$remoteIp = $_SERVER['REMOTE_ADDR'];

// Validar el token con Google
$url = "https://www.google.com/recaptcha/api/siteverify";
$data = [
    'secret' => $secretKey,
    'response' => $captchaResponse,
    'remoteip' => $remoteIp,
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$result = json_decode($response, true);

// Verifica el éxito del reCAPTCHA y el puntaje
if (!$result['success'] || $result['score'] < 0.5) { // Ajusta el puntaje según tu tolerancia
    $_SESSION['mensaje'] = 'CAPTCHA inválido o interacción sospechosa. Inténtalo de nuevo.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Conexión a la base de datos
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
    header('Location: ../../frontend/pages/login.php'); 
}
?>
