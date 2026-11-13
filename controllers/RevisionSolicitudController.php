<?php
function revisarSolicitud(): void
{
    global $pdo;
    if (empty($_SESSION['usuario_id'])) {
        http_response_code(403);
        exit('Debes iniciar sesion.');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Metodo no permitido.');
    }
    $leer = static fn(string $campo): string => is_string($_POST[$campo] ?? null) ? $_POST[$campo] : '';
    if (empty($_SESSION['revision_solicitud_csrf']) || !hash_equals($_SESSION['revision_solicitud_csrf'], $leer('csrf'))) {
        http_response_code(403);
        exit('El formulario vencio. Recarga el listado.');
    }
    $categorias = [
        'listarSolicitudes' => Solicitud::class,
        'listarSolicitudesJuegos' => SolicitudJuego::class,
        'listarSolicitudesProyectos' => SolicitudProyecto::class,
    ];
    $listado = $leer('listado');
    $estado = $leer('estado');
    $id = filter_var($leer('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!isset($categorias[$listado]) || !$id || !in_array($estado, ['aceptada', 'rechazada'], true)) {
        http_response_code(400);
        exit('Solicitud no valida.');
    }
    try {
        $actualizada = $categorias[$listado]::revisar($pdo, $id, $estado, (int) $_SESSION['usuario_id']);
        $_SESSION['revision_solicitud_mensaje'] = $actualizada
            ? ($estado === 'aceptada' ? 'Solicitud aceptada.' : 'Solicitud rechazada.')
            : 'La solicitud ya fue revisada o no existe.';
    } catch (Throwable $e) {
        error_log('Error al revisar solicitud: ' . $e->getMessage());
        $_SESSION['revision_solicitud_mensaje'] = 'No se pudo guardar el cambio. Intenta nuevamente.';
    }
    $pagina = max(1, (int) $leer('pagina'));
    header('Location: index.php?accion=' . $listado . '&pagina=' . $pagina, true, 303);
    exit;
}

function eliminarSolicitudAceptada(): void
{
    global $pdo;
    if (empty($_SESSION['usuario_id'])) {
        http_response_code(403);
        exit('Debes iniciar sesion.');
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Metodo no permitido.');
    }

    $leer = static fn(string $campo): string => is_string($_POST[$campo] ?? null) ? trim($_POST[$campo]) : '';
    if (empty($_SESSION['revision_solicitud_csrf']) || !hash_equals($_SESSION['revision_solicitud_csrf'], $leer('csrf'))) {
        http_response_code(403);
        exit('El formulario vencio. Recarga el listado.');
    }

    $categorias = [
        'listarSolicitudes' => Solicitud::class,
        'listarSolicitudesJuegos' => SolicitudJuego::class,
        'listarSolicitudesProyectos' => SolicitudProyecto::class,
    ];
    $listado = $leer('listado');
    $id = filter_var($leer('id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!isset($categorias[$listado]) || !$id) {
        http_response_code(400);
        exit('Solicitud no valida.');
    }

    try {
        $eliminada = $categorias[$listado]::eliminarAceptada($pdo, $id);
        $_SESSION['revision_solicitud_mensaje'] = $eliminada
            ? 'Solicitud aceptada eliminada.'
            : 'La solicitud no está aceptada o ya no existe.';
    } catch (Throwable $e) {
        error_log('Error al eliminar solicitud aceptada: ' . $e->getMessage());
        $_SESSION['revision_solicitud_mensaje'] = 'No se pudo eliminar la solicitud. Intenta nuevamente.';
    }

    $pagina = max(1, (int) $leer('pagina'));
    header('Location: index.php?accion=' . $listado . '&pagina=' . $pagina, true, 303);
    exit;
}
