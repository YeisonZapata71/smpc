<?php require_once 'includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMPC - Alcaldía de Girardota</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Se usa Montserrat como alternativa geométrica a Isidora Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header" id="sidebar-home" style="cursor:pointer" title="Ir al Dashboard">
                <div class="logo">
                    <img src="logo.jpg" alt="Logo" class="logo-img" />
                </div>
            </div>
            <div class="slogan-container">
                <div class="slogan-badge">
                    <span>¡Girardota,</span>
                    <strong>Te Queremos!</strong>
                    <i class="fa-solid fa-heart"></i>
                </div>
            </div>
            <div class="sidebar-menu" id="sector-menu">
                <!-- Sectors will be injected here -->
            </div>
            
            <div style="padding: 16px; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 8px;">
                <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-align: center; margin-bottom: 8px;">
                    <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                </div>
                <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                <a href="admin/usuarios.php" class="sector-item" style="text-decoration:none; padding: 10px;">
                    <i class="fa-solid fa-gear"></i><span>Administración</span>
                </a>
                <?php endif; ?>
                <a href="api/auth_logout.php" class="sector-item" style="text-decoration:none; padding: 10px; color: #ef4444;">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-header">
                <div class="header-title">
                    <h1 id="current-sector-title">Seleccione un Sector</h1>
                    <p>Sistema Municipal de Participación Ciudadana</p>
                </div>
                <div class="header-actions">
                    <button class="theme-toggle" id="theme-toggle" title="Cambiar tema">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                </div>
            </header>

            <div class="content-area" id="exercises-container">
                <!-- Dashboard rendered by JS -->
            </div>
        </main>
    </div>

    <!-- Modal for Folder Structure -->
    <div class="modal-overlay" id="folder-modal">
        <div class="modal-content">
            <button class="modal-close" id="modal-close"><i class="fa-solid fa-xmark"></i></button>
            <div class="modal-header">
                <h2 id="modal-exercise-title">Nombre del Ejercicio</h2>
                <span class="modal-badge" id="modal-sector-badge">Sector</span>
            </div>
            <div class="modal-body">
                <div class="folder-browser">
                    <!-- Folders injected here -->
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
