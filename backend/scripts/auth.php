<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$tiempo_limite_inactividad = 300;

// Verificar si la sesión ya tiene un tiempo de actividad registrado
if (isset($_SESSION['ultimo_acceso'])) {
    $inactividad = time() - $_SESSION['ultimo_acceso']; // Calcular tiempo de inactividad
    
    // Si el tiempo de inactividad excede el límite, cerrar la sesión
    if ($inactividad > $tiempo_limite_inactividad) {
        session_unset(); // Destruir variables de sesión
        session_destroy(); // Destruir la sesión
        header('Location: login.php?mensaje=sesion_expirada'); // Redirigir al login con mensaje
        exit();
    }
}

// Actualizar el tiempo del último acceso
$_SESSION['ultimo_acceso'] = time();

// Validar si la sesión es válida
if (!isset($_SESSION['valida']) || !$_SESSION['valida']) {
    header('Location: login.php');
    exit();
}

// Variables de acceso adicionales (opcional)
$acceso_menu = $_SESSION['acceso_menu'] ?? [];
?>
