<?php
require_once 'config/db.php';
$token = $_GET['token'] ?? '';
$validToken = false;
$userId = null;

if (!empty($token)) {
    // Validar el token
    $stmt = $pdo->prepare("SELECT id, token_expiracion FROM usuarios WHERE token_recuperacion = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        $expira = strtotime($user['token_expiracion']);
        $ahora = time();
        if ($expira > $ahora) {
            $validToken = true;
            $userId = $user['id'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMPC - Restablecer Contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: var(--bg-color); padding: 20px; }
        .login-card { background-color: var(--panel-bg); border: 1px solid var(--border-color); border-radius: 20px; box-shadow: var(--shadow-lg); width: 100%; max-width: 450px; padding: 40px; display: flex; flex-direction: column; align-items: center; }
        .login-title { font-size: 1.5rem; font-weight: 800; color: var(--primary-color); margin-bottom: 8px; text-align: center; }
        .login-subtitle { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 32px; text-align: center; font-weight: 500; }
        .form-group { width: 100%; margin-bottom: 20px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--text-main); margin-bottom: 8px; }
        .form-input { width: 100%; padding: 14px 16px; border: 1px solid var(--border-color); border-radius: 10px; background-color: var(--bg-color); color: var(--text-main); font-size: 0.95rem; font-family: 'Montserrat', sans-serif; }
        .login-btn { width: 100%; padding: 14px; background: var(--gradient-primary); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .msg-box { width: 100%; padding: 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; margin-bottom: 20px; display: none; text-align: center; }
        .msg-error { background-color: #fee2e2; color: #b91c1c; border: 1px solid #f87171; display: block; }
        .msg-success { background-color: #dcfce7; color: #15803d; border: 1px solid #4ade80; }
        .back-link { margin-top: 20px; font-size: 0.85rem; color: var(--text-muted); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1 class="login-title">Crear Nueva Contraseña</h1>
            
            <?php if (!$validToken): ?>
                <div class="msg-box msg-error">El enlace es inválido o ha expirado. Por favor, solicita uno nuevo.</div>
                <a href="recuperar.php" class="back-link">Volver a solicitar enlace</a>
            <?php else: ?>
                <p class="login-subtitle">Ingresa tu nueva contraseña a continuación.</p>
                <div class="msg-box" id="msg-box"></div>
                
                <form id="reset-form" style="width: 100%;">
                    <input type="hidden" id="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="form-group">
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" id="password" class="form-input" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmar Contraseña</label>
                        <input type="password" id="confirm_password" class="form-input" required minlength="6">
                    </div>
                    <button type="submit" class="login-btn" id="submit-btn">Guardar Contraseña</button>
                </form>
                <a href="login.php" class="back-link" style="display:none;" id="login-link">Ir a Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($validToken): ?>
    <script>
        document.getElementById('reset-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const token = document.getElementById('token').value;
            const btn = document.getElementById('submit-btn');
            const msgBox = document.getElementById('msg-box');
            
            if (password !== confirm) {
                msgBox.textContent = 'Las contraseñas no coinciden.';
                msgBox.className = 'msg-box msg-error';
                msgBox.style.display = 'block';
                return;
            }

            btn.textContent = 'Guardando...';
            btn.disabled = true;

            try {
                const response = await fetch('api/actualizar_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ token, password })
                });
                
                const data = await response.json();
                
                msgBox.textContent = data.message;
                msgBox.className = 'msg-box ' + (data.success ? 'msg-success' : 'msg-error');
                msgBox.style.display = 'block';
                
                if (data.success) {
                    document.getElementById('reset-form').style.display = 'none';
                    document.getElementById('login-link').style.display = 'block';
                } else {
                    btn.textContent = 'Guardar Contraseña';
                    btn.disabled = false;
                }
            } catch (err) {
                msgBox.textContent = 'Error de conexión.';
                msgBox.className = 'msg-box msg-error';
                msgBox.style.display = 'block';
                btn.disabled = false;
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
