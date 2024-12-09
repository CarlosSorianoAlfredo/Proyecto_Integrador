<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validar si la sesión es válida
if (!isset($_SESSION['valida']) || !$_SESSION['valida']) {
    header('Location: login.php');
    exit();
}

// Variables de acceso adicionales (opcional)
$acceso_menu = $_SESSION['acceso_menu'] ?? [];
?>
