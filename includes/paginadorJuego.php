<?php
if (!function_exists('urlPagina')) {
    function urlPagina($pagina)
    {
        $parametros = $_GET;
        $parametros['accion'] = 'listarJuegos';
        $parametros['pagina'] = $pagina;
        return 'index.php?' . http_build_query($parametros);
    }
}

$paginaActual = $paginaActual ?? 1;
$totalPaginas = $totalPaginas ?? 1;

if ($totalPaginas <= 1) {
    return;
}
?>

<div class="paginador mt-4">
    <ul class="pagination justify-content-center flex-wrap">

        <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= htmlspecialchars(urlPagina(1), ENT_QUOTES, 'UTF-8') ?>">« Primera</a>
        </li>

        <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= htmlspecialchars(urlPagina(max(1, $paginaActual - 1)), ENT_QUOTES, 'UTF-8') ?>">Anterior</a>
        </li>

        <?php
        $rango = 4;
        $inicio = max(1, $paginaActual - $rango);
        $fin = min($totalPaginas, $paginaActual + $rango);
        ?>

        <?php if ($inicio > 1): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>

        <?php for ($i = $inicio; $i <= $fin; $i++): ?>
            <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                <a class="page-link" href="<?= htmlspecialchars(urlPagina($i), ENT_QUOTES, 'UTF-8') ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($fin < $totalPaginas): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>

        <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= htmlspecialchars(urlPagina(min($totalPaginas, $paginaActual + 1)), ENT_QUOTES, 'UTF-8') ?>">Siguiente</a>
        </li>

        <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= htmlspecialchars(urlPagina($totalPaginas), ENT_QUOTES, 'UTF-8') ?>">Última »</a>
        </li>

    </ul>

    <div class="text-center mt-2">
        Página <strong><?= $paginaActual ?></strong> de <strong><?= $totalPaginas ?></strong>
    </div>
</div>
