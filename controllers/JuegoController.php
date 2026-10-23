<?php

function listarJuegos() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Juego::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $juegos = Juego::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/juegos/verjuegos.php';
}

function listarJuegosP() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Juego::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $juegos = Juego::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/juegos/catalogojuegos.php';
}

function formCrearJuego() {
    require __DIR__ . '/../views/juegos/form.php';
}

function formEditarJuego() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $juego = Juego::buscarPorId($pdo, $id);

    if (!$juego) {
        die("El juego no existe.");
    }

    require __DIR__ . '/../views/juegos/form.php';
}

function crearJuego() {
    global $pdo;

    $nombre     = trim($_POST['nombre'] ?? '');
    $editor      = trim($_POST['editor'] ?? '');
    $disponible = $_POST['disponible'] ?? '';

    if ($nombre === '' || $editor === '' || $disponible === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/juegos/form.php';
        return;
    }

    if (!in_array((string) $disponible, ['0', '1'], true)) {
        $error = "La disponibilidad debe ser 0 o 1.";
        require __DIR__ . '/../views/juegos/form.php';
        return;
    }

    $foto = subirFotoJuego();

    Juego::crear($pdo, $nombre, $editor, (int) $disponible, $foto);

    header('Location: index.php?accion=listarJuegos');
    exit;
}

function editarJuego() {
    global $pdo;

    $id         = (int) ($_POST['id'] ?? 0);
    $nombre     = trim($_POST['nombre'] ?? '');
    $editor     = trim($_POST['editor'] ?? '');
    $disponible = $_POST['disponible'] ?? '';

    if ($nombre === '' || $editor === '' || $disponible === '') {
        $juego = Juego::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/juegos/form.php';
        return;
    }

    if (!in_array((string) $disponible, ['0', '1'], true)) {
        $juego = Juego::buscarPorId($pdo, $id);
        $error = "La disponibilidad debe ser 0 o 1.";
        require __DIR__ . '/../views/juegos/form.php';
        return;
    }

    $fotoNueva = subirFotoJuego();
    if ($fotoNueva === null) {
        $juegoActual = Juego::buscarPorId($pdo, $id);
        $fotoNueva = $juegoActual ? $juegoActual->getFoto() : null;
    }

    Juego::actualizar($pdo, $id, $nombre, $editor, (int) $disponible, $fotoNueva);

    header('Location: index.php?accion=listarJuegos');
    exit;
}

function eliminarJuego() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

   
    $juego = Juego::buscarPorId($pdo, $id);
    $rutaFoto = __DIR__ . '/../uploads/juegos/';

    Juego::eliminar($pdo, $id);

    if ($juego && $juego->getFoto() && file_exists($rutaFoto . $juego->getFoto())) {
        unlink($rutaFoto . $juego->getFoto());
    }

    header('Location: index.php?accion=listarJuegos');
    exit;
}


function subirFotoJuego(): ?string {
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        return null;
    }

    $nombreArchivo = uniqid('foto_', true) . '.' . $extension;
    $destino = __DIR__ . '/../uploads/juegos/' . $nombreArchivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        return $nombreArchivo;
    }

    return null;
}
