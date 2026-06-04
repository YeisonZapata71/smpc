-- Creación de la base de datos y tablas

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'user') DEFAULT 'user',
    token_recuperacion VARCHAR(100) NULL,
    token_expiracion DATETIME NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sectores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    sector_id INT NOT NULL,
    FOREIGN KEY (sector_id) REFERENCES sectores(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS usuarios_ejercicios (
    usuario_id INT NOT NULL,
    ejercicio_id INT NOT NULL,
    PRIMARY KEY (usuario_id, ejercicio_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (ejercicio_id) REFERENCES ejercicios(id) ON DELETE CASCADE
);

-- Insertar el usuario administrador por defecto
-- La contraseña por defecto será 'admin123' (El hash es para 'admin123')
INSERT INTO usuarios (nombre, correo, password, rol) VALUES 
('Administrador', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');


-- InserciÃ³n de datos migrados desde data.json

INSERT INTO sectores (id, nombre) VALUES 
(1, 'Afrodescendiente'),
(2, 'Agricultura y desarrollo rural'),
(3, 'Ambiente y desarrollo sostenible'),
(4, 'Comercio, industria y turismo'),
(5, 'Cultura'),
(6, 'Educación'),
(7, 'Juventudes'),
(8, 'Mujeres'),
(9, 'Niñez'),
(10, 'Paz y derechos humanos'),
(11, 'Personas con discapacidad'),
(12, 'Personas LGBTIQ+'),
(13, 'Personas mayores'),
(14, 'Salud'),
(15, 'Transporte'),
(16, 'Transversal'),
(17, 'Vivienda y servicios públicos');

INSERT INTO ejercicios (id, nombre, sector_id) VALUES 
(1, 'Caracterización del territorio y la población afrodescendiente ', 1),
(2, 'Consulta a la población afrodescendiente relativa a instrumentos de política pública y de planificación territorial ', 1),
(3, 'Implementación de estrategias de fortalecimiento a organizaciones agropecuarias', 2),
(4, 'Implementación del plan agropecuario mundial (PAM) ', 2),
(5, 'Control social al plan agropecuario mundial (PAM) ', 2),
(6, 'Diagnóstico o caracterización de asociaciones agropecuarias', 2),
(7, 'Diagnóstico de la última Feria del campo y la cosecha', 2),
(8, 'Implementación del Mercado Agro Verde para la comercialización de productos agropecuarios', 2),
(9, 'Días de campo: priorización de necesidades de asistencia técnica y extensión agropecuaria', 2),
(10, 'Días de campo: rendición de cuentas de la Secretaría de Agricultura y Desarrollo Rural', 2),
(11, 'Implementación de estrategias de educación ambiental ', 3),
(12, 'Actualización del acto administrativo que reglamenta el CIDEAM', 3),
(13, 'Creación de rutas para la atención y seguimiento a casos de maltrato animal', 3),
(14, 'Implementación de la política pública de protección y bienestar animal', 3),
(15, 'Implementación de iniciativas de sostenibilidad ambiental', 3),
(16, 'Implementación de campañas para la gestión de cambio climático', 3),
(17, 'Un viaje por los sonidos de nuestro territorio: Identificación de niveles de ruido y presencia de fauna a través de ejercicios de cartografía socioambiental y juego interactivo', 3),
(18, 'Implementación de estrategias educativas y ambientales para reconocimiento, dignificación y fortalecimiento de capacidades de recuperadores ambientales', 3),
(19, 'Implementación del Plan Estratégico y la Política Pública de Turismo', 4),
(20, 'Control social del Plan Estratégico y la Política Pública de Turismo', 4),
(21, 'Selección de emprendedores beneficiarios de incentivos financieros del programa Red de Economía Social y Solidaria (REDESS)', 4),
(22, 'Control social a los recursos del programa Red de Economía Social y Solidaria (REDESS)', 4),
(23, 'Implementación del plan estratégico y la política pública de cultura', 5),
(24, 'Implementación de iniciativas ciudadanas para dinamización del sector cultural en sus eslabones de creación, formación, participación, investigación, circulación y patrimonio', 5),
(25, 'Control social al Programa de Alimentación Escolar (PAE)', 6),
(26, 'Implementación de las políticas educativas', 6),
(27, 'Implementación del programa Girardota es Joven y la oferta institucional para las juventudes', 7),
(28, 'Actualización de la Política Pública de Juventud ', 7),
(29, 'Implementación de estrategias para la promoción de la ciudadanía y el liderazgo juvenil', 7),
(30, 'Asamblea Subregional de Juventudes', 7),
(31, 'Implementación de la política pública de mujer y género', 8),
(32, 'Control social a la política pública de mujer y género', 8),
(33, 'Implementación de estrategias de prevención y atención de violencias basadas en género', 8),
(34, 'Actualización de diagnósticos del sector niñez', 9),
(35, 'Construcción de política pública de infancia y primera infancia', 9),
(36, 'Implementación de campañas para la garantía de derechos de la niñez', 9),
(37, 'Implementación Plan de Acción Territorial (PAT) de la Política Pública Nacional de Víctimas', 10),
(38, 'Control social del Plan de Acción Territorial (PAT) de la Política Pública Nacional de Víctimas', 10),
(39, 'Priorización de problemáticas de convivencia y seguridad', 10),
(40, 'Implementación de estrategias de promoción de la paz, la convivencia, la seguridad, la reconciliación y la no estigmatización', 10),
(41, 'Implementación de estrategias de atención y protección a líderes y lideresas sociales y otras poblaciones especiales.  ', 10),
(42, 'Implementación de la política pública de libertad religiosa y de cultos', 10),
(43, 'Implementación del programa y la Política Pública de Discapacidad', 11),
(44, 'Control social al programa y la Política Pública de Discapacidad', 11),
(45, 'Caracterización de personas con discapacidad y cuidadores y priorización de necesidades', 11),
(46, 'Implementación del programa Transformación con Equidad en la Diversidad', 12),
(47, 'Formulación participativa de política pública para la población LGBTI', 12),
(48, 'Implementación de estrategias para la garantía de derechos de las personas mayores', 13),
(49, 'Actualización de la política pública de envejecimiento y vejez', 13),
(50, 'Control social a la política pública de envejecimiento y vejez', 13),
(51, 'Identificación y notificación de eventos de interés en salud pública', 14),
(52, 'Implementación de la política pública de seguridad alimentaria', 14),
(53, 'Veeduría ciudadana al Plan de Intervenciones Colectivas (PIC) y a los Equipos Básicos en Salud', 14),
(54, 'Implementación de jornadas Tu EPS al Parque para resolución de PQRS por barreras de acceso a los servicios de salud en el municipio', 14),
(55, 'Diálogo de líderes e instancias de participación en salud con EPS e IPS para mejorar el acceso a servicios de salud en el municipio ', 14),
(56, 'Implementación de jornadas de promoción de la salud mental en entornos familiares y comunitarios ', 14),
(57, 'Implementación de estrategias para fortalecer el Programa Ampliado de Inmunizaciones (PAI)', 14),
(58, 'Rendición pública de cuentas de la Secretaría de Salud y Protección Social para informar a la ciudadanía sobre los avances en la implementación del Plan Territorial de Salud en la vigencia 2026, generar diálogo y retroalimentación al respecto y asumir compromisos de mejoramiento', 14),
(59, 'Priorización de problemáticas de movilidad y seguridad vial', 15),
(60, 'Implementación de estrategias para avanzar hacia un sistema de transporte más eficiente, seguro y respetuoso con el entorno', 15),
(61, 'Control social a la implementación del Plan de Desarrollo Territorial Decencia en lo Público para el año 2026 ', 16),
(62, 'Elaboración y divulgación de boletín con agenda o cronograma de los ejercicios participativos ', 16),
(63, 'Implementación de estrategia lúdico – recreativa dirigida a la comunidad para la promoción de la participación ciudadana y el control social', 16),
(64, 'Desarrollo de instrumento de participación para canalizar y hacer seguimiento a solicitudes de las instancias de participación y la comunidad a través de sus representantes', 16),
(65, 'Jornadas de socialización y cabildo abierto para la formulación del Plan Básico de Ordenamiento Territorial (PBOT)', 16),
(66, 'Socialización y retroalimentación de proyectos formulados con la población objetivo desde el Banco de Programas y Proyectos', 16),
(67, 'Control social a políticas públicas sociales', 16),
(68, 'Priorización e implementación de proyectos de inversión pública a través de la política pública de presupuesto participativo ', 16),
(69, 'Control social a los proyectos de presupuesto participativo por parte de comités veedores y el Consejo Municipal de Participación Ciudadana', 16),
(70, 'Rendición pública de cuentas sobre los proyectos ejecutados con recursos de presupuesto participativo', 16),
(71, 'Audiencia pública de rendición de cuentas de la Administración Municipal para informar a la ciudadanía sobre los avances en la implementación del Plan de Desarrollo Municipal en la vigencia 2026, generar diálogo y retroalimentación al respecto y asumir compromisos de mejoramiento ', 16),
(72, 'Control social a la aplicación y actualización de la estratificación urbana y rural para servicios públicos', 17),
(73, 'Resolución en segunda instancia reclamos por la aplicación de la estratificación urbana y rural ', 17),
(74, 'Reactivación de la Mesa técnica para el fortalecimiento de las comunidades organizadas que prestan servicios públicos e implementación de política pública', 17),
(75, 'Audiencia pública sobre actualización catastral', 17);

- -   C r e a c i � � n   d e   t a b l a   p a r a   F o r m u l a r i o s   d e   C a r a c t e r i z a c i � � n   y   S e g u i m i e n t o  
 C R E A T E   T A B L E   I F   N O T   E X I S T S   f o r m u l a r i o s _ c a r a c t e r i z a c i o n   (  
         i d   I N T   A U T O _ I N C R E M E N T   P R I M A R Y   K E Y ,  
         e j e r c i c i o _ i d   I N T   N O T   N U L L ,  
         u s u a r i o _ i d   I N T   N O T   N U L L ,  
          
         - -   F u n c i o n a r i o   r e s p o n s a b l e  
         d e p e n d e n c i a   V A R C H A R ( 2 5 5 )   N U L L ,  
         f u n c i o n a r i o _ n o m b r e   V A R C H A R ( 2 5 5 )   N U L L ,  
         f u n c i o n a r i o _ c a r g o   V A R C H A R ( 2 5 5 )   N U L L ,  
         f u n c i o n a r i o _ t e l e f o n o   V A R C H A R ( 5 0 )   N U L L ,  
         f u n c i o n a r i o _ c o r r e o   V A R C H A R ( 2 5 5 )   N U L L ,  
          
         - -   O b j e t i v o   y   M e t o d o l o g � � a  
         o b j e t i v o   T E X T   N U L L ,  
         m e t o d o l o g i a   T E X T   N U L L ,  
          
         - -   A c t i v i d a d  
         a c t _ f e c h a   D A T E   N U L L ,  
         a c t _ z o n a _ r u r a l   I N T   D E F A U L T   0 ,  
         a c t _ z o n a _ u r b a n a   I N T   D E F A U L T   0 ,  
          
         - -   A s i s t e n c i a   G � � n e r o  
         g e n e r o _ h o m b r e   I N T   D E F A U L T   0 ,  
         g e n e r o _ m u j e r   I N T   D E F A U L T   0 ,  
          
         - -   A s i s t e n c i a   E d a d e s  
         e d a d _ 0 _ 5   I N T   D E F A U L T   0 ,  
         e d a d _ 6 _ 1 1   I N T   D E F A U L T   0 ,  
         e d a d _ 1 2 _ 1 8   I N T   D E F A U L T   0 ,  
         e d a d _ 1 9 _ 2 6   I N T   D E F A U L T   0 ,  
         e d a d _ 2 7 _ 5 9   I N T   D E F A U L T   0 ,  
         e d a d _ 6 0 _ m a s   I N T   D E F A U L T   0 ,  
          
         - -   E n f o q u e   D i f e r e n c i a l   ( O b l i g a t o r i o s   e n   U I ,   d e f a u l t   0   e n   B D   p a r a   n o   r o m p e r )  
         e n f o q u e _ a f r o   I N T   D E F A U L T   0 ,  
         e n f o q u e _ i n d i g e n a   I N T   D E F A U L T   0 ,  
         e n f o q u e _ c a m p e s i n o   I N T   D E F A U L T   0 ,  
         e n f o q u e _ d i s c a p a c i d a d   I N T   D E F A U L T   0 ,  
         e n f o q u e _ v i c t i m a   I N T   D E F A U L T   0 ,  
         e n f o q u e _ l g b t i   I N T   D E F A U L T   0 ,  
         e n f o q u e _ o t r o   I N T   D E F A U L T   0 ,  
         e n f o q u e _ o t r o _ d e s c   V A R C H A R ( 2 5 5 )   N U L L ,  
          
         - -   T o t a l  
         t o t a l _ p a r t i c i p a n t e s   I N T   D E F A U L T   0 ,  
          
         - -   I n s t a n c i a s   y   O r g a n i z a c i o n e s  
         i n s t a n c i a s   T E X T   N U L L ,  
         o r g a n i z a c i o n e s   T E X T   N U L L ,  
          
         - -   R e s u l t a d o s   y   L e c c i o n e s  
         a p o r t e s   T E X T   N U L L ,  
         a c t u a c i o n e s   T E X T   N U L L ,  
         c a n a l e s _ i n f o   T E X T   N U L L ,  
         l e c c i o n e s   T E X T   N U L L ,  
         b u e n a s _ p r a c t i c a s   T E X T   N U L L ,  
          
         f e c h a _ c r e a c i o n   T I M E S T A M P   D E F A U L T   C U R R E N T _ T I M E S T A M P ,  
         f e c h a _ a c t u a l i z a c i o n   T I M E S T A M P   D E F A U L T   C U R R E N T _ T I M E S T A M P   O N   U P D A T E   C U R R E N T _ T I M E S T A M P ,  
          
         F O R E I G N   K E Y   ( e j e r c i c i o _ i d )   R E F E R E N C E S   e j e r c i c i o s ( i d )   O N   D E L E T E   C A S C A D E ,  
         F O R E I G N   K E Y   ( u s u a r i o _ i d )   R E F E R E N C E S   u s u a r i o s ( i d )   O N   D E L E T E   S E T   N U L L  
 ) ;  
 