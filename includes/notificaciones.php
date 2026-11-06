<?php
require_once __DIR__ . '/../models/notificacion.php';
$usuarioNotificacionesId = (int) $_SESSION['usuario_id'];
$notificaciones = Notificacion::recientes($pdo, $usuarioNotificacionesId);
$sinLeer = Notificacion::contarPendientes($pdo, $usuarioNotificacionesId);
$_SESSION['notificaciones_csrf'] ??= bin2hex(random_bytes(32));
$escNotificacion = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<li class="dropdown notificaciones-dropdown">
    <button type="button" class="btn btn-dark notificaciones-toggle" id="notificacionesDropdown"
        data-bs-toggle="dropdown" aria-expanded="false"
        aria-label="Ver notificaciones: <?= $sinLeer ?> sin leer">
        <i class="fa-solid fa-bell" aria-hidden="true"></i>
        <?php if ($sinLeer > 0): ?><span class="badge bg-danger"><?= $sinLeer ?></span><?php endif; ?>
    </button>

    <div class="dropdown-menu dropdown-menu-dark notificaciones-menu" aria-labelledby="notificacionesDropdown">
        <div class="notificaciones-encabezado">
            <div>
                <h2>Notificaciones</h2>
                <span><?= $sinLeer ?> sin leer</span>
            </div>
            <?php if ($notificaciones): ?>
                <form method="post" action="index.php?accion=gestionarNotificacion">
                    <input type="hidden" name="csrf" value="<?= $escNotificacion($_SESSION['notificaciones_csrf']) ?>">
                    <input type="hidden" name="operacion" value="eliminar_todas">
                    <button class="btn btn-outline-danger btn-sm" type="submit"
                        onclick="return confirm('¿Eliminar todas tus notificaciones?')">
                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                        <span>Eliminar todas</span>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($sinLeer > 0): ?>
            <form method="post" action="index.php?accion=gestionarNotificacion" class="notificaciones-marcar">
                <input type="hidden" name="csrf" value="<?= $escNotificacion($_SESSION['notificaciones_csrf']) ?>">
                <input type="hidden" name="operacion" value="todas">
                <button class="btn btn-link btn-sm" type="submit">
                    <i class="fa-solid fa-check-double" aria-hidden="true"></i> Marcar todas como leídas
                </button>
            </form>
        <?php endif; ?>

        <?php if (!$notificaciones): ?>
            <div class="notificaciones-vacias">
                <i class="fa-regular fa-bell-slash" aria-hidden="true"></i>
                <p>No hay notificaciones</p>
            </div>
        <?php else: ?>
            <div class="notificaciones-lista">
                <?php foreach ($notificaciones as $notificacion): ?>
                    <div class="notificacion-item <?= empty($notificacion['leida']) ? 'no-leida' : '' ?>">
                        <form method="post" action="index.php?accion=gestionarNotificacion" class="notificacion-abrir">
                            <input type="hidden" name="csrf" value="<?= $escNotificacion($_SESSION['notificaciones_csrf']) ?>">
                            <input type="hidden" name="operacion" value="abrir">
                            <input type="hidden" name="id" value="<?= (int) $notificacion['id'] ?>">
                            <button type="submit" class="notificacion-mensaje">
                                <span><?= $escNotificacion($notificacion['mensaje']) ?></span>
                                <small><?= $escNotificacion($notificacion['creada_en']) ?></small>
                            </button>
                        </form>
                        <form method="post" action="index.php?accion=gestionarNotificacion" class="notificacion-eliminar">
                            <input type="hidden" name="csrf" value="<?= $escNotificacion($_SESSION['notificaciones_csrf']) ?>">
                            <input type="hidden" name="operacion" value="eliminar">
                            <input type="hidden" name="id" value="<?= (int) $notificacion['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar notificación"
                                aria-label="Eliminar notificación: <?= $escNotificacion($notificacion['mensaje']) ?>">
                                <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</li>
