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
    <title>Formato de Caracterización - SMPC</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body { padding: 0; background: var(--bg-color); overflow-y: auto !important; }
        
        /* Navbar superior */
        .top-navbar {
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-sm);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .navbar-brand img {
            height: 40px;
            object-fit: contain;
        }
        .navbar-titles h1 {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin: 0;
            font-weight: 800;
        }
        .navbar-titles p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
            font-weight: 600;
        }
        
        .form-container {
            max-width: 1000px;
            margin: 40px auto;
            background: var(--surface-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 40px;
            background: #fff;
        }
        
        /* Progress Bar (Wizard) */
        .wizard-progress {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 40px;
        }
        .wizard-progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--border-color);
            z-index: 1;
            border-radius: 2px;
        }
        .progress-bar-fill {
            position: absolute;
            top: 20px;
            left: 0;
            height: 4px;
            background: var(--primary-color);
            z-index: 2;
            transition: width 0.4s ease;
            border-radius: 2px;
        }
        .wizard-step {
            position: relative;
            z-index: 3;
            text-align: center;
            width: 25%;
        }
        .step-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--border-color);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.3s;
            border: 4px solid #fff;
        }
        .wizard-step.active .step-icon {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        .wizard-step.completed .step-icon {
            background: var(--accent-color);
            color: #fff;
        }
        .step-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
        }
        .wizard-step.active .step-label { color: var(--primary-color); }
        
        /* Secciones del formulario */
        .form-section { display: none; animation: fadeIn 0.4s ease; }
        .form-section.active { display: block; }
        
        .section-title {
            background: var(--primary-light);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 1.2rem;
            color: var(--primary-color);
            margin: 30px 0 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-grid.col-3 { grid-template-columns: 1fr 1fr 1fr; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #f8fafc;
            color: var(--text-main);
            font-family: inherit;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(15, 81, 50, 0.1);
        }
        textarea.form-control { resize: vertical; min-height: 100px; }
        
        .info-card {
            background: #f0fdf4;
            border-left: 4px solid var(--primary-color);
            padding: 15px 20px;
            border-radius: 4px 8px 8px 4px;
            margin-bottom: 25px;
        }
        
        /* Estilos de Actividades Dinámicas */
        .activity-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
            position: relative;
        }
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed var(--border-color);
        }
        .activity-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .activity-title .badge {
            background: var(--primary-light);
            color: var(--primary-color);
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 0.8rem;
        }
        .btn-remove-activity {
            color: #ef4444;
            background: #fee2e2;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-remove-activity:hover { background: #f87171; color: #fff; }
        
        .btn-add-activity {
            background: var(--primary-light);
            color: var(--primary-color);
            border: 2px dashed var(--primary-color);
            padding: 15px;
            border-radius: 12px;
            width: 100%;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        .btn-add-activity:hover {
            background: var(--primary-color);
            color: #fff;
        }
        
        .table-responsive { overflow-x: auto; margin-bottom: 20px; }
        .input-table { width: 100%; border-collapse: collapse; }
        .input-table th, .input-table td {
            border: 1px solid var(--border-color);
            padding: 10px;
            text-align: center;
        }
        .input-table th { background: #f8fafc; font-weight: 600; font-size: 0.85rem; }
        .input-table input {
            width: 80px;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            text-align: center;
        }
        
        /* Botones de navegación */
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            transition: all 0.2s;
            font-size: 1rem;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }
        .btn-outline:hover { background: #f1f5f9; }
        .btn-primary {
            background: var(--primary-color);
            color: #fff;
        }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-success { background: var(--accent-color); color: #fff; }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            display: none;
            font-weight: 500;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-grid.col-3 { grid-template-columns: 1fr; }
            .form-container { padding: 20px; margin: 20px; }
            .step-label { display: none; }
        }
    </style>
</head>
<body>

<div class="top-navbar">
    <div class="navbar-brand">
        <!-- Logo simulado usando un icono para no depender de imágenes externas si no existen -->
        <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 8px; display: flex; align-items:center; justify-content:center; color: white; font-size: 1.5rem;">
            <i class="fa-solid fa-landmark"></i>
        </div>
        <div class="navbar-titles">
            <h1>Alcaldía de Girardota</h1>
            <p>Sistema Municipal de Participación Ciudadana</p>
        </div>
    </div>
    <a href="dashboard.php" class="btn btn-outline" style="padding: 8px 16px; font-size: 0.9rem;">
        <i class="fa-solid fa-xmark"></i> Cerrar
    </a>
</div>

<div class="form-container">
    <h2 style="color: var(--text-main); margin-bottom: 30px; text-align: center;">Formato de Caracterización y Seguimiento</h2>

    <div class="wizard-progress">
        <div class="progress-bar-fill" id="progress-bar" style="width: 0%;"></div>
        <div class="wizard-step active" id="step-1-indicator">
            <div class="step-icon">1</div>
            <div class="step-label">Caracterización</div>
        </div>
        <div class="wizard-step" id="step-2-indicator">
            <div class="step-icon">2</div>
            <div class="step-label">Cronograma</div>
        </div>
        <div class="wizard-step" id="step-3-indicator">
            <div class="step-icon">3</div>
            <div class="step-label">Actividades</div>
        </div>
        <div class="wizard-step" id="step-4-indicator">
            <div class="step-icon">4</div>
            <div class="step-label">Resultados</div>
        </div>
    </div>

    <form id="caracterizacion-form">
        <input type="hidden" id="form_id" name="id" value="<?= $id ?>">
        <input type="hidden" id="ejercicio_id" name="ejercicio_id" value="<?= $ejercicio_id ?>">

        <!-- PASO 1: CARACTERIZACIÓN -->
        <div class="form-section active" id="step-1">
            <div class="info-card">
                <h3 style="margin-top:0; color:var(--primary-color)"><i class="fa-solid fa-tag"></i> Identificación del Ejercicio</h3>
                <p><strong>Sector:</strong> <span id="lbl-sector">Cargando...</span></p>
                <p><strong>Ejercicio:</strong> <span id="lbl-ejercicio">Cargando...</span></p>
            </div>

            <div class="section-title"><i class="fa-solid fa-user-tie"></i> Funcionario Responsable</div>
            <div class="form-grid col-3">
                <div class="form-group">
                    <label>Dependencia *</label>
                    <input type="text" class="form-control" name="dependencia" required>
                </div>
                <div class="form-group">
                    <label>Nombre del Funcionario *</label>
                    <input type="text" class="form-control" name="funcionario_nombre" required>
                </div>
                <div class="form-group">
                    <label>Cargo *</label>
                    <input type="text" class="form-control" name="funcionario_cargo" required>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label>Teléfono *</label>
                    <input type="text" class="form-control" name="funcionario_telefono" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico *</label>
                    <input type="email" class="form-control" name="funcionario_correo" required>
                </div>
            </div>
        </div>

        <!-- PASO 2: CRONOGRAMA Y ACOMPAÑAMIENTO -->
        <div class="form-section" id="step-2">
            <div class="section-title"><i class="fa-solid fa-bullseye"></i> Objetivos y Metodología</div>
            <div class="form-group">
                <label>Objetivo del ejercicio participativo *</label>
                <textarea class="form-control" name="objetivo" placeholder="Definición clara y medible de lo que se busca lograr..." required></textarea>
            </div>
            <div class="form-group">
                <label>Metodología del ejercicio participativo *</label>
                <textarea class="form-control" name="metodologia" placeholder="Conjunto de técnicas, métodos y procedimientos..." required></textarea>
            </div>
            
            <div class="section-title"><i class="fa-solid fa-handshake-angle"></i> Acompañamiento Requerido</div>
            <div class="form-group">
                <label>Descripción del acompañamiento (Articulación con actores, Apoyo jurídico, Gestión de recursos, etc.) *</label>
                <textarea class="form-control" name="acompanamiento" placeholder="Describa el acompañamiento requerido..." required></textarea>
            </div>
        </div>

        <!-- PASO 3: ACTIVIDADES -->
        <div class="form-section" id="step-3">
            <div class="info-card">
                Agrega todas las actividades que se realizaron como parte de este ejercicio. El sistema numerará automáticamente cada una.
            </div>

            <div id="activities-container">
                <!-- Las actividades dinámicas se insertan aquí mediante JS -->
            </div>

            <button type="button" class="btn-add-activity" onclick="addActivity()">
                <i class="fa-solid fa-plus-circle"></i> Agregar Nueva Actividad
            </button>
        </div>

        <!-- PASO 4: RESULTADOS Y GESTIÓN DEL CONOCIMIENTO -->
        <div class="form-section" id="step-4">
            <div class="section-title"><i class="fa-solid fa-chart-line"></i> Resultados de la Participación</div>
            <div class="form-group">
                <label>¿Qué aportes realizaron los grupos de valor durante el ejercicio participativo? *</label>
                <textarea class="form-control" name="aportes" required></textarea>
            </div>
            <div class="form-group">
                <label>Describa las actuaciones administrativas realizadas para incorporar estos aportes *</label>
                <textarea class="form-control" name="actuaciones" required></textarea>
            </div>
            <div class="form-group">
                <label>¿A través de qué canales se informó a los grupos de valor sobre los resultados? *</label>
                <textarea class="form-control" name="canales_info" required></textarea>
            </div>

            <div class="section-title"><i class="fa-solid fa-lightbulb"></i> Gestión del Conocimiento</div>
            <div class="form-group">
                <label>Lecciones Aprendidas *</label>
                <textarea class="form-control" name="lecciones" placeholder="Conocimientos obtenidos reflexionando sobre el desarrollo..." required></textarea>
            </div>
            <div class="form-group">
                <label>Buenas Prácticas *</label>
                <textarea class="form-control" name="buenas_practicas" placeholder="Acciones o metodologías efectivas replicables..." required></textarea>
            </div>
        </div>

        <div id="mensaje" class="alert"></div>

        <!-- Controles del Wizard -->
        <div class="form-actions">
            <button type="button" class="btn btn-outline" id="btn-prev" onclick="changeStep(-1)" style="visibility: hidden;">
                <i class="fa-solid fa-arrow-left"></i> Anterior
            </button>
            <button type="button" class="btn btn-primary" id="btn-next" onclick="changeStep(1)">
                Siguiente <i class="fa-solid fa-arrow-right"></i>
            </button>
            <button type="submit" class="btn btn-success" id="btn-submit" style="display: none;">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Formulario Completo
            </button>
        </div>
    </form>
</div>

<!-- Template para nueva actividad (oculto) -->
<template id="activity-template">
    <div class="activity-card" data-act-index="{INDEX}">
        <div class="activity-header">
            <div class="activity-title">
                <i class="fa-solid fa-clipboard-list"></i> Actividad <span class="badge">{NUM}</span>
            </div>
            <button type="button" class="btn-remove-activity" onclick="removeActivity(this)">
                <i class="fa-solid fa-trash"></i> Eliminar
            </button>
        </div>
        
        <div class="form-grid col-3">
            <div class="form-group">
                <label>Fecha de Actividad</label>
                <input type="date" class="form-control input-act-fecha">
            </div>
            <div class="form-group">
                <label>Participantes Zona Urbana</label>
                <input type="number" class="form-control input-act-urbana" min="0" value="0">
            </div>
            <div class="form-group">
                <label>Participantes Zona Rural</label>
                <input type="number" class="form-control input-act-rural" min="0" value="0">
            </div>
        </div>

        <label style="font-weight:600; margin-top:10px; display:block; color:var(--primary-color)">Distribución por Edades</label>
        <div class="table-responsive">
            <table class="input-table">
                <tr>
                    <th>0-5</th><th>6-11</th><th>12-18</th><th>19-26</th><th>27-59</th><th>60+</th>
                </tr>
                <tr>
                    <td><input type="number" class="input-edad-0-5" min="0" value="0"></td>
                    <td><input type="number" class="input-edad-6-11" min="0" value="0"></td>
                    <td><input type="number" class="input-edad-12-18" min="0" value="0"></td>
                    <td><input type="number" class="input-edad-19-26" min="0" value="0"></td>
                    <td><input type="number" class="input-edad-27-59" min="0" value="0"></td>
                    <td><input type="number" class="input-edad-60-mas" min="0" value="0"></td>
                </tr>
            </table>
        </div>

        <label style="font-weight:600; margin-top:10px; display:block; color:var(--primary-color)">Distribución por Género</label>
        <div class="table-responsive">
            <table class="input-table" style="width: 250px">
                <tr><th>Hombres</th><th>Mujeres</th></tr>
                <tr>
                    <td><input type="number" class="input-genero-hombre" min="0" value="0"></td>
                    <td><input type="number" class="input-genero-mujer" min="0" value="0"></td>
                </tr>
            </table>
        </div>

        <label style="font-weight:600; margin-top:10px; display:block; color:var(--primary-color)">Enfoque Diferencial (Obligatorios)</label>
        <div class="form-grid col-3">
            <div class="form-group">
                <label>Afrodescendiente *</label>
                <input type="number" class="form-control input-afro" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Indígena *</label>
                <input type="number" class="form-control input-indigena" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Campesino *</label>
                <input type="number" class="form-control input-campesino" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Persona con Discapacidad *</label>
                <input type="number" class="form-control input-discapacidad" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Víctima del Conflicto *</label>
                <input type="number" class="form-control input-victima" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>LGBTI *</label>
                <input type="number" class="form-control input-lgbti" min="0" value="0" required>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Otro (Cantidad) *</label>
                <input type="number" class="form-control input-otro" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label>Otro (Descripción)</label>
                <input type="text" class="form-control input-otro-desc">
            </div>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label>Instancias de participación ciudadana</label>
                <textarea class="form-control input-instancias" style="min-height:60px"></textarea>
            </div>
            <div class="form-group">
                <label>Organizaciones</label>
                <textarea class="form-control input-organizaciones" style="min-height:60px"></textarea>
            </div>
        </div>
    </div>
</template>

<script>
let currentStep = 1;
const totalSteps = 4;
let activityCount = 0;

document.addEventListener('DOMContentLoaded', async () => {
    updateWizardUI();
    
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

        if (!data.is_new) {
            // Llenar datos principales
            const form = document.getElementById('caracterizacion-form');
            for (const key in data) {
                if (key !== 'id' && key !== 'ejercicio_id' && key !== 'actividades' && key !== 'info_ejercicio') {
                    if(form.elements[key]) {
                        form.elements[key].value = data[key] !== null ? data[key] : '';
                    }
                }
            }
            
            // Llenar actividades
            if (data.actividades && data.actividades.length > 0) {
                data.actividades.forEach(act => addActivity(act));
            } else {
                addActivity(); // Siempre 1 por defecto
            }
        } else {
            addActivity(); // Agregar primera actividad vacía
        }
    } catch(err) {
        console.error(err);
        alert('Error al cargar datos del servidor');
    }
});

function changeStep(stepChange) {
    // Basic validation before going next
    if(stepChange > 0) {
        const currentSection = document.getElementById(`step-${currentStep}`);
        const requiredInputs = currentSection.querySelectorAll('input[required]');
        let isValid = true;
        requiredInputs.forEach(inp => {
            if(!inp.checkValidity()) {
                inp.reportValidity();
                isValid = false;
            }
        });
        if(!isValid) return;
    }

    document.getElementById(`step-${currentStep}`).classList.remove('active');
    currentStep += stepChange;
    document.getElementById(`step-${currentStep}`).classList.add('active');
    
    updateWizardUI();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateWizardUI() {
    // Progreso
    const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
    document.getElementById('progress-bar').style.width = `${progress}%`;
    
    // Indicadores
    for (let i = 1; i <= totalSteps; i++) {
        const ind = document.getElementById(`step-${i}-indicator`);
        if (i < currentStep) {
            ind.className = 'wizard-step completed';
        } else if (i === currentStep) {
            ind.className = 'wizard-step active';
        } else {
            ind.className = 'wizard-step';
        }
    }
    
    // Botones
    document.getElementById('btn-prev').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
    
    if (currentStep === totalSteps) {
        document.getElementById('btn-next').style.display = 'none';
        document.getElementById('btn-submit').style.display = 'inline-flex';
    } else {
        document.getElementById('btn-next').style.display = 'inline-flex';
        document.getElementById('btn-submit').style.display = 'none';
    }
}

function addActivity(data = null) {
    activityCount++;
    const container = document.getElementById('activities-container');
    const template = document.getElementById('activity-template').innerHTML;
    
    const html = template.replace(/{INDEX}/g, activityCount).replace(/{NUM}/g, activityCount);
    container.insertAdjacentHTML('beforeend', html);
    
    if (data) {
        const el = container.lastElementChild;
        el.querySelector('.input-act-fecha').value = data.act_fecha || '';
        el.querySelector('.input-act-urbana').value = data.act_zona_urbana || 0;
        el.querySelector('.input-act-rural').value = data.act_zona_rural || 0;
        
        el.querySelector('.input-edad-0-5').value = data.edad_0_5 || 0;
        el.querySelector('.input-edad-6-11').value = data.edad_6_11 || 0;
        el.querySelector('.input-edad-12-18').value = data.edad_12_18 || 0;
        el.querySelector('.input-edad-19-26').value = data.edad_19_26 || 0;
        el.querySelector('.input-edad-27-59').value = data.edad_27_59 || 0;
        el.querySelector('.input-edad-60-mas').value = data.edad_60_mas || 0;
        
        el.querySelector('.input-genero-hombre').value = data.genero_hombre || 0;
        el.querySelector('.input-genero-mujer').value = data.genero_mujer || 0;
        
        el.querySelector('.input-afro').value = data.enfoque_afro || 0;
        el.querySelector('.input-indigena').value = data.enfoque_indigena || 0;
        el.querySelector('.input-campesino').value = data.enfoque_campesino || 0;
        el.querySelector('.input-discapacidad').value = data.enfoque_discapacidad || 0;
        el.querySelector('.input-victima').value = data.enfoque_victima || 0;
        el.querySelector('.input-lgbti').value = data.enfoque_lgbti || 0;
        el.querySelector('.input-otro').value = data.enfoque_otro || 0;
        el.querySelector('.input-otro-desc').value = data.enfoque_otro_desc || '';
        
        el.querySelector('.input-instancias').value = data.instancias || '';
        el.querySelector('.input-organizaciones').value = data.organizaciones || '';
    }
}

function removeActivity(button) {
    const card = button.closest('.activity-card');
    card.remove();
    reindexActivities();
}

function reindexActivities() {
    const cards = document.querySelectorAll('.activity-card');
    activityCount = 0;
    cards.forEach(card => {
        activityCount++;
        card.dataset.actIndex = activityCount;
        card.querySelector('.badge').textContent = activityCount;
    });
}

document.getElementById('caracterizacion-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    // Validar el último paso antes de dejar guardar
    const currentSection = document.getElementById(`step-${currentStep}`);
    const requiredInputs = currentSection.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    requiredInputs.forEach(inp => {
        if(!inp.checkValidity()) {
            inp.reportValidity();
            isValid = false;
        }
    });
    if(!isValid) return;

    const btn = document.getElementById('btn-submit');
    const msj = document.getElementById('mensaje');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando...';
    msj.style.display = 'none';

    const formData = new FormData(e.target);
    const dataObj = Object.fromEntries(formData.entries());
    
    // Recolectar actividades
    dataObj.actividades = [];
    document.querySelectorAll('.activity-card').forEach(card => {
        dataObj.actividades.push({
            act_fecha: card.querySelector('.input-act-fecha').value,
            act_zona_urbana: card.querySelector('.input-act-urbana').value,
            act_zona_rural: card.querySelector('.input-act-rural').value,
            edad_0_5: card.querySelector('.input-edad-0-5').value,
            edad_6_11: card.querySelector('.input-edad-6-11').value,
            edad_12_18: card.querySelector('.input-edad-12-18').value,
            edad_19_26: card.querySelector('.input-edad-19-26').value,
            edad_27_59: card.querySelector('.input-edad-27-59').value,
            edad_60_mas: card.querySelector('.input-edad-60-mas').value,
            genero_hombre: card.querySelector('.input-genero-hombre').value,
            genero_mujer: card.querySelector('.input-genero-mujer').value,
            enfoque_afro: card.querySelector('.input-afro').value,
            enfoque_indigena: card.querySelector('.input-indigena').value,
            enfoque_campesino: card.querySelector('.input-campesino').value,
            enfoque_discapacidad: card.querySelector('.input-discapacidad').value,
            enfoque_victima: card.querySelector('.input-victima').value,
            enfoque_lgbti: card.querySelector('.input-lgbti').value,
            enfoque_otro: card.querySelector('.input-otro').value,
            enfoque_otro_desc: card.querySelector('.input-otro-desc').value,
            instancias: card.querySelector('.input-instancias').value,
            organizaciones: card.querySelector('.input-organizaciones').value
        });
    });

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
            if (result.id && !document.getElementById('form_id').value) {
                document.getElementById('form_id').value = result.id;
            }
            setTimeout(() => { window.location.href = 'dashboard.php'; }, 2000);
        } else {
            msj.className = 'alert alert-danger';
            msj.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> ${result.message}`;
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Formulario Completo';
        }
    } catch(err) {
        msj.className = 'alert alert-danger';
        msj.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Ocurrió un error de conexión`;
        msj.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Formulario Completo';
    }
});
</script>

</body>
</html>
