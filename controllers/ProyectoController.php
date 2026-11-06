<?php

function listarProyectos() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Proyecto::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $proyectos = Proyecto::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/proyectos/verproyecto.php';
}

function listarProyectosP() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Proyecto::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $proyectos = Proyecto::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/proyectos/catalogoproyecto.php';
}

function formCrearProyecto() {
    require __DIR__ . '/../views/proyectos/form.php';
}

function formEditarProyecto() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $proyecto = Proyecto::buscarPorId($pdo, $id);

    if (!$proyecto) {
        die("El proyecto no existe.");
    }

    require __DIR__ . '/../views/proyectos/form.php';
}

function crearProyecto() {
    global $pdo;

    $nombre  = trim($_POST['nombre'] ?? '');
    $integrantes   = trim($_POST['integrantes'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $clase = $_POST['clase'] ?? '';
    $curso  = trim($_POST['curso'] ?? '');

    if ($nombre === '' || $integrantes === '' || $anio === '' || $clase === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/proyectos/form.php';
        return;
    }

    $caratula = subirCaratula();

    Proyecto::crear($pdo, $nombre, $integrantes, (int) $anio, (string) $clase, $curso, $caratula);

    header('Location: index.php?accion=listarProyectos');
    exit;
}



function editarProyecto() {
    global $pdo;

    $id      = (int) ($_POST['id'] ?? 0);
    $nombre  = trim($_POST['nombre'] ?? '');
    $integrantes   = trim($_POST['integrantes'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $clase = $_POST['clase'] ?? '';
    $curso  = trim($_POST['curso'] ?? '');

    if ($nombre === '' || $integrantes === '' || $anio === '' || $clase === '' || $curso === '') {
        $proyecto = Proyecto::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/proyectos/form.php';
        return;
    }

    $portadaNueva = subirCaratula();
    if ($portadaNueva === null) {
        $proyectoActual = Proyecto::buscarPorId($pdo, $id);
        $portadaNueva = $proyectoActual ? $proyectoActual->getPortada() : null;
    }

    Proyecto::actualizar($pdo, $id, $nombre, $integrantes, (int) $anio, (string) $clase, $curso, $portadaNueva);

    header('Location: index.php?accion=listarProyectos');
    exit;
}

function eliminarProyecto() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

   
    $proyecto = Proyecto::buscarPorId($pdo, $id);
    $rutaPortada = __DIR__ . '/../uploads/portadas/';

    Proyecto::eliminar($pdo, $id);

    if ($proyecto && $proyecto->getPortada() && file_exists($rutaPortada . $proyecto->getPortada())) {
        unlink($rutaPortada . $proyecto->getPortada());
    }

    header('Location: index.php?accion=listarProyectos');
    exit;
}


function subirCaratula(): ?string {
    if (!isset($_FILES['caratula']) || $_FILES['caratula']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($_FILES['caratula']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        return null;
    }

    $nombreArchivo = uniqid('portada_', true) . '.' . $extension;
    $destino = __DIR__ . '/../uploads/portadas/' . $nombreArchivo;

    if (move_uploaded_file($_FILES['caratula']['tmp_name'], $destino)) {
        return $nombreArchivo;
    }

    return null;
}
