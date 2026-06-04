<?php
require_once '../config/db.php';
require_once '../config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../includes/PHPMailer/Exception.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';

header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

if (!isset($input['email'])) {
    echo json_encode(['success' => false, 'message' => 'Correo es requerido']);
    exit();
}

$email = trim($input['email']);

try {
    // Verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT id, nombre FROM usuarios WHERE correo = :correo");
    $stmt->execute(['correo' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar un token único y seguro
        $token = bin2hex(random_bytes(32));
        // Expiración: 1 hora
        $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token en la base de datos
        $stmtUpdate = $pdo->prepare("UPDATE usuarios SET token_recuperacion = :token, token_expiracion = :expiracion WHERE id = :id");
        $stmtUpdate->execute([
            'token' => $token,
            'expiracion' => $expiracion,
            'id' => $user['id']
        ]);

        // Construir URL de recuperación (ajustar protocolo/dominio en producción)
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        // Asumiendo que el proyecto está en la raíz o subcarpeta, se extrae de la URI actual:
        $baseDir = dirname($_SERVER['REQUEST_URI'], 2); // Subimos 2 niveles porque estamos en /api
        if ($baseDir == '\\' || $baseDir == '/') $baseDir = '';
        
        $resetLink = $protocol . $domainName . $baseDir . "/restablecer.php?token=" . $token;

        // Enviar Correo usando PHPMailer
        $mail = new PHPMailer(true);

        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // O ENCRYPTION_STARTTLS si es puerto 587
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // Destinatarios
        $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
        $mail->addAddress($email, $user['nombre']);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña - SMPC';
        $mail->Body    = "
            <h2>Hola {$user['nombre']},</h2>
            <p>Has solicitado restablecer tu contraseña en el Sistema Municipal de Participación Ciudadana.</p>
            <p>Por favor, haz clic en el siguiente enlace para crear una nueva contraseña. Este enlace es válido por 1 hora.</p>
            <p><a href='{$resetLink}' style='background-color:#0f5132; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; display:inline-block;'>Restablecer Contraseña</a></p>
            <br>
            <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
            <p>Atentamente,<br>Alcaldía de Girardota</p>
        ";

        try {
            $mail->send();
            // Siempre mostramos éxito aunque el correo no exista por seguridad
            echo json_encode(['success' => true, 'message' => 'Si el correo existe, hemos enviado un enlace de recuperación.']);
        } catch (Exception $e) {
            // Error enviando el correo (por configuración incorrecta probablemente)
            // Solo para debug en desarrollo se podría mostrar $mail->ErrorInfo
            echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo. Verifica la configuración SMTP.']);
        }
    } else {
        // Por seguridad, no revelamos si el correo existe o no
        echo json_encode(['success' => true, 'message' => 'Si el correo existe, hemos enviado un enlace de recuperación.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error de servidor.']);
}
?>
