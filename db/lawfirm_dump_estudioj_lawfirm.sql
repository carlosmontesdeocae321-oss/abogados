-- SQL dump adapted for hosting DB `estudioj_lawfirm`
-- This file assumes you will import into the existing database `estudioj_lawfirm`
-- It does NOT attempt to CREATE the database or switch to it; import directly into your selected DB in phpMyAdmin.

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `faq_followups`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `faqs`;

-- Table: faqs
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `question` TEXT NOT NULL,
  `answer` TEXT NOT NULL,
  `keywords` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserts for faqs
INSERT INTO `faqs` (`question`, `answer`, `keywords`) VALUES
('¿Cómo agendo una consulta?', 'Para agendar una consulta, puede usar el botón Solicitar cita o enviarnos su nombre y número en el formulario; le contactaremos para confirmar.', 'agendar,cita,consulta'),
('¿Cuáles son sus honorarios?', 'Los honorarios varían según el tipo de caso. Envíe los detalles de su situación y le daremos una estimación inicial gratuita.', 'honorarios,precio,costo'),
('¿Atienden casos penales?', 'Sí, nuestro equipo penal defiende a personas en procesos penales y asesoramos desde la etapa de investigación hasta juicio.', 'penal,defensa,delito'),
('¿Dónde están ubicados?', 'Estamos ubicados en Torres de la Merced, Víctor Manuel Rendón, Guayaquil, piso 20.', 'ubicacion,direccion,donde'),
('¿Ofrecen servicios para empresas?', 'Sí, prestamos asesoría corporativa, contratos, cumplimiento normativo y representación en litigios comerciales.', 'empresas,corporativo,contratos'),
('¿Puedo iniciar una demanda civil?', 'Depende de su caso. Envíenos los hechos para evaluar viabilidad, plazos y costos para iniciar un proceso civil.', 'demanda,civil,juicio'),
('¿Qué documentos necesito traer a la primera consulta?', 'Traiga documentación relacionada: contratos, comunicaciones, identificaciones y cualquier prueba (fotos, mails) que tenga.', 'documentos,consulta,pruebas'),
('¿Atienden casos de derecho laboral?', 'Sí, asesoramos en despidos, reclamaciones de salarios, contratos laborales y juicios laborales.', 'laboral,despido,salarios'),
('¿Cuánto tarda un proceso judicial?', 'La duración varía mucho según la materia y la carga procesal; tras analizar su caso le daremos una estimación más precisa.', 'tiempo,duracion,proceso'),
('¿Puedo recibir asesoría online?', 'Sí, ofrecemos asesoría remota por videollamada o teléfono para comodidad de nuestros clientes.', 'online,videollamada,asesoria'),
('¿Cómo protegen la confidencialidad de mi caso?', 'Mantenemos estricta confidencialidad profesional y solo compartimos información con su autorización o por mandato judicial.', 'confidencialidad,privacidad,secreto'),
('¿Qué es lo primero que debo hacer ante una detención?', 'Pida hablar con un abogado y no firme nada sin asesoría; comuníquese con nosotros para orientación inmediata.', 'detencion,policia,arresto');

-- Table: faq_followups
CREATE TABLE IF NOT EXISTS `faq_followups` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `faq_id` INT NOT NULL,
  `question` TEXT NOT NULL,
  `answer` TEXT NOT NULL,
  `ord` INT DEFAULT 0,
  FOREIGN KEY (`faq_id`) REFERENCES `faqs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserts for followups (faq_id values assume the faqs inserts above produce sequential ids starting at 1)
INSERT INTO `faq_followups` (`faq_id`, `question`, `answer`, `ord`) VALUES
(1, '¿Cuánto cuesta una consulta inicial?', 'La consulta inicial tiene un costo simbólico o puede ser gratuita según la promoción vigente; pregunte al agendar.', 1),
(1, '¿Puedo pagar en línea?', 'Sí, aceptamos pagos por transferencia y en algunos casos pagos con tarjeta. Coordine con nuestro equipo administrativo.', 2),
(2, '¿Aceptan pagos en cuotas?', 'En ciertos casos podemos acordar pagos fraccionados; esto depende del tipo de representación y costos previstos.', 1),
(3, '¿Ofrecen defensa desde la etapa de flagrancia?', 'Sí, intervenimos desde la detención o citación, y trabajamos para proteger sus derechos inmediatamente.', 1),
(4, '¿Hay estacionamiento cerca?', 'Sí, en la misma torre hay estacionamiento público y alternativas cercanas en la avenida.', 1),
(5, '¿Pueden elaborar contratos a medida?', 'Sí, redactamos y revisamos contratos adaptados a su negocio y sector.', 1),
(6, '¿Cuáles son los plazos para iniciar una demanda?', 'Los plazos dependen de la causa; algunos asuntos prescriben en meses, otros en años. Es importante actuar pronto.', 1),
(7, '¿Aceptan casos por correo electrónico?', 'Sí, puede enviarnos documentación inicial por email y coordinaremos una revisión.', 1),
(8, '¿Atienden reclamaciones por despido injustificado?', 'Sí, representamos tanto a empleados como a empleadores en reclamaciones laborales.', 1),
(9, '¿Puedo recibir notificaciones por WhatsApp?', 'Podemos coordinar notificaciones por WhatsApp para actualizaciones rápidas, manteniendo la confidencialidad.', 1),
(10, '¿Qué duración tiene una videollamada típica?', 'Una videollamada inicial suele durar entre 20 y 40 minutos según la complejidad del caso.', 1);

-- Table: messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `page` VARCHAR(100) DEFAULT 'site',
  `message` TEXT NOT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `telefono` VARCHAR(60) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;

-- End of adapted dump
