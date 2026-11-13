<?php
$avisosSocio = [];
$consultasAvisosSocio = [
    "SELECT COALESCE(l.titulo, p.libroTitulo, 'Libro') AS articulo, p.fechaDevolucion FROM prestamos p LEFT JOIN libros l ON l.id = p.libroId WHERE p.socioId = ? AND p.estado = 'Prestado' AND p.fechaDevolucion <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)",
    "SELECT COALESCE(j.nombre, p.nombreJuego, 'Juego') AS articulo, p.fechaDevolucion FROM prestamosjuegos p LEFT JOIN juegos j ON j.id = p.juegoId WHERE p.socioId = ? AND p.estado = 'Prestado' AND p.fechaDevolucion <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)",
    "SELECT COALESCE(pr.nombre, p.nombreProyecto, 'Proyecto') AS articulo, p.fechaDevolucion FROM prestamosproyecto p LEFT JOIN proyectos pr ON pr.id = p.proyectoId WHERE p.socioId = ? AND p.estado = 'Prestado' AND p.fechaDevolucion <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)",
];
foreach ($consultasAvisosSocio as $consultaAvisoSocio) {
    $stmtAvisoSocio = $pdo->prepare($consultaAvisoSocio);
    $stmtAvisoSocio->execute([(int) $_SESSION['socio_id']]);
    $avisosSocio = array_merge($avisosSocio, $stmtAvisoSocio->fetchAll(PDO::FETCH_ASSOC));
}
$escAvisoSocio = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<li class="dropdown notificaciones-dropdown notificaciones-socio">
    <button type="button" class="btn btn-dark notificaciones-toggle" id="notificacionesSocioDropdown"
        data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"
        aria-label="Avisos de devolución: <?= count($avisosSocio) ?>">
        <i class="fa-solid fa-bell" aria-hidden="true"></i>
        <?php if ($avisosSocio): ?><span class="badge bg-danger"><?= count($avisosSocio) > 99 ? '99+' : count($avisosSocio) ?></span><?php endif; ?>
    </button>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark notificaciones-menu" aria-labelledby="notificacionesSocioDropdown">
        <div class="notificaciones-encabezado">
            <div>
                <span class="notificaciones-titulo-icono"><i class="fa-regular fa-bell" aria-hidden="true"></i></span>
                <div><h2>Avisos de devolución</h2><span><?= count($avisosSocio) ? count($avisosSocio) . ' por revisar' : 'Todo al día' ?></span></div>
            </div>
        </div>
        <?php if (!$avisosSocio): ?>
            <div class="notificaciones-vacias"><i class="fa-regular fa-circle-check" aria-hidden="true"></i><p>No tienes devoluciones próximas.</p></div>
        <?php else: ?>
            <div class="notificaciones-lista">
                <?php foreach ($avisosSocio as $aviso): ?>
                    <?php $fechaDevolucion = (string) ($aviso['fechaDevolucion'] ?? ''); $vencido = $fechaDevolucion !== '' && $fechaDevolucion < date('Y-m-d'); ?>
                    <a class="notificacion-item no-leida notificacion-socio-item" href="index.php?accion=misPrestamos#mis-prestamos">
                        <span class="notificacion-mensaje"><span class="notificacion-texto"><?= $vencido ? 'Devolución vencida: ' : 'Devolución próxima: ' ?><?= $escAvisoSocio($aviso['articulo']) ?></span><small><i class="fa-regular fa-calendar" aria-hidden="true"></i> <?= $escAvisoSocio($fechaDevolucion ? date('d/m/Y', strtotime($fechaDevolucion)) : 'Sin fecha') ?></small></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</li>
