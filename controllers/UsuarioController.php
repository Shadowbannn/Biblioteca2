<?php

// ===== LOGIN / LOGOUT =====

function formLogin() {
    if (!empty($_SESSION['usuario_id'])) {
        header('Location: index.php?accion=inicio');
        exit;
    }

    require __DIR__ . '/../views/usuarios/login.php';
}

function login() {
    global $pdo;

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $usuario = Usuario::validarCredenciales($pdo, $email, $password);

    if (!$usuario) {
        $error = "Usuario o contraseña incorrectos.";
        require __DIR__ . '/../views/usuarios/login.php';
        return;
    }

    session_regenerate_id(true);
    $_SESSION['usuario']    = $usuario->getNombre();
    $_SESSION['usuario_id'] = $usuario->getId();

    header('Location: index.php?accion=inicio');
    exit;
}

function logout() {
    if (!empty($_SESSION['socio_id']) && empty($_SESSION['usuario_id'])) {
        unset($_SESSION['socio_id'], $_SESSION['socio_nombre']);
        session_regenerate_id(true);
        header('Location: index.php?accion=formLoginSocio');
        exit;
    }
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();

    header('Location: index.php?accion=formLogin');
    exit;
}

// Acceso separado para socios. La contraseña se guarda con password_hash en socios.password.
function formLoginSocio() {
    if (!empty($_SESSION['socio_id'])) {
        header('Location: index.php?accion=misPrestamos');
        exit;
    }
    require __DIR__ . '/../views/usuarios/loginSocio.php';
}

function loginSocio() {
    global $pdo;
    $cedula = trim($_POST['cedula'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, nombre, password FROM socios WHERE cedula = ? LIMIT 1');
    $stmt->execute([$cedula]);
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);
    $credencialValida = $socio && ($socio['password']
        ? password_verify($password, $socio['password'])
        : hash_equals($cedula, $password));
    if (!$credencialValida) {
        $error = 'Cédula o contraseña incorrectas. La contraseña inicial es tu cédula.';
        require __DIR__ . '/../views/usuarios/loginSocio.php';
        return;
    }
    session_regenerate_id(true);
    unset($_SESSION['usuario_id'], $_SESSION['usuario']);
    $_SESSION['socio_id'] = (int) $socio['id'];
    $_SESSION['socio_nombre'] = $socio['nombre'];
    header('Location: index.php?accion=misPrestamos');
    exit;
}

function cambiarPasswordSocio() {
    global $pdo;
    $socioId = (int) $_SESSION['socio_id'];
    $actual = (string) ($_POST['password_actual'] ?? '');
    $nueva = (string) ($_POST['password_nueva'] ?? '');
    $confirmacion = (string) ($_POST['password_confirmacion'] ?? '');
    $stmt = $pdo->prepare('SELECT cedula, password FROM socios WHERE id = ?');
    $stmt->execute([$socioId]);
    $socio = $stmt->fetch(PDO::FETCH_ASSOC);
    $actualValida = $socio && ($socio['password']
        ? password_verify($actual, $socio['password'])
        : hash_equals((string) $socio['cedula'], $actual));
    if (!$actualValida || strlen($nueva) < 8 || $nueva !== $confirmacion) {
        $_SESSION['mensaje_password'] = !$actualValida
            ? 'La contraseña actual no coincide.'
            : ($nueva !== $confirmacion ? 'La confirmación no coincide.' : 'La nueva contraseña debe tener al menos 8 caracteres.');
        header('Location: index.php?accion=misPrestamos#cambiar-contrasena');
        exit;
    }
    $stmt = $pdo->prepare('UPDATE socios SET password = ? WHERE id = ?');
    $stmt->execute([password_hash($nueva, PASSWORD_DEFAULT), $socioId]);
    $_SESSION['mensaje_password'] = 'Contraseña actualizada.';
    header('Location: index.php?accion=misPrestamos#cambiar-contrasena');
    exit;
}

function misPrestamos() {
    global $pdo;
    $socioId = (int) $_SESSION['socio_id'];
    $prestamosPorTipo = [];
    $consultas = [
        'Libros' => "SELECT p.fechaPrestamo, p.fechaDevolucion, p.estado, COALESCE(l.titulo, p.libroTitulo, 'Libro') AS articulo, l.portada AS imagen FROM prestamos p LEFT JOIN libros l ON l.id = p.libroId WHERE p.socioId = ? ORDER BY p.fechaPrestamo DESC, p.id DESC",
        'Juegos' => "SELECT p.fechaPrestamo, p.fechaDevolucion, p.estado, COALESCE(j.nombre, p.nombreJuego, 'Juego') AS articulo, j.foto AS imagen FROM prestamosjuegos p LEFT JOIN juegos j ON j.id = p.juegoId WHERE p.socioId = ? ORDER BY p.fechaPrestamo DESC, p.id DESC",
        'Proyectos' => "SELECT p.fechaPrestamo, p.fechaDevolucion, p.estado, COALESCE(pr.nombre, p.nombreProyecto, 'Proyecto') AS articulo, pr.portada AS imagen FROM prestamosproyecto p LEFT JOIN proyectos pr ON pr.id = p.proyectoId WHERE p.socioId = ? ORDER BY p.fechaPrestamo DESC, p.id DESC",
    ];
    foreach ($consultas as $tipo => $sql) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$socioId]);
        $prestamosPorTipo[$tipo] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    require __DIR__ . '/../views/socios/misPrestamos.php';
}

// ===== CRUD DE USUARIOS =====

function listarUsuarios() {
    global $pdo;

    $usuarios = Usuario::listar($pdo);

    require __DIR__ . '/../views/usuarios/verusuario.php';
}

// Registro (público, también sirve para crear cuentas desde dentro)
function formCrearUsuario() {
    require __DIR__ . '/../views/usuarios/form.php';
}

function formEditarUsuario() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);
    $usuario = Usuario::buscarPorId($pdo, $id);

    if (!$usuario) {
        die("El usuario no existe.");
    }

    require __DIR__ . '/../views/usuarios/form.php';
}

function crearUsuario() {
    global $pdo;

    $nombre    = trim($_POST['nombre'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $roles     = trim($_POST['roles'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if ($nombre === '' || $email === '' || $password === '' || $confirmar === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/usuarios/form.php';
        return;
    }

    if ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
        require __DIR__ . '/../views/usuarios/form.php';
        return;
    }

    if (Usuario::emailExiste($pdo, $email)) {
        $error = "Ese correo ya está registrado.";
        require __DIR__ . '/../views/usuarios/form.php';
        return;
    }

    $foto = null;
    try {
        $foto = subirFotoUsuario();
        Usuario::crear($pdo, $nombre, $email, $roles, $password, $foto);
    } catch (Throwable $e) {
        if ($foto !== null) unlink(__DIR__ . '/../uploads/usuarios/' . $foto);
        $error = $e instanceof InvalidArgumentException ? $e->getMessage() : 'No se pudo guardar el usuario. Intenta nuevamente.';
        require __DIR__ . '/../views/usuarios/form.php';
        return;
    }

    // Si ya hay sesión, vuelve al listado; si no, al login
    if (!empty($_SESSION['usuario_id'])) {
        header('Location: index.php?accion=listarUsuarios');
    } else {
        header('Location: index.php?accion=formLogin&registro=exito');
    }
    exit;
}

function editarUsuario() {
    global $pdo;

    $id        = (int) ($_POST['id'] ?? 0);
    $nombre    = trim($_POST['nombre'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $roles     = trim($_POST['roles'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    $error = null;

    if ($nombre === '' || $email === '') {
        $error = "Nombre y correo son obligatorios.";
    } elseif ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } elseif (Usuario::emailExiste($pdo, $email, $id)) {
        $error = "Ese correo ya lo usa otra cuenta.";
    }

    if ($error !== null) {
        $usuario = Usuario::buscarPorId($pdo, $id);
        require __DIR__ . '/../views/usuarios/form.php';
        return;
    }

    $foto = null;
    try {
        $foto = subirFotoUsuario();
        Usuario::actualizar($pdo, $id, $nombre, $email, $password, $roles, $foto);
    } catch (Throwable $e) {
        if ($foto !== null) unlink(__DIR__ . '/../uploads/usuarios/' . $foto);
        $usuario = Usuario::buscarPorId($pdo, $id);
        $error = $e instanceof InvalidArgumentException ? $e->getMessage() : 'No se pudo guardar el usuario. Intenta nuevamente.';
        require __DIR__ . '/../views/usuarios/form.php';
        return;
    }

    if ($id === (int) ($_SESSION['usuario_id'] ?? 0)) {
        $_SESSION['usuario'] = $nombre;
    }

    header('Location: index.php?accion=listarUsuarios');
    exit;
}

function eliminarUsuario() {
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    // No permitir que alguien elimine su propia cuenta en uso
    if ($id === (int) ($_SESSION['usuario_id'] ?? 0)) {
        header('Location: index.php?accion=listarUsuarios');
        exit;
    }

    Usuario::eliminar($pdo, $id);

    header('Location: index.php?accion=listarUsuarios');
    exit;
}

function subirFotoUsuario(): ?string
{
    $archivo = $_FILES['foto'] ?? null;
    if ($archivo === null || ($archivo['error'] ?? null) === UPLOAD_ERR_NO_FILE) return null;
    if (!is_array($archivo) || ($archivo['error'] ?? null) !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('No se pudo subir la foto. Elige un archivo de hasta 2 MB.');
    }
    $temporal = $archivo['tmp_name'] ?? '';
    if (!is_string($temporal) || !is_uploaded_file($temporal) || filesize($temporal) > 2 * 1024 * 1024) {
        throw new InvalidArgumentException('La foto debe pesar como máximo 2 MB.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temporal);
    $extensiones = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($extensiones[$mime]) || !@getimagesize($temporal)) {
        throw new InvalidArgumentException('La foto debe ser una imagen JPG, PNG o WEBP válida.');
    }
    $directorio = __DIR__ . '/../uploads/usuarios';
    if (!is_dir($directorio) && !mkdir($directorio, 0755, true)) throw new RuntimeException('No se pudo crear la carpeta de fotos.');
    $nombre = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
    if (!move_uploaded_file($temporal, $directorio . '/' . $nombre)) throw new RuntimeException('No se pudo guardar la foto.');
    return $nombre;
}
