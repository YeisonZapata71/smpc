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

        // Enviar correo de bienvenida
        require_once '../includes/PHPMailer/PHPMailer.php';
        require_once '../includes/PHPMailer/SMTP.php';
        require_once '../includes/PHPMailer/Exception.php';
        require_once '../config/mail.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = SMTP_PORT;

            // Remitente y destinatario
            $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
            $mail->addAddress($correo, $nombre);
            $mail->CharSet = 'UTF-8';

            // Determinar la URL del sitio
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domain = $_SERVER['HTTP_HOST'];
            $app_url = $protocol . "://" . $domain . "/smpc";

            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido al SMPC - Alcaldía de Girardota';
            
            $html_body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f4f7f6; padding: 20px;'>
                <div style='background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);'>
                    <div style='background-color: #16a34a; padding: 20px; text-align: center; color: white;'>
                        <h1 style='margin: 0; font-size: 24px;'>Bienvenido al SMPC</h1>
                        <p style='margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;'>Sistema Municipal de Participación Ciudadana</p>
                    </div>
                    <div style='padding: 30px;'>
                        <h2 style='color: #1f2937; margin-top: 0;'>Hola, {$nombre}</h2>
                        <p style='color: #4b5563; line-height: 1.6;'>
                            Nos complace darte la bienvenida al <strong>Sistema Municipal de Participación Ciudadana (SMPC)</strong> de la Alcaldía de Girardota.
                        </p>
                        <p style='color: #4b5563; line-height: 1.6;'>
                            Esta plataforma está diseñada para facilitar el seguimiento, gestión documental y caracterización de todos los ejercicios participativos de nuestro municipio, permitiendo una administración transparente y eficiente.
                        </p>
                        <div style='background-color: #f8fafc; border-left: 4px solid #16a34a; padding: 15px; margin: 25px 0; border-radius: 4px;'>
                            <h3 style='margin-top: 0; color: #1f2937; font-size: 16px;'>Tus credenciales de acceso:</h3>
                            <p style='margin: 5px 0; color: #4b5563;'><strong>Usuario/Correo:</strong> {$correo}</p>
                            <p style='margin: 5px 0; color: #4b5563;'><strong>Contraseña:</strong> {$password_plana}</p>
                        </div>
                        <div style='text-align: center; margin: 35px 0;'>
                            <a href='{$app_url}' style='background-color: #16a34a; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-weight: bold; display: inline-block;'>Ingresar a la Plataforma</a>
                        </div>
                        <p style='color: #9ca3af; font-size: 12px; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 15px;'>
                            Te recomendamos cambiar tu contraseña al ingresar por primera vez.<br>
                            ¡Girardota, Te Queremos!
                        </p>
                    </div>
                </div>
            </div>";

            $mail->Body = $html_body;
            $mail->send();
        } catch (Exception $e) {
            // Ignoramos el error para no romper la creación del usuario, pero se podría loguear.
        }
        
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente y correo enviado.']);
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
