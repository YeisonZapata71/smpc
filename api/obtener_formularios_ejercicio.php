<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_GET['ejercicio_id'])) {
    echo json_encode([]);
    exit;
}

$ejercicio_id = (int) $_GET['ejercicio_id'];
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['usuario_rol'];

try {
    // Si es usuario normal, validamos que tenga acceso al ejercicio.
    if ($rol !== 'admin') {
        $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
        $stmt_check->execute(['uid' => $usuario_id, 'eid' => $ejercicio_id]);
        if ($stmt_check->rowCount() === 0) {
            echo json_encode([]);
            exit;
        }
    }

    $stmt = $pdo->prepare("SELECT id, funcionario_nombre, fecha_creacion FROM formularios_caracterizacion WHERE ejercicio_id = :eid ORDER BY fecha_creacion DESC");
    $stmt->execute(['eid' => $ejercicio_id]);
    $formularios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($formularios);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
