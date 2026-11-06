<?php if ($solicitud['estado'] === 'pendiente'): ?>
    <form method="post" action="index.php?accion=revisarSolicitud" class="d-flex gap-2">
        <input type="hidden" name="csrf" value="<?= $esc($_SESSION['revision_solicitud_csrf']) ?>">
        <input type="hidden" name="id" value="<?= (int) $solicitud['id'] ?>">
        <input type="hidden" name="listado" value="<?= $esc($accionSolicitudes) ?>">
        <input type="hidden" name="pagina" value="<?= (int) $paginaActual ?>">
        <?php if ($accionRevision === 'aceptada'): ?>
            <button type="submit" name="estado" value="aceptada" class="btn btn-warning btn-sm" title="Aceptar solicitud" aria-label="Aceptar solicitud #<?= (int) $solicitud['id'] ?>">
                <i class="fa-solid fa-check" aria-hidden="true"></i>
            </button>
        <?php else: ?>
            <button type="submit" name="estado" value="rechazada" class="btn btn-danger btn-sm" title="Rechazar solicitud" aria-label="Rechazar solicitud #<?= (int) $solicitud['id'] ?>">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        <?php endif; ?>
    </form>
<?php else: ?>
    <span class="text-secondary">—</span>
<?php endif; ?>