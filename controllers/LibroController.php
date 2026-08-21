<?php

function listarLibros() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 20;
    $totalRegistros = Libro::contarTotal($pdo);
    $totalPaginas = (int) ceil($totalRegistros / $porPagina);

    if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
        $paginaActual = $totalPaginas;
    }

    $libros = Libro::listarPaginado($pdo, $paginaActual, $porPagina);

    require __DIR__ . '/../views/libros/verlibro.php';
}

function formCrearLibro() {
    require __DIR__ . '/../views/libros/form.php';
}

function formEditarLibro() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $libro = Libro::buscarPorId($pdo, $id);

    if (!$libro) {
        die("El libro no existe.");
    }

    require __DIR__ . '/../views/libros/form.php';
}

/** Antes: todo guardarlibro.php. */
function crearLibro() {
    global $pdo;

    $titulo  = trim($_POST['titulo'] ?? '');
    $autor   = trim($_POST['autor'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $paginas = $_POST['paginas'] ?? '';
    $genero  = trim($_POST['genero'] ?? '');

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    $portada = subirPortada();

    Libro::crear($pdo, $titulo, $autor, (int) $anio, (int) $paginas, $genero, $portada);

    header('Location: index.php?accion=listarLibros');
    exit;
}

function editarLibro() {
    global $pdo;

    $id      = (int) ($_POST['id'] ?? 0);
    $titulo  = trim($_POST['titulo'] ?? '');
    $autor   = trim($_POST['autor'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $paginas = $_POST['paginas'] ?? '';
    $genero  = trim($_POST['genero'] ?? '');

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '') {
        $libro = Libro::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    $portadaNueva = subirPortada();
    if ($portadaNueva === null) {
        $libroActual = Libro::buscarPorId($pdo, $id);
        $portadaNueva = $libroActual ? $libroActual->getPortada() : null;
    }

    Libro::actualizar($pdo, $id, $titulo, $autor, (int) $anio, (int) $paginas, $genero, $portadaNueva);

    header('Location: index.php?accion=listarLibros');
    exit;
}

function eliminarLibro() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

   
    $libro = Libro::buscarPorId($pdo, $id);
    $rutaPortada = __DIR__ . '/../uploads/portadas/';

    Libro::eliminar($pdo, $id);

    if ($libro && $libro->getPortada() && file_exists($rutaPortada . $libro->getPortada())) {
        unlink($rutaPortada . $libro->getPortada());
    }

    header('Location: index.php?accion=listarLibros');
    exit;
}


function subirPortada(): ?string {
    if (!isset($_FILES['portada']) || $_FILES['portada']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        return null;
    }

    $nombreArchivo = uniqid('portada_', true) . '.' . $extension;
    $destino = __DIR__ . '/../uploads/portadas/' . $nombreArchivo;

    if (move_uploaded_file($_FILES['portada']['tmp_name'], $destino)) {
        return $nombreArchivo;
    }

    return null;
}
