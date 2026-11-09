<?php
$esc = static fn($v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$activos = ['Prestado', 'Reservado'];
$totalActivos = 0;
foreach ($prestamosPorTipo as $lista) {
    foreach ($lista as $prestamo) {
        if (in_array($prestamo['estado'], $activos, true)) $totalActivos++;
    }
}
?>
<?php require __DIR__ . '/../../includes/headersocio.php'; ?>


<main class="socio-main" id="mis-prestamos"style="padding-top: 100px;">
    <section class="socio-welcome">
        <div>
            <h1>Mis préstamos</h1>
            <p class="socio-greeting">Hola, <?= $esc($_SESSION['socio_nombre'] ?? 'socio') ?>. Aquí puedes consultar tus recursos.</p>
        </div>
        <div class="socio-summary" aria-label="Cantidad de préstamos activos">
            <span class="socio-summary-icon"><i class="fa-solid fa-book-open" style="color: rgb(255, 255, 255);"></i></i></span>
            <span><strong><?= $totalActivos ?></strong><small>préstamos activos</small></span>
        </div>
    </section>

    <?php foreach ($prestamosPorTipo as $tipo => $lista): ?>
        <?php $carpetaImagen = ['Libros' => 'portadas', 'Juegos' => 'juegos', 'Proyectos' => 'Proyectos'][$tipo]; ?>
        <?php $activosTipo = array_values(array_filter($lista, static fn($p) => in_array($p['estado'], $activos, true))); ?>
        <?php $historialTipo = array_values(array_filter($lista, static fn($p) => !in_array($p['estado'], $activos, true))); ?>
        <section class="socio-loan-card">
            <div class="socio-card-heading">
                <span class="socio-type-icon" aria-hidden="true">
                    <?php if ($tipo === 'Libros'): ?><i class="fa-solid fa-book" style="color: rgb(255, 255, 255);"></i></i><?php elseif ($tipo === 'Juegos'): ?><i class="fa-solid fa-dice"></i><?php else: ?><i class="fa-solid fa-lightbulb"></i><?php endif; ?>
                </span>
                <div><h2><?= $esc($tipo) ?></h2><p>Préstamos y movimientos de <?= $esc(strtolower($tipo)) ?></p></div>
            </div>
            <div class="socio-loan-section">
                <h3><span class="status-dot status-active"></span>Activos <span class="section-count"><?= count($activosTipo) ?></span></h3>
                <?php if (!$activosTipo): ?>
                    <p class="socio-empty"><i class="fa-regular fa-circle-check" aria-hidden="true"></i> No tienes préstamos activos de <?= $esc(strtolower($tipo)) ?>.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark socio-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Portada</th>
                                    <th>Artículo</th>
                                    <th>Estado</th>
                                    <th>Préstamo</th>
                                    <th>Devolver</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php foreach ($activosTipo as $p): ?>
                            <tr>
                                <td><img src="<?= $p['imagen'] ? '/carpeta2/uploads/' . rawurlencode($carpetaImagen) . '/' . rawurlencode(basename($p['imagen'])) : '/carpeta2/assets/sin-portada.webp' ?>"
                                 alt="Portada de <?= $esc($p['articulo']) ?>" 
                                 class="portada-prestamo" 
                                 style="max-height:100px;max-width:50px;object-fit:cover">
                                </td>
                                <td class="article-name"><?= $esc($p['articulo']) ?></td>
                                <td><span class="badge rounded-pill <?= $p['estado'] === 'Prestado' ? 'text-bg-primary' : 'text-bg-warning' ?>"><?= $esc($p['estado']) ?></span></td><td><?= $esc($p['fechaPrestamo'] ?: '—') ?></td><td><?= $esc($p['fechaDevolucion'] ?: '—') ?></td></tr><?php endforeach; ?>
                    </tbody></table></div>
                <?php endif; ?>
            </div>
            <div class="socio-loan-section socio-history">
                <h3><span class="status-dot status-history"></span>Historial <span class="section-count"><?= count($historialTipo) ?></span></h3>
                <?php if (!$historialTipo): ?>
                    <p class="socio-empty">Todavía no hay préstamos anteriores.</p>
                <?php else: ?>
                    <div class="table-responsive"><table class="table table-striped table-dark socio-table align-middle mb-0"><thead><tr><th>Portada</th><th>Artículo</th><th>Estado</th><th>Préstamo</th><th>Devolución</th></tr></thead><tbody>
                        <?php foreach ($historialTipo as $p): ?><tr><td><img src="<?= $p['imagen'] ? '/carpeta2/uploads/' . rawurlencode($carpetaImagen) . '/' . rawurlencode(basename($p['imagen'])) : '/carpeta2/assets/sin-portada.webp' ?>" alt="Portada de <?= $esc($p['articulo']) ?>" class="portada-prestamo" style="max-height:100px;max-width:50px;object-fit:cover"></td><td class="article-name"><?= $esc($p['articulo']) ?></td><td><span class="badge rounded-pill <?= ['Devuelto' => 'text-bg-success', 'Cancelado' => 'text-bg-danger'][$p['estado']] ?? 'text-bg-secondary' ?>"><?= $esc($p['estado']) ?></span></td><td><?= $esc($p['fechaPrestamo'] ?: '—') ?></td><td><?= $esc($p['fechaDevolucion'] ?: '—') ?></td></tr><?php endforeach; ?>
                    </tbody></table></div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</main>

<?php require __DIR__ . '/../../includes/footer2.php'; ?>

</body>
</html>
