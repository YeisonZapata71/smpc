// Sector icons mapping (to give a nice visual identity to each sector)
const sectorIcons = {
    "Afrodescendiente": "fa-people-group",
    "Agricultura y desarrollo rural": "fa-seedling",
    "Ambiente y desarrollo sostenible": "fa-leaf",
    "Comercio, industria y turismo": "fa-store",
    "Cultura": "fa-masks-theater",
    "Educación": "fa-graduation-cap",
    "Juventudes": "fa-child",
    "Mujeres": "fa-venus",
    "Niñez": "fa-children",
    "Paz y derechos humanos": "fa-dove",
    "Personas con discapacidad": "fa-wheelchair",
    "Personas LGBTIQ+": "fa-rainbow",
    "Personas mayores": "fa-person-cane",
    "Salud": "fa-heart-pulse",
    "Transporte": "fa-bus",
    "Transversal": "fa-arrows-to-circle",
    "Vivienda y servicios públicos": "fa-house-chimney"
};

// Extracted Data
const rawData = [
    {"Ejercicio": "Afrodescendiente", "Tipo": "Caracterización del territorio y la población afrodescendiente", "Sector": "1"},
    {"Ejercicio": "Afrodescendiente", "Tipo": "Consulta a la población afrodescendiente relativa a instrumentos de política pública y de planificación territorial", "Sector": "2"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Implementación de estrategias de fortalecimiento a organizaciones agropecuarias", "Sector": "3"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Implementación del plan agropecuario mundial (PAM)", "Sector": "4"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Control social al plan agropecuario mundial (PAM)", "Sector": "5"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Diagnóstico o caracterización de asociaciones agropecuarias", "Sector": "6"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Diagnóstico de la última Feria del campo y la cosecha", "Sector": "7"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Implementación del Mercado Agro Verde para la comercialización de productos agropecuarios", "Sector": "8"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Días de campo: priorización de necesidades de asistencia técnica y extensión agropecuaria", "Sector": "9"},
    {"Ejercicio": "Agricultura y desarrollo rural", "Tipo": "Días de campo: rendición de cuentas de la Secretaría de Agricultura y Desarrollo Rural", "Sector": "10"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Implementación de estrategias de educación ambiental", "Sector": "11"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Actualización del acto administrativo que reglamenta el CIDEAM", "Sector": "12"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Creación de rutas para la atención y seguimiento a casos de maltrato animal", "Sector": "13"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Implementación de la política pública de protección y bienestar animal", "Sector": "14"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Implementación de iniciativas de sostenibilidad ambiental", "Sector": "15"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Implementación de campañas para la gestión de cambio climático", "Sector": "16"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Un viaje por los sonidos de nuestro territorio: Identificación de niveles de ruido y presencia de fauna a través de ejercicios de cartografía socioambiental y juego interactivo", "Sector": "17"},
    {"Ejercicio": "Ambiente y desarrollo sostenible", "Tipo": "Implementación de estrategias educativas y ambientales para reconocimiento, dignificación y fortalecimiento de capacidades de recuperadores ambientales", "Sector": "18"},
    {"Ejercicio": "Comercio, industria y turismo", "Tipo": "Implementación del Plan Estratégico y la Política Pública de Turismo", "Sector": "19"},
    {"Ejercicio": "Comercio, industria y turismo", "Tipo": "Control social del Plan Estratégico y la Política Pública de Turismo", "Sector": "20"},
    {"Ejercicio": "Comercio, industria y turismo", "Tipo": "Selección de emprendedores beneficiarios de incentivos financieros del programa Red de Economía Social y Solidaria (REDESS)", "Sector": "21"},
    {"Ejercicio": "Comercio, industria y turismo", "Tipo": "Control social a los recursos del programa Red de Economía Social y Solidaria (REDESS)", "Sector": "22"},
    {"Ejercicio": "Cultura", "Tipo": "Implementación del plan estratégico y la política pública de cultura", "Sector": "23"},
    {"Ejercicio": "Cultura", "Tipo": "Implementación de iniciativas ciudadanas para dinamización del sector cultural en sus eslabones de creación, formación, participación, investigación, circulación y patrimonio", "Sector": "24"},
    {"Ejercicio": "Educación", "Tipo": "Control social al Programa de Alimentación Escolar (PAE)", "Sector": "25"},
    {"Ejercicio": "Educación", "Tipo": "Implementación de las políticas educativas", "Sector": "26"},
    {"Ejercicio": "Juventudes", "Tipo": "Implementación del programa Girardota es Joven y la oferta institucional para las juventudes", "Sector": "27"},
    {"Ejercicio": "Juventudes", "Tipo": "Actualización de la Política Pública de Juventud", "Sector": "28"},
    {"Ejercicio": "Juventudes", "Tipo": "Implementación de estrategias para la promoción de la ciudadanía y el liderazgo juvenil", "Sector": "29"},
    {"Ejercicio": "Juventudes", "Tipo": "Asamblea Subregional de Juventudes", "Sector": "30"},
    {"Ejercicio": "Mujeres", "Tipo": "Implementación de la política pública de mujer y género", "Sector": "31"},
    {"Ejercicio": "Mujeres", "Tipo": "Control social a la política pública de mujer y género", "Sector": "32"},
    {"Ejercicio": "Mujeres", "Tipo": "Implementación de estrategias de prevención y atención de violencias basadas en género", "Sector": "33"},
    {"Ejercicio": "Niñez", "Tipo": "Actualización de diagnósticos del sector niñez", "Sector": "34"},
    {"Ejercicio": "Niñez", "Tipo": "Construcción de política pública de infancia y primera infancia", "Sector": "35"},
    {"Ejercicio": "Niñez", "Tipo": "Implementación de campañas para la garantía de derechos de la niñez", "Sector": "36"},
    {"Ejercicio": "Paz y derechos humanos", "Tipo": "Implementación Plan de Acción Territorial (PAT) de la Política Pública Nacional de Víctimas", "Sector": "37"},
    {"Ejercicio": "Paz y derechos humanos", "Tipo": "Control social del Plan de Acción Territorial (PAT) de la Política Pública Nacional de Víctimas", "Sector": "38"},
    {"Ejercicio": "Paz y derechos humanos", "Tipo": "Priorización de problemáticas de convivencia y seguridad", "Sector": "39"},
    {"Ejercicio": "Paz y derechos humanos", "Tipo": "Implementación de estrategias de promoción de la paz, la convivencia, la seguridad, la reconciliación y la no estigmatización", "Sector": "40"},
    {"Ejercicio": "Paz y derechos humanos", "Tipo": "Implementación de estrategias de atención y protección a líderes y lideresas sociales y otras poblaciones especiales.", "Sector": "41"},
    {"Ejercicio": "Paz y derechos humanos", "Tipo": "Implementación de la política pública de libertad religiosa y de cultos", "Sector": "42"},
    {"Ejercicio": "Personas con discapacidad", "Tipo": "Implementación del programa y la Política Pública de Discapacidad", "Sector": "43"},
    {"Ejercicio": "Personas con discapacidad", "Tipo": "Control social al programa y la Política Pública de Discapacidad", "Sector": "44"},
    {"Ejercicio": "Personas con discapacidad", "Tipo": "Caracterización de personas con discapacidad y cuidadores y priorización de necesidades", "Sector": "45"},
    {"Ejercicio": "Personas LGBTIQ+", "Tipo": "Implementación del programa Transformación con Equidad en la Diversidad", "Sector": "46"},
    {"Ejercicio": "Personas LGBTIQ+", "Tipo": "Formulación participativa de política pública para la población LGBTI", "Sector": "47"},
    {"Ejercicio": "Personas mayores", "Tipo": "Implementación de estrategias para la garantía de derechos de las personas mayores", "Sector": "48"},
    {"Ejercicio": "Personas mayores", "Tipo": "Actualización de la política pública de envejecimiento y vejez", "Sector": "49"},
    {"Ejercicio": "Personas mayores", "Tipo": "Control social a la política pública de envejecimiento y vejez", "Sector": "50"},
    {"Ejercicio": "Salud", "Tipo": "Identificación y notificación de eventos de interés en salud pública", "Sector": "51"},
    {"Ejercicio": "Salud", "Tipo": "Implementación de la política pública de seguridad alimentaria", "Sector": "52"},
    {"Ejercicio": "Salud", "Tipo": "Veeduría ciudadana al Plan de Intervenciones Colectivas (PIC) y a los Equipos Básicos en Salud", "Sector": "53"},
    {"Ejercicio": "Salud", "Tipo": "Implementación de jornadas Tu EPS al Parque para resolución de PQRS por barreras de acceso a los servicios de salud en el municipio", "Sector": "54"},
    {"Ejercicio": "Salud", "Tipo": "Diálogo de líderes e instancias de participación en salud con EPS e IPS para mejorar el acceso a servicios de salud en el municipio", "Sector": "55"},
    {"Ejercicio": "Salud", "Tipo": "Implementación de jornadas de promoción de la salud mental en entornos familiares y comunitarios", "Sector": "56"},
    {"Ejercicio": "Salud", "Tipo": "Implementación de estrategias para fortalecer el Programa Ampliado de Inmunizaciones (PAI)", "Sector": "57"},
    {"Ejercicio": "Salud", "Tipo": "Rendición pública de cuentas de la Secretaría de Salud y Protección Social para informar a la ciudadanía sobre los avances en la implementación del Plan Territorial de Salud en la vigencia 2026, generar diálogo y retroalimentación al respecto y asumir compromisos de mejoramiento", "Sector": "58"},
    {"Ejercicio": "Transporte", "Tipo": "Priorización de problemáticas de movilidad y seguridad vial", "Sector": "59"},
    {"Ejercicio": "Transporte", "Tipo": "Implementación de estrategias para avanzar hacia un sistema de transporte más eficiente, seguro y respetuoso con el entorno", "Sector": "60"},
    {"Ejercicio": "Transversal", "Tipo": "Control social a la implementación del Plan de Desarrollo Territorial Decencia en lo Público para el año 2026", "Sector": "61"},
    {"Ejercicio": "Transversal", "Tipo": "Elaboración y divulgación de boletín con agenda o cronograma de los ejercicios participativos", "Sector": "62"},
    {"Ejercicio": "Transversal", "Tipo": "Implementación de estrategia lúdico – recreativa dirigida a la comunidad para la promoción de la participación ciudadana y el control social", "Sector": "63"},
    {"Ejercicio": "Transversal", "Tipo": "Desarrollo de instrumento de participación para canalizar y hacer seguimiento a solicitudes de las instancias de participación y la comunidad a través de sus representantes", "Sector": "64"},
    {"Ejercicio": "Transversal", "Tipo": "Jornadas de socialización y cabildo abierto para la formulación del Plan Básico de Ordenamiento Territorial (PBOT)", "Sector": "65"},
    {"Ejercicio": "Transversal", "Tipo": "Socialización y retroalimentación de proyectos formulados con la población objetivo desde el Banco de Programas y Proyectos", "Sector": "66"},
    {"Ejercicio": "Transversal", "Tipo": "Control social a políticas públicas sociales", "Sector": "67"},
    {"Ejercicio": "Transversal", "Tipo": "Priorización e implementación de proyectos de inversión pública a través de la política pública de presupuesto participativo", "Sector": "68"},
    {"Ejercicio": "Transversal", "Tipo": "Control social a los proyectos de presupuesto participativo por parte de comités veedores y el Consejo Municipal de Participación Ciudadana", "Sector": "69"},
    {"Ejercicio": "Transversal", "Tipo": "Rendición pública de cuentas sobre los proyectos ejecutados con recursos de presupuesto participativo", "Sector": "70"},
    {"Ejercicio": "Transversal", "Tipo": "Audiencia pública de rendición de cuentas de la Administración Municipal para informar a la ciudadanía sobre los avances en la implementación del Plan de Desarrollo Municipal en la vigencia 2026, generar diálogo y retroalimentación al respecto y asumir compromisos de mejoramiento", "Sector": "71"},
    {"Ejercicio": "Vivienda y servicios públicos", "Tipo": "Control social a la aplicación y actualización de la estratificación urbana y rural para servicios públicos", "Sector": "72"},
    {"Ejercicio": "Vivienda y servicios públicos", "Tipo": "Resolución en segunda instancia reclamos por la aplicación de la estratificación urbana y rural", "Sector": "73"},
    {"Ejercicio": "Vivienda y servicios públicos", "Tipo": "Reactivación de la Mesa técnica para el fortalecimiento de las comunidades organizadas que prestan servicios públicos e implementación de política pública", "Sector": "74"},
    {"Ejercicio": "Vivienda y servicios públicos", "Tipo": "Audiencia pública sobre actualización catastral", "Sector": "75"}
];

// Group data by Sector
const groupedData = {};
rawData.forEach(item => {
    const sectorName = item.Ejercicio; // From the JSON, "Ejercicio" is actually the Sector name
    const exerciseName = item.Tipo;    // "Tipo" is the Exercise
    
    if (!groupedData[sectorName]) {
        groupedData[sectorName] = [];
    }
    groupedData[sectorName].push(exerciseName);
});

// Folder Structure Blueprint
const folderStructure = [
    { type: 'file', name: 'Formato de caracterización y seguimiento (documento adjunto)', icon: 'fa-file-pdf' },
    { type: 'folder', name: 'Información clave', children: [] },
    { type: 'folder', name: 'Normatividad', children: [] },
    { type: 'folder', name: 'Pedagogía', children: [] },
    { type: 'folder', name: 'Otros', children: [] },
    { type: 'folder', name: 'Evidencias de actividades', children: [
        { type: 'folder', name: 'Actividad 1', children: [
            { type: 'file', name: 'Acta', icon: 'fa-file-lines' },
            { type: 'file', name: 'Lista de asistencia', icon: 'fa-file-lines' },
            { type: 'file', name: 'Material audiovisual', icon: 'fa-file-video' },
            { type: 'file', name: 'Evaluación de la satisfacción', icon: 'fa-file-lines' },
            { type: 'folder', name: 'Otros', children: [] }
        ]}
    ]},
    { type: 'folder', name: 'Resultados', children: [] }
];

// DOM Elements
const sectorMenu = document.getElementById('sector-menu');
const exercisesContainer = document.getElementById('exercises-container');
const currentSectorTitle = document.getElementById('current-sector-title');
const themeToggle = document.getElementById('theme-toggle');
const folderModal = document.getElementById('folder-modal');
const modalClose = document.getElementById('modal-close');
const modalExerciseTitle = document.getElementById('modal-exercise-title');
const modalSectorBadge = document.getElementById('modal-sector-badge');
const folderBrowserContainer = document.querySelector('.folder-browser');

// ─────────────────────────────────────
// DASHBOARD
// ─────────────────────────────────────
function renderDashboard() {
    // Reset active sector
    document.querySelectorAll('.sector-item').forEach(el => el.classList.remove('active'));
    currentSectorTitle.textContent = 'Dashboard General';

    // Compute stats from rawData
    const totalEjercicios = rawData.length;
    const sectores = Object.keys(groupedData);
    const totalSectores = sectores.length;

    // Simulated progress data per sector (realistic distribution)
    const sectorProgress = {
        'Afrodescendiente': { pct: 100, status: 'completed' },
        'Agricultura y desarrollo rural': { pct: 78, status: 'in-progress' },
        'Ambiente y desarrollo sostenible': { pct: 62, status: 'in-progress' },
        'Comercio, industria y turismo': { pct: 50, status: 'in-progress' },
        'Cultura': { pct: 100, status: 'completed' },
        'Educación': { pct: 100, status: 'completed' },
        'Juventudes': { pct: 75, status: 'in-progress' },
        'Mujeres': { pct: 33, status: 'in-progress' },
        'Niñez': { pct: 67, status: 'in-progress' },
        'Paz y derechos humanos': { pct: 17, status: 'planned' },
        'Personas con discapacidad': { pct: 33, status: 'planned' },
        'Personas LGBTIQ+': { pct: 50, status: 'in-progress' },
        'Personas mayores': { pct: 67, status: 'in-progress' },
        'Salud': { pct: 25, status: 'planned' },
        'Transporte': { pct: 50, status: 'in-progress' },
        'Transversal': { pct: 18, status: 'planned' },
        'Vivienda y servicios públicos': { pct: 25, status: 'planned' }
    };

    const completados = Object.values(sectorProgress).filter(s => s.status === 'completed').length;
    const enProceso  = Object.values(sectorProgress).filter(s => s.status === 'in-progress').length;
    const planeados  = Object.values(sectorProgress).filter(s => s.status === 'planned').length;
    const avgProgress = Math.round(Object.values(sectorProgress).reduce((a,b) => a + b.pct, 0) / totalSectores);

    // Top 6 sectors by exercise count for bar chart
    const topSectors = Object.entries(groupedData)
        .sort((a,b) => b[1].length - a[1].length)
        .slice(0, 6);
    const maxCount = topSectors[0][1].length;

    // Donut slices: completed / in-progress / planned
    const donutData = [
        { label: 'Completados', value: completados, color: '#16a34a' },
        { label: 'En proceso',  value: enProceso,  color: '#f59e0b' },
        { label: 'Planeados',   value: planeados,  color: '#3b82f6' }
    ];
    const donutTotal = completados + enProceso + planeados;

    // Build SVG donut
    function buildDonut(data, total, size=160, stroke=28) {
        const r = (size - stroke) / 2;
        const cx = size / 2, cy = size / 2;
        const circ = 2 * Math.PI * r;
        let offset = 0;
        let paths = '';
        data.forEach(d => {
            const pct = d.value / total;
            const dash = pct * circ;
            paths += `<circle
                cx="${cx}" cy="${cy}" r="${r}"
                fill="none" stroke="${d.color}" stroke-width="${stroke}"
                stroke-dasharray="${dash} ${circ - dash}"
                stroke-dashoffset="${-offset}"
                transform="rotate(-90 ${cx} ${cy})"
                style="transition:stroke-dasharray 1.2s cubic-bezier(.4,0,.2,1)"
            />`;
            offset += dash;
        });
        return `<svg width="${size}" height="${size}" viewBox="0 0 ${size} ${size}">${paths}</svg>`;
    }

    const now = new Date();
    const dateStr = now.toLocaleDateString('es-CO', { weekday:'long', year:'numeric', month:'long', day:'numeric' });

    exercisesContainer.innerHTML = `
    <div class="dashboard">

      <!-- Header -->
      <div class="dashboard-header">
        <h2><i class="fa-solid fa-chart-line"></i> Panel de Control SMPC</h2>
        <p>Sistema Municipal de Participación Ciudadana &mdash; Vigencia 2026</p>
        <div class="dash-date"><i class="fa-regular fa-calendar"></i> ${dateStr}</div>
      </div>

      <!-- KPI Cards -->
      <div class="kpi-row">
        <div class="kpi-card">
          <div class="kpi-icon"><i class="fa-solid fa-list-check"></i></div>
          <div class="kpi-value"><span id="kpi-total">0</span></div>
          <div class="kpi-label">Ejercicios Totales</div>
          <div class="kpi-trend up"><i class="fa-solid fa-arrow-trend-up"></i> Vigencia 2026</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon"><i class="fa-solid fa-building-columns"></i></div>
          <div class="kpi-value"><span id="kpi-sectores">0</span></div>
          <div class="kpi-label">Sectores Activos</div>
          <div class="kpi-trend neutral"><i class="fa-solid fa-layer-group"></i> Sectores</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon"><i class="fa-solid fa-circle-check"></i></div>
          <div class="kpi-value"><span id="kpi-completados">0</span></div>
          <div class="kpi-label">Sectores Completados</div>
          <div class="kpi-trend up"><i class="fa-solid fa-check"></i> Finalizados</div>
        </div>
        <div class="kpi-card">
          <div class="kpi-icon"><i class="fa-solid fa-gauge-high"></i></div>
          <div class="kpi-value"><span id="kpi-avance">0</span><span class="kpi-unit">%</span></div>
          <div class="kpi-label">Avance General</div>
          <div class="kpi-trend up"><i class="fa-solid fa-arrow-up"></i> Promedio</div>
        </div>
      </div>

      <!-- Row 1: Bar Chart + Donut -->
      <div class="dashboard-grid">
        <!-- Bar Chart -->
        <div class="dashboard-panel">
          <div class="panel-header">
            <h3><i class="fa-solid fa-chart-bar"></i> Ejercicios por Sector (Top 6)</h3>
            <span class="panel-badge">2026</span>
          </div>
          <div class="bar-chart">
            ${topSectors.map(([name, exs]) => `
              <div class="bar-row">
                <div class="bar-label" title="${name}">${name}</div>
                <div class="bar-track">
                  <div class="bar-fill" data-w="${Math.round(exs.length/maxCount*100)}"><span>${exs.length}</span></div>
                </div>
                <div class="bar-num">${exs.length}</div>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Donut Chart -->
        <div class="dashboard-panel">
          <div class="panel-header">
            <h3><i class="fa-solid fa-chart-pie"></i> Estado Sectores</h3>
            <span class="panel-badge">${totalSectores} total</span>
          </div>
          <div class="donut-wrapper">
            <div class="donut-svg-container">
              ${buildDonut(donutData, donutTotal)}
              <div class="donut-center-text">
                <div class="big-num">${avgProgress}%</div>
                <div class="small-lbl">Avance</div>
              </div>
            </div>
            <div class="donut-legend">
              ${donutData.map(d => `
                <div class="legend-item">
                  <div class="legend-dot" style="background:${d.color}"></div>
                  ${d.label}
                  <span class="legend-value">${d.value}</span>
                </div>
              `).join('')}
            </div>
          </div>
        </div>
      </div>

      <!-- Row 2: Table + Activity -->
      <div class="dashboard-grid-2">
        <!-- Sector Progress Table -->
        <div class="dashboard-panel">
          <div class="panel-header">
            <h3><i class="fa-solid fa-table-list"></i> Progreso por Sector</h3>
            <span class="panel-badge">${totalSectores} sectores</span>
          </div>
          <div class="table-wrapper">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Sector</th>
                  <th>Ejercicios</th>
                  <th>Avance</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                ${Object.entries(groupedData).sort((a,b)=>b[1].length-a[1].length).map(([name, exs]) => {
                  const prog = sectorProgress[name] || { pct: 0, status: 'planned' };
                  const statusLabel = { completed: 'Completado', 'in-progress': 'En proceso', planned: 'Planeado' }[prog.status];
                  return `<tr>
                    <td style="font-weight:600">${name}</td>
                    <td style="text-align:center">${exs.length}</td>
                    <td>
                      <div class="progress-cell">
                        <div class="progress-bar-bg"><div class="progress-bar-fill" data-w="${prog.pct}"></div></div>
                        <span class="progress-text">${prog.pct}%</span>
                      </div>
                    </td>
                    <td><span class="status-badge ${prog.status}"><span class="status-dot"></span>${statusLabel}</span></td>
                  </tr>`;
                }).join('')}
              </tbody>
            </table>
          </div>
        </div>

        <!-- Activity Feed -->
        <div class="dashboard-panel">
          <div class="panel-header">
            <h3><i class="fa-solid fa-bolt"></i> Actividad Reciente</h3>
            <span class="panel-badge">Live</span>
          </div>
          <div class="activity-feed">
            <div class="activity-item">
              <div class="activity-icon green"><i class="fa-solid fa-circle-check"></i></div>
              <div class="activity-info">
                <h4>Sector Educación completado</h4>
                <p>2 ejercicios finalizados &middot; hace 2 días</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon green"><i class="fa-solid fa-circle-check"></i></div>
              <div class="activity-info">
                <h4>Sector Cultura completado</h4>
                <p>2 ejercicios finalizados &middot; hace 3 días</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon amber"><i class="fa-solid fa-spinner"></i></div>
              <div class="activity-info">
                <h4>Transversal en progreso</h4>
                <p>2 de 11 ejercicios &middot; Actualizado hoy</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon blue"><i class="fa-solid fa-calendar-plus"></i></div>
              <div class="activity-info">
                <h4>Salud: 8 ejercicios planeados</h4>
                <p>Inicio programado &middot; Mayo 2026</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon amber"><i class="fa-solid fa-people-group"></i></div>
              <div class="activity-info">
                <h4>Juventudes en seguimiento</h4>
                <p>3 de 4 ejercicios &middot; hace 1 semana</p>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon blue"><i class="fa-solid fa-dove"></i></div>
              <div class="activity-info">
                <h4>Paz y DDHH: inicio próximo</h4>
                <p>6 ejercicios &middot; Convocatoria abierta</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>`;

    // Animate KPI counters
    animateCounter('kpi-total', totalEjercicios, 1200);
    animateCounter('kpi-sectores', totalSectores, 900);
    animateCounter('kpi-completados', completados, 800);
    animateCounter('kpi-avance', avgProgress, 1000);

    // Animate bars (after short delay)
    setTimeout(() => {
        document.querySelectorAll('.bar-fill[data-w]').forEach(el => {
            el.style.width = el.dataset.w + '%';
        });
        document.querySelectorAll('.progress-bar-fill[data-w]').forEach(el => {
            el.style.width = el.dataset.w + '%';
        });
    }, 100);
}

function animateCounter(id, target, duration) {
    const el = document.getElementById(id);
    if (!el) return;
    const start = performance.now();
    function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(eased * target);
        if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
}

// Initialize App
function init() {
    renderSidebar();
    setupThemeToggle();
    setupModal();
    renderDashboard();

    // Logo click → back to dashboard
    const sidebarHome = document.getElementById('sidebar-home');
    if (sidebarHome) {
        sidebarHome.addEventListener('click', () => renderDashboard());
    }
}

// Render Sidebar
function renderSidebar() {
    Object.keys(groupedData).sort().forEach(sector => {
        const div = document.createElement('div');
        div.className = 'sector-item';
        div.dataset.sector = sector;
        
        const iconClass = sectorIcons[sector] || 'fa-building';
        
        div.innerHTML = `
            <i class="fa-solid ${iconClass}"></i>
            <span>${sector}</span>
        `;
        
        div.addEventListener('click', () => {
            // Remove active class from all
            document.querySelectorAll('.sector-item').forEach(el => el.classList.remove('active'));
            // Add active to clicked
            div.classList.add('active');
            
            renderExercises(sector);
        });
        
        sectorMenu.appendChild(div);
    });
}

// Render Exercises for selected sector
function renderExercises(sector) {
    currentSectorTitle.textContent = sector;
    
    const exercises = groupedData[sector];
    
    let html = '<div class="exercises-grid">';
    exercises.forEach((exercise, index) => {
        const iconClass = sectorIcons[sector] || 'fa-folder';
        html += `
            <div class="exercise-card" onclick="openExerciseFolder('${escapeQuotes(exercise)}', '${escapeQuotes(sector)}')">
                <div class="exercise-icon">
                    <i class="fa-solid ${iconClass}"></i>
                </div>
                <h3>${exercise}</h3>
                <div class="card-footer">
                    <span>Explorar Carpetas</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    exercisesContainer.innerHTML = html;
}

function escapeQuotes(str) {
    return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

// Open Modal and render folder structure
window.openExerciseFolder = function(exercise, sector) {
    modalExerciseTitle.textContent = exercise;
    modalSectorBadge.textContent = sector;
    
    // Build folder tree HTML recursively
    folderBrowserContainer.innerHTML = buildTreeHtml(folderStructure, true);
    
    // Attach event listeners to collapsibles
    attachTreeListeners();
    
    folderModal.classList.add('active');
};

function buildTreeHtml(nodes, isOpen = false) {
    let html = '';
    nodes.forEach(node => {
        if (node.type === 'folder') {
            const hasChildren = node.children && node.children.length > 0;
            html += `
                <div class="tree-node">
                    <div class="tree-item collapsible ${isOpen ? 'open' : ''}">
                        <i class="fa-solid fa-chevron-right chevron" style="visibility: ${hasChildren ? 'visible' : 'hidden'}"></i>
                        <i class="fa-solid ${isOpen ? 'fa-folder-open' : 'fa-folder'}"></i>
                        <span>${node.name}</span>
                    </div>
                    ${hasChildren ? `
                    <div class="tree-children" style="display: ${isOpen ? 'block' : 'none'}">
                        ${buildTreeHtml(node.children, false)}
                    </div>
                    ` : ''}
                </div>
            `;
        } else {
            html += `
                <div class="tree-node">
                    <div class="tree-item">
                        <i style="visibility: hidden" class="fa-solid fa-chevron-right chevron"></i>
                        <i class="fa-solid ${node.icon || 'fa-file'}"></i>
                        <span>${node.name}</span>
                    </div>
                </div>
            `;
        }
    });
    return html;
}

function attachTreeListeners() {
    const collapsibles = folderBrowserContainer.querySelectorAll('.collapsible');
    collapsibles.forEach(col => {
        col.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('open');
            
            const folderIcon = this.querySelector('.fa-folder, .fa-folder-open');
            if (this.classList.contains('open')) {
                folderIcon.classList.remove('fa-folder');
                folderIcon.classList.add('fa-folder-open');
            } else {
                folderIcon.classList.remove('fa-folder-open');
                folderIcon.classList.add('fa-folder');
            }

            const childrenContainer = this.nextElementSibling;
            if (childrenContainer && childrenContainer.classList.contains('tree-children')) {
                if (childrenContainer.style.display === 'block') {
                    childrenContainer.style.display = 'none';
                } else {
                    childrenContainer.style.display = 'block';
                }
            }
        });
    });
}

// Modal Setup
function setupModal() {
    modalClose.addEventListener('click', () => {
        folderModal.classList.remove('active');
    });
    
    // Close on click outside
    folderModal.addEventListener('click', (e) => {
        if (e.target === folderModal) {
            folderModal.classList.remove('active');
        }
    });
}

// Theme Toggle Setup
function setupThemeToggle() {
    // Check user preference or set default light
    const isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    if (isDark) {
        document.documentElement.setAttribute('data-theme', 'dark');
        themeToggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
    }
    
    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        if (currentTheme === 'dark') {
            document.documentElement.removeAttribute('data-theme');
            themeToggle.innerHTML = '<i class="fa-solid fa-moon"></i>';
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeToggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
        }
    });
}

// Run init
init();
