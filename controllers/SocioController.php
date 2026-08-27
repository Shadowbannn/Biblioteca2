<?php

function listarSocios() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Socio::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $socios = Socio::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/Socios/versocio.php';
}

function formCrearSocio() {
    require __DIR__ . '/../views/Socios/form.php';
}

function formEditarSocio() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $socios = Socio::buscarPorId($pdo, $id);

    if (!$socio) {
        die("El socio no existe.");
    }

    require __DIR__ . '/../views/Socios/form.php';
}

/** Antes: todo guardarlibro.php. */
function crearSocio() {
    global $pdo;

    $nombre         = trim($_POST['nombre'] ?? '');
    $cedula         = trim($_POST['cedula'] ?? '');
    $telefono       = $_POST['telefono'] ?? '';
    $fecha_registro = $_POST['fecha_registro'] ?? '';
    

    if ($nombre === '' || $cedula === '' || $telefono === '' || $fecha_registro === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }


    Socio::crear($pdo, $nombre, $apellido, $cedula, (string) $telefono, (int) $fecha_registro);

    header('Location: index.php?accion=listarSocios');
    exit;
}

function editarSocio() {
    global $pdo;

    $id             = (int) ($_POST['id'] ?? 0);
    $nombre         = trim($_POST['nombre'] ?? '');
    $cedula         = trim($_POST['cedula'] ?? '');
    $telefono       = $_POST['telefono'] ?? '';
    $fecha_registro = $_POST['fecha_registro'] ?? '';
    

    if ($nombre === '' || $cedula === '' || $telefono === '' || $fecha_registro === '') {
        $socio = Socio::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }

 

    Socio::actualizar($pdo, $id, $nombre, $apellido, $cedula, (string) $telefono, (int) $fecha_registro);

    header('Location: index.php?accion=listarSocio');
    exit;
}

function eliminarSocio() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);


    header('Location: index.php?accion=listarSocios');
    exit;
}