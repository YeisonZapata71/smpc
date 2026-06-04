<?php
require_once '../includes/auth_check.php';

// Solo admin puede entrar
if ($_SESSION['usuario_rol'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios - SMPC</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-header { padding: 32px; background: var(--bg-color); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        .admin-title h1 { font-size: 1.8rem; font-weight: 800; color: var(--primary-color); }
        .btn-primary { background: var(--gradient-primary); color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-family: 'Montserrat', sans-serif;}
        .btn-danger { background: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 0.8rem;}
        .btn-edit { background: var(--folder-color); color: var(--bg-color); padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 0.8rem;}
        .users-table-container { padding: 32px; }
        .users-table { width: 100%; border-collapse: collapse; background: var(--panel-bg); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-sm); }
        .users-table th { text-align: left; padding: 16px; background: var(--primary-light); color: var(--primary-color); font-weight: 700; text-transform: uppercase; font-size: 0.8rem; }
        .users-table td { padding: 16px; border-bottom: 1px solid var(--border-color); color: var(--text-main); font-size: 0.9rem; font-weight: 500;}
        .badge { padding: 4px 8px; border-radius: 100px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-admin { background: #dcfce7; color: #15803d; }
        .badge-user { background: #e0f2fe; color: #0369a1; }
        
        /* Modal */
        .modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index: 100; justify-content: center; align-items: center; }
        .modal.active { display: flex; }
        .modal-content { background: var(--panel-bg); width: 90%; max-width: 800px; border-radius: 16px; display: flex; flex-direction: column; max-height: 90vh; }
        .modal-header { padding: 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        .modal-header h2 { font-weight: 700; color: var(--text-main); }
        .modal-body { padding: 24px; overflow-y: auto; }
        .modal-footer { padding: 24px; border-top: 1px solid var(--border-color); text-align: right; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;}
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); }
        .form-group input { padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; font-family: 'Montserrat'; background: var(--bg-color); color: var(--text-main); }
        
        .sector-section { margin-bottom: 16px; border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; background: var(--bg-color); }
        .sector-title { font-weight: 700; font-size: 1rem; color: var(--primary-color); margin-bottom: 12px; }
        .checkbox-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .checkbox-item { display: flex; align-items: flex-start; gap: 8px; font-size: 0.85rem; color: var(--text-main); font-weight: 500;}
        .checkbox-item input { margin-top: 3px; }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Reutilizado (Versión Admin) -->
        <aside class="sidebar">
            <div class="sidebar-header" style="cursor:pointer" onclick="window.location.href='../dashboard.php'">
                <div class="logo">
                    <img src="../logo.jpg" alt="Logo" class="logo-img" />
                </div>
            </div>
            <div class="sidebar-menu">
                <a href="../dashboard.php" class="sector-item" style="text-decoration:none;">
                    <i class="fa-solid fa-chart-line"></i><span>Ir al Dashboard</span>
                </a>
                <a href="usuarios.php" class="sector-item active" style="text-decoration:none;">
                    <i class="fa-solid fa-users"></i><span>Gestión de Usuarios</span>
                </a>
                <a href="../api/auth_logout.php" class="sector-item" style="text-decoration:none; margin-top:auto;">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Cerrar Sesión</span>
                </a>
            </div>
        </aside>

        <!-- Main -->
        <main class="main-content">
            <div class="admin-header">
                <div class="admin-title">
                    <h1>Gestión de Usuarios</h1>
                    <p style="color:var(--text-muted); font-size:0.9rem; font-weight:500; margin-top:4px;">Crea usuarios y asígnales ejercicios participativos.</p>
                </div>
                <div>
                    <button class="btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Nuevo Usuario</button>
                </div>
            </div>

            <div class="users-table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Ejercicios Asignados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="users-tbody">
                        <!-- Llenado por JS -->
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Formulario Usuario -->
    <div class="modal" id="user-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Nuevo Usuario</h2>
                <button style="background:none; border:none; font-size:1.5rem; cursor:pointer;" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="user-form">
                    <input type="hidden" id="user_id">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" id="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" id="correo" required>
                        </div>
                        <div class="form-group" id="pwd-group">
                            <label>Contraseña Temporal</label>
                            <input type="password" id="password">
                            <small style="color:var(--text-muted); font-size:0.75rem;">El usuario podrá cambiarla después. En edición, déjalo en blanco para no cambiarla.</small>
                        </div>
                    </div>

                    <h3 style="margin-bottom:16px; color:var(--text-main); font-weight:700;">Asignar Ejercicios</h3>
                    <div id="exercises-container">
                        <!-- Checkboxes generados por JS -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" style="background:#64748b;" onclick="closeModal()">Cancelar</button>
                <button class="btn-primary" onclick="saveUser()">Guardar Usuario</button>
            </div>
        </div>
    </div>

    <script>
        let allExercises = [];
        let allUsers = [];

        async function loadData() {
            // Cargar ejercicios
            const resEx = await fetch('../api/obtener_todos_ejercicios.php');
            const dataEx = await resEx.json();
            allExercises = dataEx.data || [];
            renderExerciseCheckboxes();

            // Cargar usuarios
            loadUsers();
        }

        async function loadUsers() {
            const res = await fetch('../api/admin_usuarios.php');
            const data = await res.json();
            allUsers = data.usuarios || [];
            
            const tbody = document.getElementById('users-tbody');
            tbody.innerHTML = '';
            
            allUsers.forEach(user => {
                const tr = document.createElement('tr');
                const rolBadge = user.rol === 'admin' ? '<span class="badge badge-admin">Admin</span>' : '<span class="badge badge-user">Usuario</span>';
                
                tr.innerHTML = `
                    <td>${user.nombre}</td>
                    <td>${user.correo}</td>
                    <td>${rolBadge}</td>
                    <td>${user.rol === 'admin' ? 'Todos' : (user.ejercicios ? user.ejercicios.length : 0)}</td>
                    <td>
                        <button class="btn-edit" onclick="editUser(${user.id})" ${user.rol === 'admin' ? 'disabled' : ''}><i class="fa-solid fa-pen"></i> Editar Permisos</button>
                        ${user.rol !== 'admin' ? `<button class="btn-danger" onclick="deleteUser(${user.id})"><i class="fa-solid fa-trash"></i></button>` : ''}
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderExerciseCheckboxes() {
            const container = document.getElementById('exercises-container');
            container.innerHTML = '';
            
            allExercises.forEach(sector => {
                const sectDiv = document.createElement('div');
                sectDiv.className = 'sector-section';
                
                let html = `<div class="sector-title">${sector.nombre}</div><div class="checkbox-grid">`;
                sector.ejercicios.forEach(ex => {
                    html += `
                        <label class="checkbox-item">
                            <input type="checkbox" name="ejercicios[]" value="${ex.id}">
                            ${ex.nombre}
                        </label>
                    `;
                });
                html += '</div>';
                sectDiv.innerHTML = html;
                container.appendChild(sectDiv);
            });
        }

        function openModal() {
            document.getElementById('user-form').reset();
            document.getElementById('user_id').value = '';
            document.getElementById('modal-title').textContent = 'Nuevo Usuario';
            document.getElementById('pwd-group').style.display = 'flex';
            document.getElementById('password').required = true;
            document.getElementById('nombre').disabled = false;
            document.getElementById('correo').disabled = false;
            document.getElementById('user-modal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('user-modal').classList.remove('active');
        }

        function editUser(id) {
            const user = allUsers.find(u => u.id == id);
            if(!user) return;
            
            document.getElementById('user_id').value = user.id;
            document.getElementById('nombre').value = user.nombre;
            document.getElementById('correo').value = user.correo;
            
            document.getElementById('modal-title').textContent = 'Editar Permisos';
            document.getElementById('pwd-group').style.display = 'none'; // Solo admin de permisos por simplicidad
            document.getElementById('password').required = false;
            document.getElementById('nombre').disabled = true; // Solo permisos
            document.getElementById('correo').disabled = true;

            // Marcar checkboxes
            const checkboxes = document.querySelectorAll('input[name="ejercicios[]"]');
            checkboxes.forEach(cb => {
                cb.checked = user.ejercicios.includes(parseInt(cb.value)) || user.ejercicios.includes(cb.value);
            });

            document.getElementById('user-modal').classList.add('active');
        }

        async function saveUser() {
            const id = document.getElementById('user_id').value;
            const nombre = document.getElementById('nombre').value;
            const correo = document.getElementById('correo').value;
            const password = document.getElementById('password').value;
            
            const checkboxes = document.querySelectorAll('input[name="ejercicios[]"]:checked');
            const ejercicios = Array.from(checkboxes).map(cb => parseInt(cb.value));

            const isEdit = id !== '';
            
            if(!isEdit && (!nombre || !correo || !password)){
                alert("Completa los campos obligatorios");
                return;
            }

            const method = isEdit ? 'PUT' : 'POST';
            const body = isEdit ? { usuario_id: id, ejercicios } : { nombre, correo, password, ejercicios };

            const res = await fetch('../api/admin_usuarios.php', {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const data = await res.json();
            if(data.success){
                closeModal();
                loadUsers();
            } else {
                alert(data.message);
            }
        }

        async function deleteUser(id) {
            if(confirm('¿Estás seguro de eliminar este usuario?')) {
                const res = await fetch('../api/admin_usuarios.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const data = await res.json();
                if(data.success){
                    loadUsers();
                } else {
                    alert(data.message);
                }
            }
        }

        window.onload = loadData;
    </script>
</body>
</html>
