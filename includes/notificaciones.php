<?php
require_once __DIR__ . '/../models/notificacion.php';
$notificaciones = Notificacion::recientes($pdo, (int) $_SESSION['usuario_id']);
$sinLeer = Notificacion::contarPendientes($pdo, (int) $_SESSION['usuario_id']);
$_SESSION['notificaciones_csrf'] ??= bin2hex(random_bytes(32));
$escNotificacion = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<li class="dropdown">
    <button type="button" class="btn btn-dark" id="notificacionesDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Ver notificaciones: <?= $sinLeer ?> sin leer">
        <i class="fa-solid fa-bell" aria-hidden="true"></i>
        <?php if ($sinLeer > 0): ?><span class="badge bg-danger"><?= $sinLeer ?></span><?php endif; ?>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificacionesDropdown" style="width: 340px; max-width: 90vw; max-height: 420px; overflow-y: auto;">
        <h6 class="dropdown-header">Notificaciones</h6>
        <?php if ($sinLeer > 0): ?>
            <form method="post" action="index.php?accion=gestionarNotificacion" class="px-3 mb-2">
                <input type="hidden" name="csrf" value="<?= $escNotificacion($_SESSION['notificaciones_csrf']) ?>">
                <input type="hidden" name="operacion" value="todas">
                <button class="btn btn-outline-secondary btn-sm" type="submit">Marcar todas como leídas</button>
            </form>
        <?php endif; ?>
        <?php if (!$notificaciones): ?>
            <p class="px-3 py-2 mb-0 text-muted small">No hay notificaciones.</p>
        <?php endif; ?>
        <?php foreach ($notificaciones as $notificacion): ?>
            <form method="post" action="index.php?accion=gestionarNotificacion">
                <input type="hidden" name="csrf" value="<?= $escNotificacion($_SESSION['notificaciones_csrf']) ?>">
                <input type="hidden" name="operacion" value="abrir">
                <input type="hidden" name="id" value="<?= (int) $notificacion['id'] ?>">
                <button type="submit" class="dropdown-item text-wrap <?= !$notificacion['leida'] ? 'fw-bold' : '' ?>">
                    <?= $escNotificacion($notificacion['mensaje']) ?>
                    <small class="d-block text-muted"><?= $escNotificacion($notificacion['creada_en']) ?></small>
                </button>
            </form>
        <?php endforeach; ?>
    </div>
</li>
