<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) && !isset($_GET['ejercicio_id'])) {
    echo json_encode(['error' => 'Parámetros faltantes']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['usuario_rol'];

try {
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM formularios_caracterizacion WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $form = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($form) {
            // Verificar acceso al ejercicio
            if ($rol !== 'admin') {
                $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
                $stmt_check->execute(['uid' => $usuario_id, 'eid' => $form['ejercicio_id']]);
                if ($stmt_check->rowCount() === 0) {
                    echo json_encode(['error' => 'Acceso denegado']);
                    exit;
                }
            }
            
            // Obtener info adicional del ejercicio
            $stmt_ej = $pdo->prepare("SELECT e.nombre as Tipo, s.nombre as Ejercicio FROM ejercicios e JOIN sectores s ON e.sector_id = s.id WHERE e.id = :eid");
            $stmt_ej->execute(['eid' => $form['ejercicio_id']]);
            $ej_info = $stmt_ej->fetch(PDO::FETCH_ASSOC);
            $form['info_ejercicio'] = $ej_info;
            
            echo json_encode($form);
        } else {
            echo json_encode(['error' => 'Formulario no encontrado']);
        }
    } else if (isset($_GET['ejercicio_id'])) {
        $ejercicio_id = (int) $_GET['ejercicio_id'];
        
        // Verificar acceso al ejercicio
        if ($rol !== 'admin') {
            $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
            $stmt_check->execute(['uid' => $usuario_id, 'eid' => $ejercicio_id]);
            if ($stmt_check->rowCount() === 0) {
                echo json_encode(['error' => 'Acceso denegado']);
                exit;
            }
        }
        
        // Obtener info base del ejercicio para pre-llenar un formulario nuevo
        $stmt_ej = $pdo->prepare("SELECT e.nombre as Tipo, s.nombre as Ejercicio FROM ejercicios e JOIN sectores s ON e.sector_id = s.id WHERE e.id = :eid");
        $stmt_ej->execute(['eid' => $ejercicio_id]);
        $ej_info = $stmt_ej->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode(['is_new' => true, 'ejercicio_id' => $ejercicio_id, 'info_ejercicio' => $ej_info]);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de BD']);
}
?>
