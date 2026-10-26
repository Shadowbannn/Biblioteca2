<?php
function formSolicitudLibro(): void {
    global $pdo;
    $libro = Libro::buscarPorId($pdo, (int) ($_GET['libro_id'] ?? 0));
    if (!$libro) {
        http_response_code(404);
        exit('El libro no existe.');
    }
    $tipo = $_GET['tipo'] ?? 'prestamo';
    if (!in_array($tipo, ['prestamo', 'reserva'], true)) {
        $tipo = 'prestamo';
    }
    require __DIR__ . '/../views/solicitudes/form.php';
}