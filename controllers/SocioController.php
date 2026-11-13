<?php

function listarSocios() {
    global $pdo;

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) {
        $paginaActual = 1;
    }

    $porPagina = 10;
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
    $password = (string) ($_POST['password'] ?? '');

    if ($nombre === '' || $clase === '' || $cedula === '' || $telefono === '' || $fecha_registro === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }

    try {
        $foto = subirFoto();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }

    $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
    Socio::crear($pdo, $nombre, $clase, $cedula, (string) $telefono, (string) $fecha_registro, $foto, $passwordHash);

    header('Location: index.php?accion=listarSocios');
    exit;
}

function editarSocio() {
    global $pdo;

    $id             = (int) ($_POST['id'] ?? 0);
    $nombre         = trim($_POST['nombre'] ?? '');
    $clase          = trim($_POST['clase'] ?? '');
    $cedula         = trim($_POST['cedula'] ?? '');
    $telefono       = (string) trim($_POST['telefono'] ?? '');
    $fecha_registro = $_POST['fecha_registro'] ?? '';
    $password = (string) ($_POST['password'] ?? '');

    if ($nombre === '' || $clase === '' || $cedula === '' || $telefono === '' || $fecha_registro === '') {
        $socio = Socio::buscarPorId($pdo, $id);
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }

    try {
        $fotoNueva = subirFoto();
    } catch (InvalidArgumentException $e) {
        $socio = Socio::buscarPorId($pdo, $id);
        $error = $e->getMessage();
        require __DIR__ . '/../views/Socios/form.php';
        return;
    }
    if ($fotoNueva === null) {
        $socioActual = Socio::buscarPorId($pdo, $id);
        $fotoNueva = $socioActual ? $socioActual->getFoto() : null;
    }

    // CORREGIDO: Se llama a Socio::actualizar (antes decía Socio::crear)
    $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
    Socio::actualizar($pdo, $id, $nombre, $clase, $cedula, (string) $telefono, (string) $fecha_registro, $fotoNueva, $passwordHash);

    // CORREGIDO: Redirección a listarSocios (en plural)
    header('Location: index.php?accion=listarSocios');
    exit;
}

function eliminarSocio() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    if (Socio::tienePrestamoActivo($pdo, $id)) {
        header('Location: index.php?accion=listarSocios&error=prestamo_activo');
        exit;
    }

    $socio = Socio::buscarPorId($pdo, $id);
    $rutaFoto = __DIR__ . '/../uploads/fotos/';

    try {
        Socio::eliminar($pdo, $id);
    } catch (PDOException $e) {
        if ($e->getCode() !== '23000') {
            throw $e;
        }

        header('Location: index.php?accion=listarSocios&error=prestamos_asociados');
        exit;
    }

   if ($socio && $socio->getFoto() && file_exists($rutaFoto . $socio->getFoto())) {
        unlink($rutaFoto . $socio->getFoto());
    }

    header('Location: index.php?accion=listarSocios');
    exit;
}



function subirFoto(): ?string {
    if (!isset($_FILES['foto']) || !is_array($_FILES['foto']) || ($_FILES['foto']['error'] ?? null) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($_FILES['foto']['error'] ?? null) !== UPLOAD_ERR_OK) {
        if (in_array($_FILES['foto']['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
            throw new InvalidArgumentException('La foto debe pesar como máximo 5 MB.');
        }
        return null;
    }

    $temporal = $_FILES['foto']['tmp_name'] ?? '';
    $tamano = is_string($temporal) && is_uploaded_file($temporal) ? filesize($temporal) : false;
    if ($tamano === false) {
        return null;
    }
    if ($tamano > 5 * 1024 * 1024) {
        throw new InvalidArgumentException('La foto debe pesar como máximo 5 MB.');
    }

    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'jfif', 'webp', 'gif'];
    $extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        return null;
    }

    $nombreArchivo = uniqid('foto_', true) . '.' . $extension;
    $destino = __DIR__ . '/../uploads/fotos/' . $nombreArchivo;

    if (move_uploaded_file($temporal, $destino)) {
        return $nombreArchivo;
    }

    return null;
}
