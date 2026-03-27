-- Preview seed: 3 full abogados with complete profile fields
-- Use for local testing / preview only. Does NOT touch other tables.

SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM abogados;
ALTER TABLE abogados AUTO_INCREMENT = 1;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO abogados (nombre, correo, celular, foto_carnet, foto_full, descripcion, area_practica, formacion, experiencia, docencia, publicaciones, distinciones, facebook, instagram, linkedin, twitter, whatsapp, cargo, destacado, fecha_creacion)
VALUES
('Dra. Ana María Pérez', 'ana.perez@example.com', '+593987654321', '/uploads/abogados/ana_carnet.jpg', '/uploads/abogados/ana_full.jpg', 'Abogada especializada en derecho corporativo y comercio internacional. Asesora a empresas nacionales e internacionales en fusiones y adquisiciones.', 'Derecho corporativo',
'• Abogada – Universidad de Guayaquil.\n• Máster en Derecho Corporativo – Universidad de Salamanca.',
'• 8 años de experiencia en transacciones M&A, negociación de contratos y compliance corporativo.\n• Ex-abogada asociada en Bufete XYZ (2016-2020).',
'• Profesora invitada en la cátedra de Derecho Mercantil, Universidad de Guayaquil.',
'• "Contratos internacionales: guía práctica" (Revista Jurídica, 2021).',
'• Premio a la Excelencia Profesional 2022 - Cámara de Comercio.',
'https://facebook.com/ana.perez','https://instagram.com/anaperez','https://linkedin.com/in/anaperez','', '+593998001122','Socio Senior', 1, NOW()),

('Dr. Carlos Alberto Gómez', 'carlos.gomez@example.com', '+593999112233', '/uploads/abogados/carlos_carnet.jpg', '/uploads/abogados/carlos_full.jpg', 'Especialista en derecho penal económico y compliance; participación en casos de relevancia nacional en fraude y corrupción.', 'Derecho penal',
'• Abogado – Universidad Central del Ecuador.\n• Doctorado en Derecho Penal – Universidad Complutense de Madrid.',
'• 12 años de experiencia en litigios penales económicos y asesoría en prevención de delitos financieros.\n• Ha liderado equipos forenses en investigaciones complejas.',
'• Profesor de Derecho Penal en Universidad Católica desde 2018.',
'• Artículos en Revista Penal y Compliance (2019-2023).',
'• Distinción al Mérito Académico 2020.',
'https://facebook.com/carlos.gomez','','https://linkedin.com/in/carlosgomez','', '+593999334455','Director de Litigios', 1, NOW()),

('Mg. Laura Estefanía Ruiz', 'laura.ruiz@example.com', '+593988223344', '/uploads/abogados/laura_carnet.jpg', '/uploads/abogados/laura_full.jpg', 'Consultora en derecho laboral y seguridad social, con amplia experiencia en negociaciones colectivas y restructuraciones laborales.', 'Derecho laboral',
'• Abogada – Universidad Estatal de Milagro (UNEMI).\n• Magíster en Derecho Laboral – Universidad de Barcelona.',
'• 10 años asesorando procesos de reestructuración, negociación colectiva y políticas de recursos humanos.',
'• Ponente en congresos nacionales sobre derecho laboral.',
'• Capítulos en libros sobre representación sindical y negociación colectiva.',
'• Reconocimiento por mejores prácticas en relaciones laborales (2019).',
'','https://instagram.com/laura.ruiz','https://linkedin.com/in/lauraruiz','', '+593988556677','Abogada Senior', 0, NOW());

-- End preview seed
