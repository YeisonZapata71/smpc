<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $rol = $_SESSION['usuario_rol'];
    $usuario_id = $_SESSION['usuario_id'];
    
    // Obtener los datos con la estructura similar a data.json
    // { "Ejercicio": "Nombre del sector", "Tipo": "Nombre del Ejercicio", "Sector": "ID_Ejercicio" }
    
    $sql = "SELECT e.id as Sector, e.nombre as Tipo, s.nombre as Ejercicio 
            FROM ejercicios e
            JOIN sectores s ON e.sector_id = s.id";
            
    if ($rol !== 'admin') {
        $sql .= " JOIN usuarios_ejercicios ue ON e.id = ue.ejercicio_id WHERE ue.usuario_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $usuario_id]);
    } else {
        $stmt = $pdo->query($sql);
    }
    
    $datos = $stmt->fetchAll();
    
    // Devolvemos el array directamente tal como lo esperaba script.js de data.json
    echo json_encode($datos);

} catch (Exception $e) {
    echo json_encode([]);
}
?>
