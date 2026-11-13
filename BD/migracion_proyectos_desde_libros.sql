SET NAMES utf8mb4;

ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

START TRANSACTION;

INSERT INTO `proyectos` (
  `stock`,
  `nombre`,
  `integrantes`,
  `anio`,
  `cantidad_paginas`,
  `disponible`,
  `portada`,
  `numero_inventario`,
  `fecha_ingreso`,
  `observaciones`,
  `categoria_inventario`,
  `curso`,
  `clase`
)
SELECT
  l.`stock`,
  l.`titulo`,
  'Sin autor registrado',
  l.`anio_publicacion`,
  l.`cantidad_paginas`,
  CASE
    WHEN UPPER(l.`titulo`) LIKE '%BAJA%' THEN 0
    WHEN HEX(l.`disponible`) = '53C3AD' THEN 1
    ELSE 0
  END,
  l.`portada`,
  l.`numero_inventario`,
  l.`fecha_ingreso`,
  CONCAT_WS(
    CHAR(10),
    NULLIF(TRIM(l.`observaciones`), ''),
    CASE
      WHEN NULLIF(TRIM(l.`editorial`), '') IS NOT NULL
      THEN CONCAT('Editorial original: ', TRIM(l.`editorial`))
      ELSE NULL
    END,
    CASE
      WHEN NULLIF(TRIM(l.`isbn`), '') IS NOT NULL
      THEN CONCAT('ISBN original: ', TRIM(l.`isbn`))
      ELSE NULL
    END,
    CONCAT('Autor original: ', TRIM(l.`autor`))
  ),
  'Proyectos',
  TRIM(
    REPLACE(
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                CASE
                  WHEN TRIM(l.`autor`) LIKE 'Proyecto de %'
                  THEN SUBSTRING(TRIM(l.`autor`), 13)
                  ELSE SUBSTRING(TRIM(l.`autor`), 10)
                END,
                '2º', ''
              ),
              '3º', ''
            ),
            '3ro', ''
          ),
          '4to', ''
        ),
        '7º', ''
      ),
      ' Año', ''
    )
  ),
  CASE
    WHEN TRIM(l.`autor`) LIKE '%2º%' THEN '2°'
    WHEN TRIM(l.`autor`) LIKE '%3º%' OR TRIM(l.`autor`) LIKE '%3ro%' THEN '3°'
    WHEN TRIM(l.`autor`) LIKE '%4to%' THEN '4°'
    WHEN TRIM(l.`autor`) LIKE '%7º%' THEN '7°'
    ELSE ''
  END
FROM `libros` l
WHERE LOWER(TRIM(l.`autor`)) LIKE 'proyecto %';

DELETE FROM `libros`
WHERE LOWER(TRIM(`autor`)) LIKE 'proyecto %';

COMMIT;
