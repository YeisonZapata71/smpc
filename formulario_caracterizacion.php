<?php
require_once 'includes/auth_check.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$ejercicio_id = isset($_GET['ejercicio_id']) ? (int)$_GET['ejercicio_id'] : null;

if (!$id && !$ejercicio_id) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Caracterización y Seguimiento - SMPC</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body { padding: 0; background: var(--bg-color); }
        .form-container {
            max-width: 900px;
            margin: 40px auto;
            background: var(--surface-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 40px;
        }
        .form-header {
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .form-header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        .btn-back {
            background: var(--surface-color);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        .btn-back:hover { background: var(--hover-color); }
        
        .section-title {
            background: var(--hover-color);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin: 30px 0 15px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-grid.col-3 { grid-template-columns: 1fr 1fr 1fr; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--bg-color);
            color: var(--text-color);
            font-family: inherit;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(11, 74, 59, 0.1);
        }
        textarea.form-control { resize: vertical; min-height: 100px; }
        
        .info-card {
            background: var(--hover-color);
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 4px 8px 8px 4px;
            margin-bottom: 20px;
        }
        
        .table-responsive { overflow-x: auto; margin-bottom: 20px; }
        .input-table { width: 100%; border-collapse: collapse; }
        .input-table th, .input-table td {
            border: 1px solid var(--border-color);
            padding: 10px;
            text-align: center;
        }
        .input-table th { background: var(--hover-color); font-weight: 600; }
        .input-table input {
            width: 80px;
            padding: 6px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: center;
        }
        
        .btn-save {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .btn-save:hover { background: #08382c; }
        .btn-save:disabled { opacity: 0.7; cursor: not-allowed; }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            display: none;
            font-weight: 500;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="form-header">
        <div>
            <h1>Formato de Caracterización y Seguimiento</h1>
            <p style="color: var(--text-muted)">Sistema Municipal de Participación Ciudadana</p>
        </div>
        <a href="dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <form id="caracterizacion-form">
        <input type="hidden" id="form_id" name="id" value="<?= $id ?>">
        <input type="hidden" id="ejercicio_id" name="ejercicio_id" value="<?= $ejercicio_id ?>">

        <!-- Identificación del Ejercicio -->
        <div class="info-card" id="info-ejercicio-card">
            <h3 style="margin-top:0; color:var(--primary-color)"><i class="fa-solid fa-tag"></i> Identificación del Ejercicio</h3>
            <p><strong>Sector:</strong> <span id="lbl-sector">Cargando...</span></p>
            <p><strong>Ejercicio:</strong> <span id="lbl-ejercicio">Cargando...</span></p>
        </div>

        <div class="section-title"><i class="fa-solid fa-user-tie"></i> Funcionario Responsable</div>
        <div class="form-grid col-3">
            <div class="form-group">
                <label>Dependencia</label>
                <input type="text" class="form-control" name="dependencia">
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="funcionario_nombre">
            </div>
            <div class="form-group">
                <label>Cargo</label>
                <input type="text" class="form-control" name="funcionario_cargo">
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" class="form-control" name="funcionario_telefono">
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" class="form-control" name="funcionario_correo">
            </div>
        </div>

        <div class="section-title"><i class="fa-solid fa-bullseye"></i> Objetivos y Metodología</div>
        <div class="form-group">
            <label>Objetivo del ejercicio participativo</label>
            <textarea class="form-control" name="objetivo" placeholder="Describa el objetivo..."></textarea>
        </div>
        <div class="form-group">
            <label>Metodología del ejercicio participativo</label>
            <textarea class="form-control" name="metodologia" placeholder="Describa la metodología..."></textarea>
        </div>

        <div class="section-title"><i class="fa-solid fa-calendar-check"></i> Actividad y Participación</div>
        <div class="form-grid col-3">
            <div class="form-group">
                <label>Fecha de Actividad</label>
                <input type="date" class="form-control" name="act_fecha">
            </div>
            <div class="form-group">
                <label>Participantes Zona Urbana</label>
                <input type="number" class="form-control" name="act_zona_urbana" min="0" value="0">
            </div>
            <div class="form-group">
                <label>Participantes Zona Rural</label>
                <input type="number" class="form-control" name="act_zona_rural" min="0" value="0">
            </div>
        </div>

        <label style="font-weight:600; margin-top:10px; display:block">Distribución por Edades</label>
        <div class="table-responsive">
            <table class="input-table">
                <tr>
                    <th>0-5 años</th>
                    <th>6-11 años</th>
                    <th>12-18 años</th>
                    <th>19-26 años</th>
                    <th>27-59 años</th>
                    <th>60 o más</th>
                </tr>
                <tr>
                    <td><input type="number" name="edad_0_5" min="0" value="0"></td>
                    <td><input type="number" name="edad_6_11" min="0" value="0"></td>
                    <td><input type="number" name="edad_12_18" min="0" value="0"></td>
                    <td><input type="number" name="edad_19_26" min="0" value="0"></td>
                    <td><input type="number" name="edad_27_59" min="0" value="0"></td>
                    <td><input type="number" name="edad_60_mas" min="0" value="0"></td>
                </tr>
            </table>
        </div>

        <label style="font-weight:600; margin-top:10px; display:block">Distribución por Género</label>
        <div class="table-responsive">
            <table class="input-table" style="width: auto">
                <tr>
                    <th>Hombres</th>
                    <th>Mujeres</th>
                </tr>
                <tr>
                    <td><input type="number" name="genero_hombre" min="0" value="0"></td>
                    <td><input type="number" name="genero_mujer" min="0" value="0"></td>
                </tr>
            </table>
        </div>

        <div class="section-title"><i class="fa-solid fa-users-viewfinder"></i> Enfoque Diferencial (Obligatorios)</div>
        <div class="info-card" style="border-left-color: var(--accent-color)">
            Todos los campos a continuación deben contener un valor numérico (si no hubo asistencia, colocar 0).
        </div>
        <div class="form-grid col-3">
            <div class="form-group">
                <label>Afrodescendiente *</label>
                <input type="number" class="form-control" name="enfoque_afro" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Indígena *</label>
                <input type="number" class="form-control" name="enfoque_indigena" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Campesino *</label>
                <input type="number" class="form-control" name="enfoque_campesino" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Persona con Discapacidad *</label>
                <input type="number" class="form-control" name="enfoque_discapacidad" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Víctima del Conflicto *</label>
                <input type="number" class="form-control" name="enfoque_victima" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>LGBTI *</label>
                <input type="number" class="form-control" name="enfoque_lgbti" min="0" value="0" required>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Otro (Cantidad) *</label>
                <input type="number" class="form-control" name="enfoque_otro" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Otro (Especifique si cantidad > 0)</label>
                <input type="text" class="form-control" name="enfoque_otro_desc">
            </div>
        </div>

        <div class="section-title"><i class="fa-solid fa-flag-checkered"></i> Resultados y Conclusiones</div>
        <div class="form-group">
            <label>¿Qué aportes realizaron los grupos de valor durante el ejercicio participativo?</label>
            <textarea class="form-control" name="aportes"></textarea>
        </div>
        <div class="form-group">
            <label>Describa las actuaciones administrativas realizadas para incorporar estos aportes</label>
            <textarea class="form-control" name="actuaciones"></textarea>
        </div>
        <div class="form-group">
            <label>Lecciones Aprendidas</label>
            <textarea class="form-control" name="lecciones"></textarea>
        </div>
        <div class="form-group">
            <label>Buenas Prácticas</label>
            <textarea class="form-control" name="buenas_practicas"></textarea>
        </div>

        <div id="mensaje" class="alert"></div>

        <button type="submit" class="btn-save" id="btn-submit">
            <i class="fa-solid fa-floppy-disk"></i> Guardar Formulario
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const formId = urlParams.get('id');
    const ejercicioId = urlParams.get('ejercicio_id');
    
    const apiUrl = formId 
        ? `api/obtener_formulario.php?id=${formId}` 
        : `api/obtener_formulario.php?ejercicio_id=${ejercicioId}`;

    try {
        const res = await fetch(apiUrl);
        const data = await res.json();
        
        if (data.error) {
            alert(data.error);
            window.location.href = 'dashboard.php';
            return;
        }

        // Llenar info del ejercicio
        if (data.info_ejercicio) {
            document.getElementById('lbl-sector').textContent = data.info_ejercicio.Sector;
            document.getElementById('lbl-ejercicio').textContent = data.info_ejercicio.Tipo;
        }

        // Si estamos editando, rellenar campos
        if (!data.is_new) {
            const form = document.getElementById('caracterizacion-form');
            for (const key in data) {
                if (form.elements[key] && key !== 'id' && key !== 'ejercicio_id') {
                    form.elements[key].value = data[key] !== null ? data[key] : '';
                }
            }
        }
    } catch(err) {
        console.error(err);
        alert('Error al cargar datos del servidor');
    }
});

document.getElementById('caracterizacion-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btn-submit');
    const msj = document.getElementById('mensaje');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
    msj.style.display = 'none';

    // Construir objeto de datos
    const formData = new FormData(e.target);
    const dataObj = Object.fromEntries(formData.entries());

    try {
        const res = await fetch('api/guardar_formulario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dataObj)
        });
        const result = await res.json();
        
        msj.style.display = 'block';
        if (result.success) {
            msj.className = 'alert alert-success';
            msj.innerHTML = `<i class="fa-solid fa-check-circle"></i> ${result.message}`;
            // Si era nuevo, actualizamos el ID para futuros guardados
            if (result.id && !document.getElementById('form_id').value) {
                document.getElementById('form_id').value = result.id;
            }
            
            // Regresar al dashboard después de 2 segundos
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 2000);
        } else {
            msj.className = 'alert alert-danger';
            msj.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> ${result.message}`;
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Formulario';
        }
    } catch(err) {
        msj.className = 'alert alert-danger';
        msj.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Ocurrió un error de conexión`;
        msj.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Formulario';
    }
});
</script>

</body>
</html>
