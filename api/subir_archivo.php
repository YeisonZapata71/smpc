<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

if (!isset($_POST['ejercicio_id']) || !isset($_POST['carpeta_destino']) || !isset($_FILES['archivo'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$ejercicio_id = (int) $_POST['ejercicio_id'];
$carpeta_destino = $_POST['carpeta_destino'];
$archivo = $_FILES['archivo'];

$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['usuario_rol'];

// Validar acceso al ejercicio si no es admin
if ($rol !== 'admin') {
    $stmt_check = $pdo->prepare("SELECT 1 FROM usuarios_ejercicios WHERE usuario_id = :uid AND ejercicio_id = :eid");
    $stmt_check->execute(['uid' => $usuario_id, 'eid' => $ejercicio_id]);
    if ($stmt_check->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
        exit;
    }
}

// Validaciones del Archivo
$max_size = 5 * 1024 * 1024; // 5 MB
$allowed_mimes = ['application/pdf', 'image/jpeg', 'image/png'];
$allowed_exts = ['pdf', 'jpg', 'jpeg', 'png'];

if ($archivo['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Error al subir el archivo (Código: ' . $archivo['error'] . ')']);
    exit;
}

if ($archivo['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'El archivo supera el límite de 5 MB.']);
    exit;
}

// Validar tipo Mime real
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $archivo['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowed_mimes)) {
    echo json_encode(['success' => false, 'message' => 'Formato de archivo no permitido. Solo PDF, JPG y PNG.']);
    exit;
}

// Preparar directorio
$upload_dir = '../uploads/' . $ejercicio_id . '/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generar nombre único para evitar sobreescrituras
$ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
$safe_name = preg_replace('/[^A-Za-z0-9_-]/', '_', pathinfo($archivo['name'], PATHINFO_FILENAME));
$new_filename = $safe_name . '_' . time() . '.' . $ext;
$target_file = $upload_dir . $new_filename;

if (move_uploaded_file($archivo['tmp_name'], $target_file)) {
    try {
        $ruta_relativa = 'uploads/' . $ejercicio_id . '/' . $new_filename;
        
        $sql = "INSERT INTO archivos_ejercicios (ejercicio_id, carpeta_destino, nombre_original, ruta_servidor, tipo_archivo, tamano_bytes) 
                VALUES (:eid, :carpeta, :nombre, :ruta, :tipo, :tamano)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'eid' => $ejercicio_id,
            'carpeta' => $carpeta_destino,
            'nombre' => $archivo['name'],
            'ruta' => $ruta_relativa,
            'tipo' => $mime,
            'tamano' => $archivo['size']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Archivo subido correctamente']);
    } catch (PDOException $e) {
        // Fallback: Si falla BD, borramos el archivo subido
        unlink($target_file);
        echo json_encode(['success' => false, 'message' => 'Error de BD al guardar referencia']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al mover el archivo en el servidor.']);
}
?>
