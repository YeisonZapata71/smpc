<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_GET['ejercicio_id'])) {
    echo json_encode(['error' => 'ID de ejercicio requerido']);
    exit;
}

$ejercicio_id = (int) $_GET['ejercicio_id'];
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['usuario_rol'];

try {
    if ($rol !== 'admin') {
        $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
        $stmt_check->execute(['uid' => $usuario_id, 'eid' => $ejercicio_id]);
        if ($stmt_check->rowCount() === 0) {
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }
    }

    $stmt = $pdo->prepare("SELECT id, carpeta_destino, nombre_original, ruta_servidor, fecha_subida FROM archivos_ejercicios WHERE ejercicio_id = :eid ORDER BY fecha_subida DESC");
    $stmt->execute(['eid' => $ejercicio_id]);
    $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Agrupar por carpeta_destino para fácil consumo en frontend
    $agrupados = [];
    foreach ($archivos as $arch) {
        $carpeta = $arch['carpeta_destino'];
        if (!isset($agrupados[$carpeta])) {
            $agrupados[$carpeta] = [];
        }
        $agrupados[$carpeta][] = $arch;
    }

    echo json_encode($agrupados);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de BD']);
}
?>
