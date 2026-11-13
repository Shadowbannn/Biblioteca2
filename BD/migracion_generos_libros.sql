SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `generos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_generos_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `libro_generos` (
  `libro_id` int(11) NOT NULL,
  `genero_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`libro_id`, `genero_id`),
  KEY `idx_libro_generos_genero` (`genero_id`),
  CONSTRAINT `fk_libro_generos_libro` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_libro_generos_genero` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TEMPORARY TABLE `migracion_generos_libros` (
  `legado` varchar(120) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  PRIMARY KEY (`legado`, `nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migracion_generos_libros` (`legado`, `nombre`) VALUES
('Adm Pers', 'Administración de personal'),
('Administ', 'Administración'),
('Arquitect.', 'Arquitectura'),
('Arte', 'Arte'),
('Biología', 'Biología'),
('Carp', 'Carpintería'),
('Cerrajería', 'Cerrajería'),
('Comics', 'Cómics'),
('Construcc', 'Construcción'),
('Contab', 'Contabilidad'),
('Derecho', 'Derecho'),
('Dibujo', 'Dibujo'),
('dicc- atlas', 'Diccionarios'),
('dicc- atlas', 'Atlas'),
('Economía', 'Economía'),
('Educac. Fis.', 'Educación física'),
('Educación', 'Educación'),
('Electronica', 'Electrónica'),
('Electrotec', 'Electrotecnia'),
('Filosofia', 'Filosofía'),
('Fisica', 'Física'),
('Follet y Proy', 'Folletos'),
('Gastronomía', 'Gastronomía'),
('Geograf', 'Geografía'),
('Historia', 'Historia'),
('Histórico', 'Histórico'),
('Horror, Ficción', 'Terror'),
('Horror, Ficción', 'Ficción'),
('Idioma Esp', 'Idioma español'),
('Infantil', 'Infantil'),
('Informat', 'Informática'),
('Ingles-Fr-Port', 'Inglés'),
('Ingles-Fr-Port', 'Francés'),
('Ingles-Fr-Port', 'Portugués'),
('Juvenil', 'Juvenil'),
('Juvenil, Romance', 'Juvenil'),
('Juvenil, Romance', 'Romance'),
('Laborat', 'Laboratorio'),
('Literat', 'Literatura'),
('Literatura, Ficción', 'Literatura'),
('Literatura, Ficción', 'Ficción'),
('Logística', 'Logística'),
('Mark- Pub-Cal', 'Marketing'),
('Mark- Pub-Cal', 'Publicidad'),
('Mark- Pub-Cal', 'Calidad'),
('Matemat', 'Matemática'),
('Mecánica', 'Mecánica'),
('NOV. GRÁFICAS', 'Novelas gráficas'),
('Peluquería Profesional', 'Peluquería profesional'),
('Quimica', 'Química'),
('Revistas', 'Revistas'),
('Revistas, Infantíl', 'Revistas'),
('Revistas, Infantíl', 'Infantil'),
('Romance, Juvenil', 'Romance'),
('Romance, Juvenil', 'Juvenil'),
('Segurid Ind', 'Seguridad industrial'),
('Sociol- Antrop', 'Sociología'),
('Sociol- Antrop', 'Antropología'),
('Tecnolog', 'Tecnología'),
('Thriller, Romance, Juvenil, Fantasía, Misterio', 'Suspenso'),
('Thriller, Romance, Juvenil, Fantasía, Misterio', 'Romance'),
('Thriller, Romance, Juvenil, Fantasía, Misterio', 'Juvenil'),
('Thriller, Romance, Juvenil, Fantasía, Misterio', 'Fantasía'),
('Thriller, Romance, Juvenil, Fantasía, Misterio', 'Misterio'),
('Trabajos', 'Trabajos'),
('Turismo', 'Turismo');

INSERT IGNORE INTO `generos` (`nombre`)
SELECT DISTINCT `nombre`
FROM `migracion_generos_libros`;

INSERT IGNORE INTO `generos` (`nombre`)
SELECT DISTINCT TRIM(l.`genero`)
FROM `libros` l
LEFT JOIN `migracion_generos_libros` m ON TRIM(l.`genero`) COLLATE utf8mb4_unicode_ci = m.`legado`
WHERE l.`genero` IS NOT NULL
  AND TRIM(l.`genero`) <> ''
  AND TRIM(l.`genero`) <> 'Sin género'
  AND m.`legado` IS NULL;

INSERT IGNORE INTO `libro_generos` (`libro_id`, `genero_id`)
SELECT l.`id`, g.`id`
FROM `libros` l
INNER JOIN `migracion_generos_libros` m ON TRIM(l.`genero`) COLLATE utf8mb4_unicode_ci = m.`legado`
INNER JOIN `generos` g ON g.`nombre` = m.`nombre`;

INSERT IGNORE INTO `libro_generos` (`libro_id`, `genero_id`)
SELECT l.`id`, g.`id`
FROM `libros` l
INNER JOIN `generos` g ON g.`nombre` = TRIM(l.`genero`) COLLATE utf8mb4_unicode_ci
LEFT JOIN `migracion_generos_libros` m ON TRIM(l.`genero`) COLLATE utf8mb4_unicode_ci = m.`legado`
WHERE l.`genero` IS NOT NULL
  AND TRIM(l.`genero`) <> ''
  AND TRIM(l.`genero`) <> 'Sin género'
  AND m.`legado` IS NULL;

UPDATE `libros`
SET `genero` = NULL;

DROP TEMPORARY TABLE `migracion_generos_libros`;
