SET NAMES utf8mb4;

ALTER TABLE `libros`
  ADD COLUMN `codigo_dewey` varchar(20) NOT NULL DEFAULT '000' AFTER `numero_inventario`;

UPDATE `libros` l
SET l.`codigo_dewey` = COALESCE(
  CASE
    WHEN l.`categoria_inventario` = 'Adm Pers' THEN '658.3'
    WHEN l.`categoria_inventario` = 'Administ' THEN '658'
    WHEN l.`categoria_inventario` = 'Arquitect.' THEN '720'
    WHEN l.`categoria_inventario` = 'Arte' THEN '700'
    WHEN l.`categoria_inventario` = 'Biología' THEN '570'
    WHEN l.`categoria_inventario` = 'Carp' THEN '684'
    WHEN l.`categoria_inventario` = 'Cerrajería' THEN '683'
    WHEN l.`categoria_inventario` = 'Comics' THEN '741.5'
    WHEN l.`categoria_inventario` = 'Construcc' THEN '690'
    WHEN l.`categoria_inventario` = 'Contab' THEN '657'
    WHEN l.`categoria_inventario` = 'Derecho' THEN '340'
    WHEN l.`categoria_inventario` = 'Dibujo' THEN '741.2'
    WHEN l.`categoria_inventario` = 'dicc- atlas' THEN
      CASE WHEN LOWER(l.`titulo`) LIKE '%atlas%' THEN '912' ELSE '030' END
    WHEN l.`categoria_inventario` = 'Economía' THEN '330'
    WHEN l.`categoria_inventario` = 'Educac. Fis.' THEN '796'
    WHEN l.`categoria_inventario` = 'Educación' THEN '370'
    WHEN l.`categoria_inventario` = 'Electronica' THEN '621.38'
    WHEN l.`categoria_inventario` = 'Electrotec' THEN '621.3'
    WHEN l.`categoria_inventario` = 'Filosofia' THEN '100'
    WHEN l.`categoria_inventario` = 'Fisica' THEN '530'
    WHEN l.`categoria_inventario` = 'Follet y Proy' THEN '000'
    WHEN l.`categoria_inventario` = 'Gastronomía' THEN '641.5'
    WHEN l.`categoria_inventario` = 'Geograf' THEN '910'
    WHEN l.`categoria_inventario` = 'Historia' THEN '900'
    WHEN l.`categoria_inventario` = 'Idioma Esp' THEN '460'
    WHEN l.`categoria_inventario` = 'Informat' THEN '004'
    WHEN l.`categoria_inventario` = 'Ingles-Fr-Port' THEN '400'
    WHEN l.`categoria_inventario` = 'Laborat' THEN '507'
    WHEN l.`categoria_inventario` = 'Literat' THEN '800'
    WHEN l.`categoria_inventario` = 'Logística' THEN '658.5'
    WHEN l.`categoria_inventario` = 'Mark- Pub-Cal' THEN '658'
    WHEN l.`categoria_inventario` = 'Matemat' THEN '510'
    WHEN l.`categoria_inventario` = 'Mecánica' THEN '621.8'
    WHEN l.`categoria_inventario` = 'NOV. GRÁFICAS' THEN '741.5'
    WHEN l.`categoria_inventario` = 'Peluquería Profesional' THEN '646.72'
    WHEN l.`categoria_inventario` = 'Quimica' THEN '540'
    WHEN l.`categoria_inventario` = 'Revistas' THEN '050'
    WHEN l.`categoria_inventario` = 'Segurid Ind' THEN '363.11'
    WHEN l.`categoria_inventario` = 'Sociol- Antrop' THEN '300'
    WHEN l.`categoria_inventario` = 'Tecnolog' THEN '600'
    WHEN l.`categoria_inventario` = 'Turismo' THEN '910.2'
    ELSE NULL
  END,
  (
    SELECT CASE g.`nombre`
      WHEN 'Administración' THEN '658'
      WHEN 'Administración de personal' THEN '658.3'
      WHEN 'Antropología' THEN '301.2'
      WHEN 'Arquitectura' THEN '720'
      WHEN 'Arte' THEN '700'
      WHEN 'Atlas' THEN '912'
      WHEN 'Biología' THEN '570'
      WHEN 'Calidad' THEN '658.56'
      WHEN 'Carpintería' THEN '684'
      WHEN 'Cerrajería' THEN '683'
      WHEN 'Cómics' THEN '741.5'
      WHEN 'Construcción' THEN '690'
      WHEN 'Contabilidad' THEN '657'
      WHEN 'Derecho' THEN '340'
      WHEN 'Dibujo' THEN '741.2'
      WHEN 'Diccionarios' THEN '030'
      WHEN 'Economía' THEN '330'
      WHEN 'Educación' THEN '370'
      WHEN 'Educación física' THEN '796'
      WHEN 'Electrónica' THEN '621.38'
      WHEN 'Electrotecnia' THEN '621.3'
      WHEN 'Fantasía' THEN '800'
      WHEN 'Ficción' THEN '800'
      WHEN 'Filosofía' THEN '100'
      WHEN 'Física' THEN '530'
      WHEN 'Folletos' THEN '000'
      WHEN 'Francés' THEN '440'
      WHEN 'Gastronomía' THEN '641.5'
      WHEN 'Geografía' THEN '910'
      WHEN 'Historia' THEN '900'
      WHEN 'Histórico' THEN '900'
      WHEN 'Idioma español' THEN '460'
      WHEN 'Infantil' THEN '800'
      WHEN 'Informática' THEN '004'
      WHEN 'Inglés' THEN '420'
      WHEN 'Juvenil' THEN '800'
      WHEN 'Laboratorio' THEN '507'
      WHEN 'Literatura' THEN '800'
      WHEN 'Logística' THEN '658.5'
      WHEN 'Marketing' THEN '658.8'
      WHEN 'Matemática' THEN '510'
      WHEN 'Mecánica' THEN '621.8'
      WHEN 'Misterio' THEN '800'
      WHEN 'Novelas gráficas' THEN '741.5'
      WHEN 'Peluquería profesional' THEN '646.72'
      WHEN 'Portugués' THEN '469'
      WHEN 'Publicidad' THEN '659.1'
      WHEN 'Química' THEN '540'
      WHEN 'Revistas' THEN '050'
      WHEN 'Romance' THEN '800'
      WHEN 'Seguridad industrial' THEN '363.11'
      WHEN 'Sociología' THEN '301'
      WHEN 'Suspenso' THEN '800'
      WHEN 'Tecnología' THEN '600'
      WHEN 'Terror' THEN '800'
      WHEN 'Trabajos' THEN '000'
      WHEN 'Turismo' THEN '910.2'
      ELSE NULL
    END
    FROM `libro_generos` lg
    INNER JOIN `generos` g ON g.`id` = lg.`genero_id`
    WHERE lg.`libro_id` = l.`id`
      AND g.`nombre` IN (
        'Administración', 'Administración de personal', 'Antropología', 'Arquitectura', 'Arte',
        'Atlas', 'Biología', 'Calidad', 'Carpintería', 'Cerrajería', 'Cómics', 'Construcción',
        'Contabilidad', 'Derecho', 'Dibujo', 'Diccionarios', 'Economía', 'Educación',
        'Educación física', 'Electrónica', 'Electrotecnia', 'Fantasía', 'Ficción', 'Filosofía',
        'Física', 'Folletos', 'Francés', 'Gastronomía', 'Geografía', 'Historia', 'Histórico',
        'Idioma español', 'Infantil', 'Informática', 'Inglés', 'Juvenil', 'Laboratorio',
        'Literatura', 'Logística', 'Marketing', 'Matemática', 'Mecánica', 'Misterio',
        'Novelas gráficas', 'Peluquería profesional', 'Portugués', 'Publicidad', 'Química',
        'Revistas', 'Romance', 'Seguridad industrial', 'Sociología', 'Suspenso', 'Tecnología',
        'Terror', 'Trabajos', 'Turismo'
      )
    ORDER BY g.`nombre`
    LIMIT 1
  ),
  CASE
    WHEN LOWER(l.`titulo`) LIKE '%atlas%' THEN '912'
    WHEN LOWER(l.`titulo`) LIKE '%dicc%' OR LOWER(l.`titulo`) LIKE '%enciclop%' THEN '030'
    ELSE NULL
  END,
  '000'
);
