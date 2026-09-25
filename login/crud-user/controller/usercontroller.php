<?php

require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../models/user.php';

// Ruter :)
if (isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'crear':
            crearUsuario();
            break;
        case 'editar':
            editarUsuario();
            break;
        case 'eliminar':
            eliminarUsuario();
            break;
    }
}

function formCrearUsuario()
{
    require __DIR__ . '/../views/registrarse.php';
}



function crearUsuario()
{
    global $pdo;

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if ($nombre === '' || $email === '' || $password === '' || $confirmar === '') {
        $error = "Todos los campos son obligatorios.";
        require __DIR__ . '/../views/registrarse.php';
        return;
    }

    if ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
        require __DIR__ . '/../views/registrarse.php';
        return;
    }

    if (Usuario::emailExiste($pdo, $email)) {
        $error = "Ese correo ya está registrado.";
        require __DIR__ . '/../views/registrarse.php';
        return;
    }

    Usuario::crear($pdo, $nombre, $email, $password);

    // Redirección corregida hacia el login tras un registro exitoso
    header('Location: ../views/login.php?registro=exito');
    exit;
}

function editarUsuario()
{
    global $pdo;

    $id = (int) ($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if ($nombre === '' || $email === '') {
        $usuario = Usuario::buscarPorId($pdo, $id);
        $error = "Nombre y correo son obligatorios.";
        require __DIR__ . '/../views/registrarse.php';
        return;
    }

    if ($password !== $confirmar) {
        $usuario = Usuario::buscarPorId($pdo, $id);
        $error = "Las contraseñas no coinciden.";
        require __DIR__ . '/../views/registrarse.php';
        return;
    }

    if (Usuario::emailExiste($pdo, $email, $id)) {
        $usuario = Usuario::buscarPorId($pdo, $id);
        $error = "Ese correo ya lo usa otra cuenta.";
        require __DIR__ . '/../views/registrarse.php';
        return;
    }

    Usuario::actualizar($pdo, $id, $nombre, $email, $password);

    header('Location: index.php?accion=listarUsuarios');
    exit;
}

function eliminarUsuario()
{
    global $pdo;

    $id = (int) ($_GET['id'] ?? 0);

    Usuario::eliminar($pdo, $id);

    header('Location: index.php?accion=listarUsuarios');
    exit;
}