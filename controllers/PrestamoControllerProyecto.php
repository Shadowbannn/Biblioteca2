<?php

function listarPrestamosProyectos() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 10;
    $totalRegistros = PrestamoProyecto::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $prestamos = PrestamoProyecto::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/prestamos/verprestamoProyecto.php';
}

function formCrearPrestamoProyectos() {
    global $pdo;
    $socios = Socio::listarPaginado($pdo, 1, 1000); 
    $proyectos = Proyecto::listarPaginado($pdo, 1, 15000); 
    require __DIR__ . '/../views/prestamos/formProyecto.php';
}

function formEditarPrestamoProyectos() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $prestamo = PrestamoProyecto::buscarPorId($pdo, $id);

    if (!$prestamo) {
        die("El préstamo no existe.");
    }

    $socios = Socio::listarPaginado($pdo, 1, 1000);
    $proyectos = Proyecto::listarPaginado($pdo, 1, 15000);

    require __DIR__ . '/../views/prestamos/formProyecto.php';
}

function crearPrestamoProyectos() {
    global $pdo;

    $socioId       = (int) ($_POST['socioId'] ?? 0);
    $proyectoId    = (int) ($_POST['proyectoId'] ?? 0);
    $estado        = (string) ($_POST['estado'] ?? 0);
    $fechaPrestamo = trim($_POST['fechaPrestamoProyecto'] ?? '');
    $fechaDevolucion = trim($_POST['fechaDevolucion'] ?? '');

    if ($socioId === 0 || $proyectoId === 0 || $estado === 0 || $fechaPrestamo === '' || $fechaPrestamo === '') {
        $error = "Todos los campos son obligatorios.";
        $socios = Socio::listarPaginado($pdo, 1, 1000);
        $proyectos = Proyecto::listarPaginado($pdo, 1, 15000);
        require __DIR__ . '/../views/prestamos/formProyecto.php';
        return;
    }

    PrestamoProyecto::crear($pdo, $socioId, $proyectoId, $estado, $fechaPrestamo, $fechaDevolucion);

    header('Location: index.php?accion=listarPrestamosProyectos');
    exit;
}

function editarPrestamoProyectos() {
    global $pdo;

    $id              = (int) ($_POST['id'] ?? 0);
    $socioId         = (int) ($_POST['socioId'] ?? 0);
    $proyectoId      = (int) ($_POST['proyectoId'] ?? 0);
    $estado          = (string) ($_POST['estado'] ?? 0);
    $fechaPrestamo   = trim($_POST['fechaPrestamoProyecto'] ?? '');
    $fechaDevolucion = trim($_POST['fechaDevolucion'] ?? '');

    if ($id === 0 || $socioId === 0 || $proyectoId === 0 || $estado === 0 || $fechaPrestamo === '' || $fechaDevolucion === '') {
        $prestamo = PrestamoProyecto::buscarPorId($pdo, $id);
        $socios = Socio::listarPaginado($pdo, 1, 1000);
        $proyectos = Proyecto::listarPaginado($pdo, 1, 15000);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/prestamos/formProyecto.php';
        return;
    }

    PrestamoProyecto::actualizar($pdo, $id, $socioId, $proyectoId, $estado, $fechaPrestamo, $fechaDevolucion);

    header('Location: index.php?accion=listarPrestamosProyectos');
    exit;
}

function eliminarPrestamoProyectos() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    PrestamoProyecto::eliminar($pdo, $id);

    header('Location: index.php?accion=listarPrestamosProyectos');
    exit;
}
