<?php

function listarPrestamos() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Prestamo::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $prestamos = Prestamo::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/prestamos/verprestamo.php';
}

function formCrearPrestamo() {
    global $pdo;
    $socios = Socio::listarPaginado($pdo, 1, 1000); 
    $libros = Libro::listarPaginado($pdo, 1, 1000); 
    require __DIR__ . '/../views/prestamos/form.php';
}

function formEditarPrestamo() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $prestamo = Prestamo::buscarPorId($pdo, $id);

    if (!$prestamo) {
        die("El préstamo no existe.");
    }

    $socios = Socio::listarPaginado($pdo, 1, 1000);
    $libros = Libro::listarPaginado($pdo, 1, 1000);

    require __DIR__ . '/../views/prestamos/form.php';
}

function crearPrestamo() {
    global $pdo;

    $socioId       = (int) ($_POST['socioId'] ?? 0);
    $libroId       = (int) ($_POST['libroId'] ?? 0);
    $fechaPrestamo = trim($_POST['fechaPrestamo'] ?? '');

    if ($socioId === 0 || $libroId === 0 || $fechaPrestamo === '') {
        $error = "Todos los campos son obligatorios.";
        $socios = Socio::listarPaginado($pdo, 1, 1000);
        $libros = Libro::listarPaginado($pdo, 1, 1000);
        require __DIR__ . '/../views/prestamos/form.php';
        return;
    }

    Prestamo::crear($pdo, $socioId, $libroId, $fechaPrestamo);

    header('Location: index.php?accion=listarPrestamos');
    exit;
}

function editarPrestamo() {
    global $pdo;

    $id            = (int) ($_POST['id'] ?? 0);
    $socioId       = (int) ($_POST['socioId'] ?? 0);
    $libroId       = (int) ($_POST['libroId'] ?? 0);
    $fechaPrestamo = trim($_POST['fechaPrestamo'] ?? '');

    if ($id === 0 || $socioId === 0 || $libroId === 0 || $fechaPrestamo === '') {
        $prestamo = Prestamo::buscarPorId($pdo, $id);
        $socios = Socio::listarPaginado($pdo, 1, 1000);
        $libros = Libro::listarPaginado($pdo, 1, 1000);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/prestamos/form.php';
        return;
    }

    Prestamo::actualizar($pdo, $id, $socioId, $libroId, $fechaPrestamo);

    header('Location: index.php?accion=listarPrestamos');
    exit;
}

function eliminarPrestamo() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    Prestamo::eliminar($pdo, $id);

    header('Location: index.php?accion=listarPrestamos');
    exit;
}