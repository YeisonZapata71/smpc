<?php
// includes/auth_check.php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Si no está logueado, redirigir al login
    header("Location: login.php");
    exit();
}
?>
