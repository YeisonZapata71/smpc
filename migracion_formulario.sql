-- Creación de tabla para Formularios de Caracterización y Seguimiento
CREATE TABLE IF NOT EXISTS formularios_caracterizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ejercicio_id INT NOT NULL,
    usuario_id INT NOT NULL,
    
    -- Funcionario responsable
    dependencia VARCHAR(255) NULL,
    funcionario_nombre VARCHAR(255) NULL,
    funcionario_cargo VARCHAR(255) NULL,
    funcionario_telefono VARCHAR(50) NULL,
    funcionario_correo VARCHAR(255) NULL,
    
    -- Objetivo y Metodología
    objetivo TEXT NULL,
    metodologia TEXT NULL,
    
    -- Actividad
    act_fecha DATE NULL,
    act_zona_rural INT DEFAULT 0,
    act_zona_urbana INT DEFAULT 0,
    
    -- Asistencia Género
    genero_hombre INT DEFAULT 0,
    genero_mujer INT DEFAULT 0,
    
    -- Asistencia Edades
    edad_0_5 INT DEFAULT 0,
    edad_6_11 INT DEFAULT 0,
    edad_12_18 INT DEFAULT 0,
    edad_19_26 INT DEFAULT 0,
    edad_27_59 INT DEFAULT 0,
    edad_60_mas INT DEFAULT 0,
    
    -- Enfoque Diferencial (Obligatorios en UI, default 0 en BD para no romper)
    enfoque_afro INT DEFAULT 0,
    enfoque_indigena INT DEFAULT 0,
    enfoque_campesino INT DEFAULT 0,
    enfoque_discapacidad INT DEFAULT 0,
    enfoque_victima INT DEFAULT 0,
    enfoque_lgbti INT DEFAULT 0,
    enfoque_otro INT DEFAULT 0,
    enfoque_otro_desc VARCHAR(255) NULL,
    
    -- Total
    total_participantes INT DEFAULT 0,
    
    -- Instancias y Organizaciones
    instancias TEXT NULL,
    organizaciones TEXT NULL,
    
    -- Resultados y Lecciones
    aportes TEXT NULL,
    actuaciones TEXT NULL,
    canales_info TEXT NULL,
    lecciones TEXT NULL,
    buenas_practicas TEXT NULL,
    
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (ejercicio_id) REFERENCES ejercicios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
