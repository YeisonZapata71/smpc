<?php
// config/db.php

$host = 'localhost';
$dbname = 'smpc_db'; // Cambiar por el nombre de tu base de datos en Hostinger
$username = 'root';  // Cambiar por tu usuario de DB
$password = '';      // Cambiar por tu contraseña de DB

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Configurar el modo de fetch por defecto a arreglo asociativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
