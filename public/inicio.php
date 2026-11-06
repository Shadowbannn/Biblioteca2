<?php
/**
 * views/inicio.php — Página de inicio pública para socios (BiblioTech)
 *
 * Variables que puede enviar el controlador (todas opcionales):
 *   $noticias          array de ['titulo', 'resumen', 'fecha', 'categoria']
 *   $librosDestacados  array de objetos Libro (getId, getTitulo, getAutor)
 *   $recomendaciones   array de ['titulo', 'autor', 'motivo', 'recomienda']
 *
 * Si no se envían, se usan los datos de ejemplo de abajo para ver el diseño.
 */

// ---------- Datos de ejemplo (borrar cuando conectes la base de datos) ----------
$noticias = $noticias ?? [
    ['titulo' => 'Nuevo horario de atención',
     'resumen' => 'A partir del próximo mes la biblioteca abre de 9:00 a 19:00 de lunes a viernes.',
     'fecha' => '2026-10-20', 'categoria' => 'Aviso'],
    ['titulo' => 'Taller de lectura para jóvenes',
     'resumen' => 'Todos los sábados de 10:00 a 12:00. Cupos limitados, inscríbete en el mostrador.',
     'fecha' => '2026-10-15', 'categoria' => 'Actividad'],
    ['titulo' => 'Llegaron nuevos ejemplares',
     'resumen' => 'Sumamos más de 40 títulos de narrativa, historia y educación a nuestro catálogo.',
     'fecha' => '2026-10-10', 'categoria' => 'Novedad'],
];

$recomendaciones = $recomendaciones ?? [
    ['titulo' => 'Cien años de soledad', 'autor' => 'Gabriel García Márquez',
     'motivo' => 'Un clásico imprescindible del realismo mágico.', 'recomienda' => 'Equipo de la biblioteca'],
    ['titulo' => 'El principito', 'autor' => 'Antoine de Saint-Exupéry',
     'motivo' => 'Breve, tierno y para todas las edades.', 'recomienda' => 'Equipo de la biblioteca'],
    ['titulo' => 'Los miserables', 'autor' => 'Victor Hugo',
     'motivo' => 'Para quienes buscan una gran historia de largo aliento.', 'recomienda' => 'Equipo de la biblioteca'],
];

// Fecha legible en español sin depender de la extensión intl
function fechaCorta(string $fecha): string {
    $meses = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
    $t = strtotime($fecha);
    return $t ? date('j', $t) . ' ' . $meses[(int)date('n', $t) - 1] . ' ' . date('Y', $t) : '';
}
$colores = ['#8d5a3b', '#3b5d8d', '#5a8d6b', '#8d3b5a', '#6b5a8d', '#8d7a3b'];


$demo = [
                    ['Cien años de soledad', 'Gabriel García Márquez'],
                    ['Don Quijote de la Mancha', 'Miguel de Cervantes'],
                    ['La sombra del viento', 'Carlos Ruiz Zafón'],
                    ['Rayuela', 'Julio Cortázar'],
                ];

// URL de la raíz: funciona tanto desde index.php como desde public/inicio.php.
$inicioScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$inicioDirectorio = rtrim(str_replace('\\', '/', dirname($inicioScript)), '/');
if (basename($inicioDirectorio) === 'public' || basename($inicioDirectorio) === 'views') {
    $inicioDirectorio = rtrim(str_replace('\\', '/', dirname($inicioDirectorio)), '/');
}
$inicioBaseUrl = ($inicioDirectorio === '.' ? '' : $inicioDirectorio) . '/';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BiblioTech — Inicio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>public/css/inicio.css">
</head>
<body>

<!-- ============ NAVEGACIÓN ============ -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php"><i class="bi bi-book-half me-2"></i>BiblioTech</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon" ></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link active" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#noticias">Noticias</a></li>
                <li class="nav-item"><a class="nav-link" href="#destacados">Destacados</a></li>
                <li class="nav-item"><a class="nav-link" href="#recomendaciones">Recomendaciones</a></li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-outline-light ms-lg-2" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=login">
                        <i class="bi bi-person-circle me-1"></i>Ingresar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ============ PORTADA + BUSCADOR ============ -->
<header class="hero text-center">
    <div class="container">
        <h1 class="mb-3">Tu próxima lectura te está esperando</h1>
        <p class="lead mb-4">Explora el catálogo, consulta tus préstamos y entérate de las novedades de la biblioteca.</p>
    </div>
</header>

<!-- ============ NOTICIAS ============ -->
<section id="noticias" class="seccion">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h2 class="seccion-titulo">Noticias</h2>
            <a href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=noticias" class="ver-mas">Ver todas <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row g-4">
            <?php foreach ($noticias as $n): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="card card-noticia">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-cat"><?= htmlspecialchars($n['categoria']) ?></span>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= fechaCorta($n['fecha']) ?></small>
                            </div>
                            <h3 class="h5"><?= htmlspecialchars($n['titulo']) ?></h3>
                            <p class="text-muted mb-0"><?= htmlspecialchars($n['resumen']) ?></p>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ LIBROS DESTACADOS ============ -->
<section id="destacados" class="seccion pt-0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h2 class="seccion-titulo">Libros destacados</h2>
            <a href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=catalogo" class="ver-mas">Ver catálogo <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (!empty($librosDestacados)): ?>
                <?php foreach ($librosDestacados as $i => $libro): ?>
                    <div class="col">
                        <div class="card card-libro">
                            <div class="portada portada-color-<?= $i % count($colores) ?>">
                                <i class="bi bi-book"></i>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title mb-1"><?= htmlspecialchars($libro->getTitulo()) ?></h3>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($libro->getAutor()) ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=libro&id=<?= $libro->getId() ?>" class="btn btn-sm btn-outline-dark w-100">Ver detalle</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Ejemplo mientras no hay datos reales -->
                <?php
                foreach ($demo as $i => [$t, $a]): ?>
                    <div class="col">
                        <div class="card card-libro">
                            <div class="portada portada-color-<?= $i % count($colores) ?>"><i class="bi bi-book"></i></div>
                            <div class="card-body">
                                <h3 class="card-title mb-1"><?= htmlspecialchars($t) ?></h3>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($a) ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pb-3">
                                <a href="#" class="btn btn-sm btn-outline-dark w-100">Ver detalle</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ RECOMENDACIONES ============ -->
<section id="recomendaciones" class="seccion bloque-recomendaciones">
    <div class="container">
        <h2 class="seccion-titulo mb-4">Recomendaciones para ti</h2>

        <div class="row g-4">
            <?php foreach ($recomendaciones as $r): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-reco">
                        <div class="card-body p-4">
                            <i class="bi bi-quote"></i>
                            <p class="fst-italic mt-2"><?= htmlspecialchars($r['motivo']) ?></p>
                            <hr>
                            <h3 class="h6 mb-0"><?= htmlspecialchars($r['titulo']) ?></h3>
                            <small class="text-muted"><?= htmlspecialchars($r['autor']) ?></small>
                            <div class="small mt-2 recomendacion-autor">
                                <i class="bi bi-person-check me-1"></i>Recomienda: <?= htmlspecialchars($r['recomienda']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ PIE DE PÁGINA ============ -->
<footer class="py-4">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6">
                <strong class="text-white">BiblioTech</strong><br>
                <small>Horario: lunes a viernes de 9:00 a 19:00</small>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=contacto" class="me-3">Contacto</a>
                <a href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=reglamento">Reglamento</a>
                <div class="small mt-2">&copy; <?= date('Y') ?> BiblioTech · Quantum Dev</div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>