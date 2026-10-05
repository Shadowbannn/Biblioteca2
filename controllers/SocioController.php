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
    // CORREGIDO: Se cambia $socios por $socio (en singular)
    $socio = Socio::buscarPorId($pdo, $id);

    if (!$socio) {
        die("El socio no existe.");
    }

    require __DIR__ . '/../views/Socios/form.php';
}

function crearSocio() {
    global $pdo;

    $nombre         = trim($_POST['nombre'] ?? '');
    $clase       = trim($_POST['clase'] ?? '');
    $cedula         = trim($_POST['cedula'] ?? '');
    $telefono       = trim($_POST['telefono'] ?? '');
    $fecha_registro = $_POST['fecha_registro'] ?? '';

    if ($nombre === '' || $clase === '' || $cedula === '' || $telefono === '' || $fecha_registro === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }

    $foto = subirFoto();

    Socio::crear($pdo, $nombre, $clase, $cedula, (string) $telefono, (string) $fecha_registro, $foto);

    header('Location: index.php?accion=listarSocios');
    exit;
}

function editarSocio() {
    global $pdo;

    $id             = (int) ($_POST['id'] ?? 0);
    $nombre         = trim($_POST['nombre'] ?? '');
    $clase       = trim($_POST['clase'] ?? '');
    $cedula         = trim($_POST['cedula'] ?? '');
    $telefono       = (string) trim($_POST['telefono'] ?? '');
    $fecha_registro = $_POST['fecha_registro'] ?? '';

    if ($nombre === '' || $clase === '' || $cedula === '' || $telefono === '' || $fecha_registro === '') {
        $socio = Socio::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }

    $fotoNueva = subirFoto();
    if ($fotoNueva === null) {
        $socioActual = Socio::buscarPorId($pdo, $id);
        $fotoNueva = $socioActual ? $socioActual->getFoto() : null;
    }

    // CORREGIDO: Se llama a Socio::actualizar (antes decía Socio::crear)
    Socio::actualizar($pdo, $id, $nombre, $clase, $cedula, (string) $telefono, (string) $fecha_registro, $fotoNueva);

    // CORREGIDO: Redirección a listarSocios (en plural)
    header('Location: index.php?accion=listarSocios');
    exit;
}

function eliminarSocio() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    Socio::eliminar($pdo, $id);

    header('Location: index.php?accion=listarSocios');
    exit;
}

function subirFoto(): ?string {
    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'jfif', 'webp', 'gif'];
    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        return null;
    }

    $nombreArchivo = uniqid('foto_', true) . '.' . $extension;
    $destino = __DIR__ . '/../uploads/fotos/' . $nombreArchivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
        return $nombreArchivo;
    }

    return null;
}
