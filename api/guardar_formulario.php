<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if (!$data || (!isset($data['ejercicio_id']) && !isset($data['id']))) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['usuario_rol'];

try {
    // Validaciones de enfoque diferencial (Obligatorios y numéricos)
    $enfoques = ['enfoque_afro', 'enfoque_indigena', 'enfoque_campesino', 'enfoque_discapacidad', 'enfoque_victima', 'enfoque_lgbti', 'enfoque_otro'];
    foreach ($enfoques as $enf) {
        if (!isset($data[$enf]) || !is_numeric($data[$enf]) || (int)$data[$enf] < 0) {
            echo json_encode(['success' => false, 'message' => "El campo $enf es obligatorio y debe ser un número válido (0 o mayor)."]);
            exit;
        }
    }

    $id = isset($data['id']) ? (int)$data['id'] : null;
    $ejercicio_id = isset($data['ejercicio_id']) ? (int)$data['ejercicio_id'] : null;

    if ($id) {
        // Verificar existencia y ejercicio_id
        $stmt_exist = $pdo->prepare("SELECT ejercicio_id FROM formularios_caracterizacion WHERE id = :id");
        $stmt_exist->execute(['id' => $id]);
        $exist = $stmt_exist->fetch(PDO::FETCH_ASSOC);
        if (!$exist) {
            echo json_encode(['success' => false, 'message' => 'Formulario no encontrado']);
            exit;
        }
        $ejercicio_id = $exist['ejercicio_id'];
    }

    // Verificar permisos
    if ($rol !== 'admin') {
        $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
        $stmt_check->execute(['uid' => $usuario_id, 'eid' => $ejercicio_id]);
        if ($stmt_check->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            exit;
        }
    }

    $campos = [
        'dependencia', 'funcionario_nombre', 'funcionario_cargo', 'funcionario_telefono', 'funcionario_correo',
        'objetivo', 'metodologia', 'act_fecha', 'act_zona_rural', 'act_zona_urbana',
        'genero_hombre', 'genero_mujer', 
        'edad_0_5', 'edad_6_11', 'edad_12_18', 'edad_19_26', 'edad_27_59', 'edad_60_mas',
        'enfoque_afro', 'enfoque_indigena', 'enfoque_campesino', 'enfoque_discapacidad', 'enfoque_victima', 'enfoque_lgbti', 'enfoque_otro', 'enfoque_otro_desc',
        'total_participantes', 'instancias', 'organizaciones',
        'aportes', 'actuaciones', 'canales_info', 'lecciones', 'buenas_practicas'
    ];

    $params = [];
    foreach ($campos as $campo) {
        $params[$campo] = isset($data[$campo]) ? $data[$campo] : null;
    }
    
    // Si la fecha está vacía, la guardamos como null
    if (empty($params['act_fecha'])) {
        $params['act_fecha'] = null;
    }

    if ($id) {
        // UPDATE
        $setStr = implode(', ', array_map(function($c) { return "$c = :$c"; }, $campos));
        $params['id'] = $id;
        $sql = "UPDATE formularios_caracterizacion SET $setStr WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'message' => 'Formulario actualizado correctamente', 'id' => $id]);
    } else {
        // INSERT
        $campos_insert = array_merge(['ejercicio_id', 'usuario_id'], $campos);
        $params['ejercicio_id'] = $ejercicio_id;
        $params['usuario_id'] = $usuario_id;
        $placeholders = implode(', ', array_map(function($c) { return ":$c"; }, $campos_insert));
        $sql = "INSERT INTO formularios_caracterizacion (" . implode(', ', $campos_insert) . ") VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $new_id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'message' => 'Formulario creado correctamente', 'id' => $new_id]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar en base de datos: ' . $e->getMessage()]);
}
?>
