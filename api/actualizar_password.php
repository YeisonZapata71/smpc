<?php
require_once '../config/db.php';

header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if (!isset($input['token']) || !isset($input['password'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit();
}

$token = $input['token'];
$password = $input['password'];

try {
    // Validar token y expiración
    $stmt = $pdo->prepare("SELECT id, token_expiracion FROM usuarios WHERE token_recuperacion = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $expira = strtotime($user['token_expiracion']);
        if ($expira > time()) {
            // El token es válido, actualizar contraseña
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmtUpdate = $pdo->prepare("UPDATE usuarios SET password = :password, token_recuperacion = NULL, token_expiracion = NULL WHERE id = :id");
            $stmtUpdate->execute([
                'password' => $hash,
                'id' => $user['id']
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'El enlace ha expirado.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Enlace inválido.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error de servidor.']);
}
?>
