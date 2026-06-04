<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

// Leer el JSON recibido
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan credenciales']);
    exit();
}

$email = trim($input['email']);
$password = $input['password'];

try {
    // Buscar usuario por correo
    $stmt = $pdo->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE correo = :correo");
    $stmt->execute(['correo' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Login exitoso
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['usuario_rol'] = $user['rol'];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login exitoso',
            'rol' => $user['rol']
        ]);
    } else {
        // Credenciales inválidas
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error de servidor.']);
}
?>
