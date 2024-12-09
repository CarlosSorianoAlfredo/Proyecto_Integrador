<?php
session_start();

// Obtener los datos del formulario
$usuario = $_POST['caja_name'] ?? null;
$password = $_POST['caja_password'] ?? null;
$captchaResponse = $_POST['recaptcha_response'] ?? null;

// Validar que se recibió el token del CAPTCHA
if (!$captchaResponse) {
    $_SESSION['mensaje'] = 'Error: No se recibió el token del CAPTCHA.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Validar CAPTCHA con reCAPTCHA v3
$secretKey = "6LfXUJYqAAAAAOoCX_7McRwuX5EamOR3mBBgAbsw";
$remoteIp = $_SERVER['REMOTE_ADDR'];
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

// Validar respuesta de CAPTCHA
if (!$result['success'] || $result['score'] < 0.5) { // Ajusta el puntaje según tus necesidades
    $_SESSION['mensaje'] = 'CAPTCHA inválido o interacción sospechosa. Inténtalo de nuevo.';
    header('Location: ../../frontend/pages/login.php');
    exit();
}

// Conectar a la base de datos usando Singleton
include_once('../../database/conexion_bd_usuarios.php');
$con = ConexionBDUsuarios::getInstancia();
$conexion = $con->getConexion();

try {
    // Validar usuario y contraseña
    $u_cifrado = sha1($usuario);
    $p_cifrado = sha1($password);

    $sql = "SELECT u.id_usuario, u.privilegio, p.acceso_menu 
            FROM usuarios u
            JOIN privilegios p ON u.privilegio = p.privilegio
            WHERE u.nombre = :nombre AND u.password = :password";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $u_cifrado);
    $stmt->bindParam(':password', $p_cifrado);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
} catch (PDOException $e) {
    $_SESSION['mensaje'] = 'Error en la consulta a la base de datos: ' . $e->getMessage();
    header('Location: ../../frontend/pages/login.php');
}
?>
