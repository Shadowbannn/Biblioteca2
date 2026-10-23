<?php

function listarPrestamosJuegos() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 10;
    $totalRegistros = PrestamoJuego::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $prestamos = PrestamoJuego::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/prestamos/verprestamoJuego.php';
}

function formCrearPrestamoJuegos() {
    global $pdo;
    $socios = Socio::listarPaginado($pdo, 1, 1000); 
    $juegos = Juego::listarPaginado($pdo, 1, 15000); 
    require __DIR__ . '/../views/prestamos/formjuego.php';
}

function formEditarPrestamoJuegos() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $prestamo = PrestamoJuego::buscarPorId($pdo, $id);

    if (!$prestamo) {
        die("El préstamo no existe.");
    }

    $socios = Socio::listarPaginado($pdo, 1, 1000);
    $juegos = Juego::listarPaginado($pdo, 1, 15000);

    require __DIR__ . '/../views/prestamos/formJuego.php';
}

function crearPrestamoJuegos() {
    global $pdo;

    $socioId       = (int) ($_POST['socioId'] ?? 0);
    $juegoId       = (int) ($_POST['juegoId'] ?? 0);
    $estado        = (string) ($_POST['estado'] ?? 0);
    $fechaPrestamo = trim($_POST['fechaPrestamoJuego'] ?? '');
    $fechaDevolucion = trim($_POST['fechaDevolucion'] ?? '');

    if ($socioId === 0 || $juegoId === 0 || $estado === 0 || $fechaPrestamo === '' || $fechaPrestamo === '') {
        $error = "Todos los campos son obligatorios.";
        $socios = Socio::listarPaginado($pdo, 1, 1000);
        $juegos = Juego::listarPaginado($pdo, 1, 15000);
        require __DIR__ . '/../views/prestamos/formJuego.php';
        return;
    }

    PrestamoJuego::crear($pdo, $socioId, $juegoId, $estado, $fechaPrestamo, $fechaDevolucion);

    header('Location: index.php?accion=listarPrestamosJuegos');
    exit;
}

function editarPrestamoJuegos() {
    global $pdo;

    $id              = (int) ($_POST['id'] ?? 0);
    $socioId         = (int) ($_POST['socioId'] ?? 0);
    $juegoId         = (int) ($_POST['juegoId'] ?? 0);
    $estado          = (string) ($_POST['estado'] ?? 0);
    $fechaPrestamo   = trim($_POST['fechaPrestamoJuego'] ?? '');
    $fechaDevolucion = trim($_POST['fechaDevolucion'] ?? '');

    if ($id === 0 || $socioId === 0 || $juegoId === 0 || $estado === 0 || $fechaPrestamo === '' || $fechaDevolucion === '') {
        $prestamo = PrestamoJuego::buscarPorId($pdo, $id);
        $socios = Socio::listarPaginado($pdo, 1, 1000);
        $juegos = Juego::listarPaginado($pdo, 1, 15000);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/prestamos/formJuego.php';
        return;
    }

    PrestamoJuego::actualizar($pdo, $id, $socioId, $juegoId, $estado, $fechaPrestamo, $fechaDevolucion);

    header('Location: index.php?accion=listarPrestamosJuegos');
    exit;
}

function eliminarPrestamoJuegos() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    PrestamoJuego::eliminar($pdo, $id);

    header('Location: index.php?accion=listarPrestamosJuegos');
    exit;
}
