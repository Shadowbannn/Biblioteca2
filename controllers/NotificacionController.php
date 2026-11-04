<?php
function gestionarNotificacion(): void
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
    $csrf = $_POST['csrf'] ?? null;
    if (!is_string($csrf) || empty($_SESSION['notificaciones_csrf']) || !hash_equals($_SESSION['notificaciones_csrf'], $csrf)) {
        http_response_code(403);
        exit('El formulario vencio. Recarga la pagina.');
    }
    $destino = 'index.php';
    if (($_POST['operacion'] ?? '') === 'todas') {
        Notificacion::marcarTodasLeidas($pdo, (int) $_SESSION['usuario_id']);
    } elseif (($_POST['operacion'] ?? '') === 'abrir') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $destino = $id ? Notificacion::abrir($pdo, $id, (int) $_SESSION['usuario_id']) : null;
        if ($destino === null) {
            http_response_code(404);
            exit('La notificacion no existe.');
        }
    } else {
        http_response_code(400);
        exit('Operacion no valida.');
    }
    header('Location: ' . $destino, true, 303);
    exit;
}
