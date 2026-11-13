<?php

function listarCatalogo() {
    global $pdo;

    $buscar = trim((string) ($_GET['buscar'] ?? ''));
    $campo = 'todos';
    $genero = (string) ($_GET['genero'] ?? '');
    if ($genero !== 'sin_genero' && $genero !== '' && !ctype_digit($genero)) $genero = '';
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
    if ($genero !== 'sin_genero' && $genero !== '' && !ctype_digit($genero)) $genero = '';
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
    global $pdo;

    $generos = Libro::listarGeneros($pdo);
    require __DIR__ . '/../views/libros/form.php';
}

function formEditarLibro() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $libro = Libro::buscarPorId($pdo, $id);

    if (!$libro) {
        die("El libro no existe.");
    }

    $generos = Libro::listarGeneros($pdo);
    require __DIR__ . '/../views/libros/form.php';
}

function obtenerGenerosNuevosEnviados(): array {
    $nuevosGeneros = $_POST['generos_nuevos'] ?? [];
    if (!is_array($nuevosGeneros)) {
        throw new InvalidArgumentException('La lista de géneros nuevos no es válida.');
    }
    foreach ($nuevosGeneros as $nombre) {
        if (!is_string($nombre)) {
            throw new InvalidArgumentException('El nombre de un género nuevo no es válido.');
        }
    }

    $nuevoGenero = $_POST['nuevo_genero'] ?? '';
    if (!is_string($nuevoGenero)) {
        throw new InvalidArgumentException('El nombre del género nuevo no es válido.');
    }
    if (trim($nuevoGenero) !== '') {
        $nuevosGeneros[] = $nuevoGenero;
    }

    return $nuevosGeneros;
}

function crearLibro() {
    global $pdo;

    $generos = Libro::listarGeneros($pdo);
    $titulo  = trim($_POST['titulo'] ?? '');
    $autor   = trim($_POST['autor'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $paginas = $_POST['paginas'] ?? '';
    $disponible = trim($_POST['disponible'] ?? '');
    $stock   = $_POST['stock'] ?? '';
    $codigoDewey = $_POST['codigo_dewey'] ?? '';

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '' || $stock === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/libros/form.php';
        return;
    }
    if (!is_string($codigoDewey)) {
        $error = 'El código Dewey no es válido.';
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    if (!in_array($disponible, ['Sí', 'No'], true)) {
        $error = 'Seleccioná una disponibilidad válida: Sí o No.';
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    try {
        $generoIds = Libro::normalizarGeneroIds($pdo, $_POST['generos'] ?? []);
        $nuevosGeneros = Libro::normalizarGenerosNuevos(obtenerGenerosNuevosEnviados());
        $codigoDewey = Libro::normalizarCodigoDewey($codigoDewey);
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    $portada = subirPortada();

    try {
        Libro::crear($pdo, $titulo, $autor, (int) $anio, (int) $paginas, $generoIds, $disponible, (int) $stock, $portada, $nuevosGeneros, $codigoDewey);
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    header('Location: index.php?accion=listarLibros');
    exit;
}

function editarLibro() {
    global $pdo;

    $generos = Libro::listarGeneros($pdo);
    $id      = (int) ($_POST['id'] ?? 0);
    $disponible = trim($_POST['disponible'] ?? '');
    $titulo  = trim($_POST['titulo'] ?? '');
    $autor   = trim($_POST['autor'] ?? '');
    $anio    = $_POST['anio'] ?? '';
    $paginas = $_POST['paginas'] ?? '';
    $stock   = $_POST['stock'] ?? '';
    $codigoDewey = $_POST['codigo_dewey'] ?? '';

    if ($titulo === '' || $autor === '' || $anio === '' || $paginas === '' || $stock === '') {
        $libro = Libro::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/libros/form.php';
        return;
    }
    if (!is_string($codigoDewey)) {
        $libro = Libro::buscarPorId($pdo, $id);
        $error = 'El código Dewey no es válido.';
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
    try {
        $generoIds = Libro::normalizarGeneroIds($pdo, $_POST['generos'] ?? []);
        $nuevosGeneros = Libro::normalizarGenerosNuevos(obtenerGenerosNuevosEnviados());
        $codigoDewey = Libro::normalizarCodigoDewey($codigoDewey);
    } catch (InvalidArgumentException $e) {
        $libro = $libroActual;
        $error = $e->getMessage();
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

    $portadaNueva = subirPortada();
    if ($portadaNueva === null) {
        $portadaNueva = $libroActual->getPortada();
    }

    try {
        Libro::actualizar($pdo, $id, $titulo, $autor, (int) $anio, (int) $paginas, $generoIds, $disponible, (int) $stock, $portadaNueva, $nuevosGeneros, $codigoDewey);
    } catch (InvalidArgumentException $e) {
        $libro = $libroActual;
        $error = $e->getMessage();
        require __DIR__ . '/../views/libros/form.php';
        return;
    }

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
