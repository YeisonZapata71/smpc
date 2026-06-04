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
    $id = isset($data['id']) ? (int)$data['id'] : null;
    $ejercicio_id = isset($data['ejercicio_id']) ? (int)$data['ejercicio_id'] : null;

    if ($id) {
        $stmt_exist = $pdo->prepare("SELECT ejercicio_id FROM formularios_caracterizacion WHERE id = :id");
        $stmt_exist->execute(['id' => $id]);
        $exist = $stmt_exist->fetch(PDO::FETCH_ASSOC);
        if (!$exist) {
            echo json_encode(['success' => false, 'message' => 'Formulario no encontrado']);
            exit;
        }
        $ejercicio_id = $exist['ejercicio_id'];
    }

    if ($rol !== 'admin') {
        $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
        $stmt_check->execute(['uid' => $usuario_id, 'eid' => $ejercicio_id]);
        if ($stmt_check->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            exit;
        }
    }

    $pdo->beginTransaction();

    // 1. Guardar Formulario Principal
    $campos_main = [
        'dependencia', 'funcionario_nombre', 'funcionario_cargo', 'funcionario_telefono', 'funcionario_correo',
        'objetivo', 'metodologia', 'acompanamiento',
        'aportes', 'actuaciones', 'canales_info', 'lecciones', 'buenas_practicas'
    ];

    $params_main = [];
    foreach ($campos_main as $campo) {
        $params_main[$campo] = isset($data[$campo]) ? $data[$campo] : null;
    }

    if ($id) {
        $setStr = implode(', ', array_map(function($c) { return "$c = :$c"; }, $campos_main));
        $params_main['id'] = $id;
        $sql = "UPDATE formularios_caracterizacion SET $setStr WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params_main);
    } else {
        $campos_insert = array_merge(['ejercicio_id', 'usuario_id'], $campos_main);
        $params_main['ejercicio_id'] = $ejercicio_id;
        $params_main['usuario_id'] = $usuario_id;
        $placeholders = implode(', ', array_map(function($c) { return ":$c"; }, $campos_insert));
        $sql = "INSERT INTO formularios_caracterizacion (" . implode(', ', $campos_insert) . ") VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params_main);
        $id = $pdo->lastInsertId();
    }

    // 2. Guardar Actividades
    // Primero, eliminamos las actividades anteriores para este formulario (reemplazo completo)
    $stmt_del = $pdo->prepare("DELETE FROM actividades_caracterizacion WHERE formulario_id = :fid");
    $stmt_del->execute(['fid' => $id]);

    if (isset($data['actividades']) && is_array($data['actividades'])) {
        $campos_act = [
            'act_fecha', 'act_zona_rural', 'act_zona_urbana',
            'genero_hombre', 'genero_mujer', 
            'edad_0_5', 'edad_6_11', 'edad_12_18', 'edad_19_26', 'edad_27_59', 'edad_60_mas',
            'enfoque_afro', 'enfoque_indigena', 'enfoque_campesino', 'enfoque_discapacidad', 'enfoque_victima', 'enfoque_lgbti', 'enfoque_otro', 'enfoque_otro_desc',
            'total_participantes', 'instancias', 'organizaciones'
        ];
        
        $sql_act = "INSERT INTO actividades_caracterizacion (formulario_id, " . implode(', ', $campos_act) . ") VALUES (:formulario_id, " . implode(', ', array_map(function($c) { return ":$c"; }, $campos_act)) . ")";
        $stmt_act = $pdo->prepare($sql_act);

        foreach ($data['actividades'] as $act) {
            $params_act = ['formulario_id' => $id];
            
            // Validación de enfoques
            $enfoques = ['enfoque_afro', 'enfoque_indigena', 'enfoque_campesino', 'enfoque_discapacidad', 'enfoque_victima', 'enfoque_lgbti', 'enfoque_otro'];
            foreach ($enfoques as $enf) {
                if (!isset($act[$enf]) || !is_numeric($act[$enf]) || (int)$act[$enf] < 0) {
                    $pdo->rollBack();
                    echo json_encode(['success' => false, 'message' => "Enfoque diferencial obligatorio en una de las actividades."]);
                    exit;
                }
            }

            foreach ($campos_act as $campo) {
                $params_act[$campo] = isset($act[$campo]) && $act[$campo] !== '' ? $act[$campo] : null;
            }
            
            $stmt_act->execute($params_act);
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Formulario guardado exitosamente', 'id' => $id]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error BD: ' . $e->getMessage()]);
}
?>
