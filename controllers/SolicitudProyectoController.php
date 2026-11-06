<?php
function listarSolicitudesProyectos(): void {
    global $pdo;
    $porPagina = 15;
    $totalRegistros = SolicitudProyecto::contar($pdo);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));
    $paginaActual = max(1, min($totalPaginas, (int) ($_GET['pagina'] ?? 1)));
    $solicitudes = SolicitudProyecto::listarPaginado($pdo, $paginaActual, $porPagina);
    require __DIR__ . '/../views/solicitudesproyectos/versolicitudesproyectos.php';
}

function formSolicitudProyecto(): void {
    global $pdo;
    $proyecto = Proyecto::buscarPorId($pdo, (int) ($_GET['proyecto_id'] ?? 0));
    if (!$proyecto) {
        http_response_code(404);
        exit('El proyecto no existe.');
    }
    $tipo = $_GET['tipo'] ?? 'prestamo';
    if (!in_array($tipo, ['prestamo', 'reserva'], true)) {
        $tipo = 'prestamo';
    }
    $_SESSION['solicitud_csrf'] ??= bin2hex(random_bytes(32));
    require __DIR__ . '/../views/solicitudesproyectos/form.php';
}

function guardarSolicitudProyecto(): void {
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Método no permitido.');
    }
    $leer = static function (string $campo): string {
        return is_string($_POST[$campo] ?? null) ? trim($_POST[$campo]) : '';
    };
    $proyecto = Proyecto::buscarPorId($pdo, (int) $leer('proyecto_id'));
    if (!$proyecto) {
        http_response_code(404);
        exit('El proyecto no existe.');
    }
    $tipo = $leer('tipo');
    $modalidad = $leer('modalidad');
    $cedula = $leer('cedula');
    $nombre = $leer('nombre');
    $clase = $leer('clase');
    $telefono = $leer('telefono');
    $error = '';
    $socio = null;

    if (empty($_SESSION['solicitud_csrf']) || !hash_equals($_SESSION['solicitud_csrf'], $leer('csrf'))) {
        $error = 'El formulario venció. Volvé a enviarlo.';
    } elseif (!in_array($tipo, ['prestamo', 'reserva'], true) || !in_array($modalidad, ['existente', 'nuevo'], true)) {
        $error = 'Seleccioná un tipo de solicitud y una modalidad válidos.';
    } elseif ($cedula === '') {
        $error = 'Ingresá tu cédula.';
    } elseif (mb_strlen($cedula) > 20 || mb_strlen($nombre) > 150 || mb_strlen($clase) > 100 || mb_strlen($telefono) > 40) {
        $error = 'Uno de los datos supera la longitud permitida.';
    } elseif ($modalidad === 'nuevo' && ($nombre === '' || $clase === '' || $telefono === '')) {
        $error = 'Completá nombre, clase y teléfono para solicitar el registro.';
    }

    if ($error === '') {
        try {
            $socio = Socio::buscarPorCedula($pdo, $cedula);
            if ($modalidad === 'existente' && !$socio) {
                $error = 'No encontramos esa cédula. Revisala o elegí registrarte como socio.';
            } elseif ($modalidad === 'nuevo' && $socio) {
                $error = 'Esa cédula ya está registrada. Elegí la opción de socio existente.';
            } else {
                // Serializa los envíos del mismo proyecto para impedir duplicados simultáneos.
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('SELECT id FROM proyectos WHERE id = ? FOR UPDATE');
                $stmt->execute([$proyecto->getId()]);
                if (!$stmt->fetchColumn()) {
                    throw new RuntimeException('El proyecto ya no existe.');
                }
                if (SolicitudProyecto::tienePendiente($pdo, $proyecto->getId(), $cedula, $tipo)) {
                    $error = 'Ya tenés una solicitud pendiente de este tipo para este proyecto.';
                    $pdo->rollBack();
                } else {
                    SolicitudProyecto::crear($pdo, $proyecto->getId(), $socio ? $socio->getId() : null,
                        $modalidad === 'nuevo', $cedula, $modalidad === 'nuevo' ? $nombre : '',
                        $modalidad === 'nuevo' ? $clase : '', $telefono, $tipo);
                    $pdo->commit();
                    $_SESSION['solicitud_enviada'] = 'Tu solicitud fue enviada y está pendiente de revisión por la biblioteca.';
                    header('Location: index.php?accion=formSolicitudProyecto&proyecto_id=' . $proyecto->getId() . '&tipo=' . $tipo);
                    exit;
                }
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Error al guardar solicitud: ' . $e->getMessage());
            $error = 'No se pudo guardar la solicitud. Intentá nuevamente.';
        }
    }
    if (!in_array($tipo, ['prestamo', 'reserva'], true)) {
        $tipo = 'prestamo';
    }
    $_SESSION['solicitud_csrf'] ??= bin2hex(random_bytes(32));
    require __DIR__ . '/../views/solicitudesproyectos/form.php';
}
