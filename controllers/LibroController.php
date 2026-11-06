<?php

function listarCatalogo() {
    global $pdo;

    $buscar = trim((string) ($_GET['buscar'] ?? ''));
    $campo = 'todos';
    $genero = (string) ($_GET['genero'] ?? '');
    $generos = Libro::listarGeneros($pdo);
    $disponibilidad = (string) ($_GET['disponibilidad'] ?? '');
    if (!in_array($disponibilidad, ['', '1', '0'], true)) $disponibilidad = '';

    $paginaActual = max(1, (int) ($_GET['pagina'] ?? 1));
    $porPagina = 20;
    $totalRegistros = Libro::contarFiltrados($pdo, $buscar, $campo, $disponibilidad, $genero);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));
    $paginaActual = min($paginaActual, $totalPaginas);
    $libros = Libro::buscarPaginado($pdo, $paginaActual, $porPagina, $buscar, $campo, $disponibilidad, $genero);

    require __DIR__ . '/../views/libros/catalogo.php';
}

function listarLibros() {
    global $pdo;

    $buscar = trim((string) ($_GET['buscar'] ?? ''));
    $campo = 'todos';
    $genero = (string) ($_GET['genero'] ?? '');
    $generos = Libro::listarGeneros($pdo);
    $disponibilidad = (string) ($_GET['disponibilidad'] ?? '');
    if (!in_array($disponibilidad, ['', '1', '0'], true)) $disponibilidad = '';

    $paginaActual = max(1, (int) ($_GET['pagina'] ?? 1));
    $porPagina = 20;
    $totalRegistros = Libro::contarFiltrados($pdo, $buscar, $campo, $disponibilidad, $genero);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));
    $paginaActual = min($paginaActual, $totalPaginas);
    $libros = Libro::buscarPaginado($pdo, $paginaActual, $porPagina, $buscar, $campo, $disponibilidad, $genero);

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

function crearLibro() {
    global $pdo;

    $titulo  = trim($_POST['titulo'] ?? '');
    $autor   = trim($_POST['autor'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $paginas = $_POST['paginas'] ?? '';
    $genero  = trim($_POST['genero'] ?? '');
    $disponible = trim($_POST['disponible'] ?? '');
    $stock   = $_POST['stock'] ?? '';

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '' || $stock === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    if (!in_array($disponible, ['Sí', 'No'], true)) {
        $error = 'Seleccioná una disponibilidad válida: Sí o No.';
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    $portada = subirPortada();

    Libro::crear($pdo, $titulo, $autor, (int) $anio, (int) $paginas, $genero, $disponible, (int) $stock, $portada);

    header('Location: index.php?accion=listarLibros');
    exit;
}

function editarLibro() {
    global $pdo;

    $id      = (int) ($_POST['id'] ?? 0);
    $disponible = trim($_POST['disponible'] ?? '');
    $titulo  = trim($_POST['titulo'] ?? '');
    $autor   = trim($_POST['autor'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $paginas = $_POST['paginas'] ?? '';
    $genero  = trim($_POST['genero'] ?? '');
    $stock   = $_POST['stock'] ?? '';

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '' || $stock === '') {
        $libro = Libro::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    $libroActual = Libro::buscarPorId($pdo, $id);
    if (!$libroActual) {
        die("El libro no existe.");
    }
    if (!in_array($disponible, ['Sí', 'No'], true)) {
        $libro = $libroActual;
        $error = 'Seleccioná una disponibilidad válida: Sí o No.';
        require __DIR__ . '/../views/libros/form.php';
        return;
    }
    $portadaNueva = subirPortada();
    if ($portadaNueva === null) {
        $portadaNueva = $libroActual->getPortada();
    }

    Libro::actualizar($pdo, $id, $titulo, $autor, (int) $anio, (int) $paginas, $genero, $disponible, (int) $stock, $portadaNueva);

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
