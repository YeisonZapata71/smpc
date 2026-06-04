<?php
session_start();
// Si el usuario ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMPC - Inicio de Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: var(--bg-color);
            padding: 20px;
        }
        .login-card {
            background-color: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slideUp 0.5s ease;
        }
        .login-logo {
            width: 180px;
            margin-bottom: 24px;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 8px;
            text-align: center;
        }
        .login-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 32px;
            text-align: center;
            font-weight: 500;
        }
        .form-group {
            width: 100%;
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background-color: var(--bg-color);
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.2s;
            font-family: 'Montserrat', sans-serif;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        .login-btn {
            width: 100%;
            padding: 14px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 81, 50, 0.2);
        }
        .forgot-pwd {
            margin-top: 20px;
            font-size: 0.85rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .forgot-pwd:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        .error-message {
            width: 100%;
            padding: 12px;
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #f87171;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <img src="logo.jpg" alt="Logo Alcaldía" class="login-logo">
            <h1 class="login-title">Acceso Restringido</h1>
            <p class="login-subtitle">Sistema Municipal de Participación Ciudadana</p>
            
            <div class="error-message" id="error-msg"></div>

            <form id="login-form" style="width: 100%;">
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="usuario@ejemplo.com" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                </div>
                <button type="submit" class="login-btn" id="submit-btn">Ingresar al Sistema</button>
            </form>
            
            <a href="recuperar.php" class="forgot-pwd">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('submit-btn');
            const errorMsg = document.getElementById('error-msg');
            
            btn.textContent = 'Verificando...';
            btn.disabled = true;
            errorMsg.style.display = 'none';

            try {
                const response = await fetch('api/auth_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    errorMsg.textContent = data.message || 'Credenciales incorrectas.';
                    errorMsg.style.display = 'block';
                    btn.textContent = 'Ingresar al Sistema';
                    btn.disabled = false;
                }
            } catch (err) {
                errorMsg.textContent = 'Ocurrió un error de conexión.';
                errorMsg.style.display = 'block';
                btn.textContent = 'Ingresar al Sistema';
                btn.disabled = false;
            }
        });
    </script>
</body>
</html>
