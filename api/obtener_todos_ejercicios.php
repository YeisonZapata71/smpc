<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $stmtSectores = $pdo->query("SELECT id, nombre FROM sectores ORDER BY nombre");
    $sectores = $stmtSectores->fetchAll();
    
    $stmtEjercicios = $pdo->query("SELECT id, nombre, sector_id FROM ejercicios ORDER BY nombre");
    $ejercicios = $stmtEjercicios->fetchAll();
    
    $agrupados = [];
    foreach($sectores as $sector){
        $agrupados[$sector['id']] = [
            'nombre' => $sector['nombre'],
            'ejercicios' => []
        ];
    }
    
    foreach($ejercicios as $ejercicio){
        if(isset($agrupados[$ejercicio['sector_id']])){
            $agrupados[$ejercicio['sector_id']]['ejercicios'][] = [
                'id' => $ejercicio['id'],
                'nombre' => $ejercicio['nombre']
            ];
        }
    }
    
    echo json_encode(['success' => true, 'data' => array_values($agrupados)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al cargar ejercicios']);
}
?>
