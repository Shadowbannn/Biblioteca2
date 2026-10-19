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
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }

    session_destroy();

    header('Location: index.php?accion=formLogin');
    exit;
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

    Usuario::crear($pdo, $nombre, $email, $roles, $password);

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

    Usuario::actualizar($pdo, $id, $nombre, $email, $password, $roles);

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
