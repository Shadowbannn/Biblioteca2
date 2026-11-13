<?php if ($solicitud['estado'] === 'aceptada'): ?>
    <form method="post" action="index.php?accion=eliminarSolicitudAceptada" onsubmit="return confirm('¿Eliminar esta solicitud aceptada?')">
        <input type="hidden" name="csrf" value="<?= $esc($_SESSION['revision_solicitud_csrf']) ?>">
        <input type="hidden" name="id" value="<?= (int) $solicitud['id'] ?>">
        <input type="hidden" name="listado" value="<?= $esc($accionSolicitudes) ?>">
        <input type="hidden" name="pagina" value="<?= (int) $paginaActual ?>">
        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar solicitud aceptada" aria-label="Eliminar solicitud aceptada #<?= (int) $solicitud['id'] ?>">
            <i class="fa-solid fa-trash" aria-hidden="true"></i>
        </button>
    </form>
<?php else: ?>
    <span class="text-secondary">—</span>
<?php endif; ?>
