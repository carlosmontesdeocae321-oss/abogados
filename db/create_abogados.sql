-- SQL: create abogados table
CREATE TABLE IF NOT EXISTS `abogados` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `correo` VARCHAR(255) DEFAULT NULL,
  `celular` VARCHAR(50) DEFAULT NULL,
  `foto` VARCHAR(512) DEFAULT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `area_practica` VARCHAR(100) DEFAULT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`area_practica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
