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

    <!-- Modal for File Upload -->
    <div class="modal-overlay" id="upload-modal" style="z-index: 2000;">
        <div class="modal-content" style="max-width: 500px;">
            <button class="modal-close" onclick="cerrarModalSubida()"><i class="fa-solid fa-xmark"></i></button>
            <div class="modal-header">
                <h2 style="font-size: 1.3rem;"><i class="fa-solid fa-cloud-arrow-up"></i> Subir Archivo</h2>
                <span class="modal-badge" id="upload-folder-badge" style="background: var(--primary-light); color: var(--primary-color);">Carpeta</span>
            </div>
            <div class="modal-body">
                <form id="upload-form">
                    <input type="hidden" id="upload_ejercicio_id" name="ejercicio_id">
                    <input type="hidden" id="upload_carpeta" name="carpeta_destino">
                    
                    <div style="background: #f8fafc; border: 2px dashed var(--border-color); border-radius: 12px; padding: 30px; text-align: center; margin-bottom: 20px;">
                        <i class="fa-solid fa-file-arrow-up" style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
                        <input type="file" id="archivo_upload" name="archivo" accept=".pdf,.jpg,.jpeg,.png" required style="display:block; margin: 0 auto 15px auto; max-width:100%;">
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                            Formatos permitidos: <strong>PDF, JPG, PNG</strong><br>
                            Peso máximo: <strong>5 MB</strong>
                        </p>
                    </div>

                    <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px; border-radius: 4px 8px 8px 4px; margin-bottom: 20px; font-size: 0.85rem; color: #92400e;">
                        <i class="fa-solid fa-lightbulb" style="color: #f59e0b;"></i> 
                        ¿Tu archivo es muy pesado? Comprímelo gratis en 
                        <a href="https://www.ilovepdf.com/compress_pdf" target="_blank" style="color: #b45309; font-weight: bold; text-decoration: underline;">iLovePDF</a> antes de subirlo.
                    </div>

                    <div id="upload-mensaje" style="display:none; padding: 10px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 15px;"></div>

                    <button type="submit" id="btn-upload-submit" style="width: 100%; padding: 12px; background: var(--primary-color); color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-upload"></i> Guardar Archivo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
