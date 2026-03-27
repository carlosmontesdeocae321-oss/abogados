-- Seed completo: borra existentes y popula la tabla `abogados` con los datos proporcionados
-- Asegúrate de haber aplicado la migración db/alter_abogados_details.sql antes de ejecutar este script.

SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM abogados;
ALTER TABLE abogados AUTO_INCREMENT = 1;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO abogados (nombre, correo, celular, foto_carnet, foto_full, descripcion, area_practica, formacion, experiencia, docencia, publicaciones, distinciones, facebook, instagram, linkedin, twitter, whatsapp, cargo, destacado, fecha_creacion)
VALUES
('Alfonso Moisés Jiménez Pintado', NULL, NULL, NULL, NULL, 'Socio Fundador', NULL,
'• Abogado – Universidad de Guayaquil.\n• Candidato a Doctor (PhD) – Universidad Católica Andrés Bello.\n• Máster en Derecho Procesal Constitucional – Universidad Estatal de Milagro (UNEMI).\n• Máster en Derecho Procesal Penal – Universidad Estatal de Milagro (UNEMI).\n• Máster en Política Criminal y Derecho Penitenciario – Universidad de Guayaquil.\n• Máster en Criminología, Victimología y Delincuencia – Universidad Internacional de Valencia.',
NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Socio Fundador',1,NOW());

INSERT INTO abogados (nombre, cargo, formacion, destacado, fecha_creacion)
VALUES
('Débora Victoria Mora Mora', 'Presidenta Alfonso Jimenez y Asociados Firma Legal', '• Abogada – Universidad Bolivariana del Ecuador.', 0, NOW()),
('Silvia Patricia Jiménez Herrera', 'Gerente del Departamento Jurídico; Gerente del Departamento Financiero, Contable y Tributaria', '• Ingeniera Comercial – Universidad Laica Vicente Rocafuerte de Guayaquil.', 0, NOW()),
('Karen Helen Jácome Santillán', 'Responsable del Departamento de Comunicación Social e Imagen Corporativa', '• Licenciada en Periodismo – Universidad Laica Vicente Rocafuerte de Guayaquil.\n• Certificación en Capital Humano – Servicio Ecuatoriano de Capacitación Profesional SECAP.\n• Máster en Gestión del Talento Humano – Universidad Estatal de Milagro (UNEMI).', 0, NOW()),
('María Fernanda Ibarra Sevillano', 'Abogada', '• Abogada – Universidad de Guayaquil.\n• Doctorado – Universidad César Vallejo, Perú.\n• Máster en Derecho Procesal Penal – Universidad Estatal de Milagro (UNEMI).', 0, NOW()),
('Joel Jesús Navarrete García', 'Abogado', '• Abogado – Universidad Laica Vicente Rocafuerte de Guayaquil.\n• Máster en Derecho Procesal – Universidad Laica Vicente Rocafuerte de Guayaquil.', 0, NOW()),
('Ericka Elizabeth Bonilla Salazar', 'Abogada', '• Abogada – Universidad Técnica de Machala.\n• Magíster en Derecho Procesal Constitucional – Universidad Estatal de Milagro (UNEMI).', 0, NOW()),
('Ángel Roberto Hernández Nicola', 'Abogado', '• Abogado – Universidad Estatal de Milagro (UNEMI).\n• Máster en Derecho Procesal – Universidad Estatal de Milagro (UNEMI).', 0, NOW()),
('Medardo César Navarro González', 'Abogado', '• Abogado – Universidad Tecnológica ECOTEC.\n• Máster en Derecho Constitucional – Universidad Casa Grande.', 0, NOW()),
('Adriana Dennisse Miño Gualán', 'Abogada', '• Abogada – Universidad Tecnológica ECOTEC.\n• Máster en Criminología, Victimología y Delincuencia – Universidad Internacional de Valencia.', 0, NOW()),
('Carlos Andrés Albuja Tigrero', 'Abogado', '• Abogado – Universidad de Guayaquil.\n• Maestrante en Derecho Procesal Constitucional – Universidad Casa Grande.', 0, NOW()),
('Héctor Abel Garófalo Bajaña', 'Abogado', '• Abogado – Universidad Tecnológica ECOTEC.\n• Maestrante en Criminología, Victimología y Delincuencia – Universidad Internacional de Valencia.', 0, NOW()),
('Martha Tatiana Vera Cacao', 'Abogada', '• Abogada – Universidad de Guayaquil.\n• Maestrante en Derecho Procesal Penal – Universidad Estatal de Milagro (UNEMI).', 0, NOW()),
('Raquel Iraida Jiménez Pintado', 'Abogada', '• Abogada – Universidad Laica Vicente Rocafuerte de Guayaquil.\n• Maestrante en Derecho Procesal – Universidad Laica Vicente Rocafuerte de Guayaquil.', 0, NOW()),
('Luis Alberto Bajaña Solórzano', 'Abogado', '• Abogado – Universidad de Guayaquil.', 0, NOW()),
('Jaime Edingson González Bravo', 'Abogado', '• Abogado – Universidad Técnica Particular de Loja.', 0, NOW()),
('Jefferson Javier Villafuerte Sanabria', 'Abogado', '• Abogado – Universidad Técnica Particular de Loja.', 0, NOW()),
('Rodolfo Rolando Saldarriaga Solórzano', 'Abogado', '• Abogado – Universidad Técnica Particular de Loja.', 0, NOW()),
('David Peter Chávez Guale', 'Abogado', '• Abogado – Universidad Técnica Particular de Loja.', 0, NOW()),
('Gustavo Garófalo Bajaña', 'Estudiante / Pasante', '• Estudiante de Derecho – Universidad Tecnológica ECOTEC.\n• Pasante de la Universidad ECOTEC', 0, NOW());

-- Fin del seed completo
