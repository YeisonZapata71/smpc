<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

// Verificar que sea Administrador
if ($_SESSION['usuario_rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit();
}

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        // Listar todos los usuarios
        $stmt = $pdo->query("SELECT id, nombre, correo, rol, creado_en FROM usuarios ORDER BY id DESC");
        $usuarios = $stmt->fetchAll();
        
        // Cargar los ejercicios asignados para cada usuario
        foreach($usuarios as &$user) {
            $stmtAsig = $pdo->prepare("SELECT ejercicio_id FROM usuarios_ejercicios WHERE usuario_id = :uid");
            $stmtAsig->execute(['uid' => $user['id']]);
            $user['ejercicios'] = $stmtAsig->fetchAll(PDO::FETCH_COLUMN);
        }
        
        echo json_encode(['success' => true, 'usuarios' => $usuarios]);
    } 
    elseif ($method === 'POST') {
        // Crear un nuevo usuario
        $input = json_decode(file_get_contents('php://input'), true);
        $nombre = trim($input['nombre'] ?? '');
        $correo = trim($input['correo'] ?? '');
        $password_plana = trim($input['password'] ?? '');
        $ejercicios = $input['ejercicios'] ?? []; // Array de IDs de ejercicios
        
        if(empty($nombre) || empty($correo) || empty($password_plana)){
            echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
            exit();
        }
        
        // Verificar si el correo ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        if($stmt->fetch()){
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
            exit();
        }
        
        $hash = password_hash($password_plana, PASSWORD_DEFAULT);
        
        $pdo->beginTransaction();
        $stmtInsert = $pdo->prepare("INSERT INTO usuarios (nombre, correo, password, rol) VALUES (:nombre, :correo, :password, 'user')");
        $stmtInsert->execute(['nombre' => $nombre, 'correo' => $correo, 'password' => $hash]);
        $newUserId = $pdo->lastInsertId();
        
        // Asignar ejercicios
        if(!empty($ejercicios) && is_array($ejercicios)){
            $stmtAsig = $pdo->prepare("INSERT INTO usuarios_ejercicios (usuario_id, ejercicio_id) VALUES (:uid, :eid)");
            foreach($ejercicios as $eid){
                $stmtAsig->execute(['uid' => $newUserId, 'eid' => $eid]);
            }
        }
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
    }
    elseif ($method === 'PUT') {
        // Actualizar asignación de ejercicios de un usuario (para simplificar, borramos y reinsertamos)
        $input = json_decode(file_get_contents('php://input'), true);
        $usuario_id = $input['usuario_id'] ?? null;
        $ejercicios = $input['ejercicios'] ?? [];
        
        if(!$usuario_id){
            echo json_encode(['success' => false, 'message' => 'Falta el ID del usuario']);
            exit();
        }
        
        $pdo->beginTransaction();
        $stmtDel = $pdo->prepare("DELETE FROM usuarios_ejercicios WHERE usuario_id = :uid");
        $stmtDel->execute(['uid' => $usuario_id]);
        
        if(!empty($ejercicios) && is_array($ejercicios)){
            $stmtAsig = $pdo->prepare("INSERT INTO usuarios_ejercicios (usuario_id, ejercicio_id) VALUES (:uid, :eid)");
            foreach($ejercicios as $eid){
                $stmtAsig->execute(['uid' => $usuario_id, 'eid' => $eid]);
            }
        }
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Permisos actualizados.']);
    }
    elseif ($method === 'DELETE') {
        // Eliminar usuario
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        
        if(!$id || $id == $_SESSION['usuario_id']){
            echo json_encode(['success' => false, 'message' => 'No se puede eliminar este usuario.']);
            exit();
        }
        
        $stmtDel = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmtDel->execute(['id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado.']);
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Error de servidor: ' . $e->getMessage()]);
}
?>
