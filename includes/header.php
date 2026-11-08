<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Biblioteca</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>    
    <link rel="stylesheet" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <link rel="stylesheet" href="css/responsive.css">
    <script src="js/navegacion.js" defer></script>
</head>

<body>


<nav class="navegacion-principal" aria-label="Navegación principal">
    <a href="index.php">
        <img src="img/logo.png"
             alt="Inicio" style="width: 270px; height: auto;">
    </a>



        <button type="button" class="menu-movil btn btn-dark" aria-controls="menu-principal" aria-expanded="false" aria-label="Abrir o cerrar menú"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
        <ul id="menu-principal">

        <li><a href="index.php"><b>Inicio</b></a></li>



        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <?php require __DIR__ . '/notificaciones.php'; ?>
            <?php require __DIR__ . '/perfilUsuario.php'; ?>

        <li class="dropdown menu-mas-dropdown">
            <button id="menuMasDropdown" type="button" class="menu-mas-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Abrir más opciones">
                <i class="fa-solid fa-bars" aria-hidden="true"></i><span>Más</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark menu-mas-panel" aria-labelledby="menuMasDropdown">
                <a class="dropdown-item" href="index.php?accion=listarSocios"><i class="fa-solid fa-users" aria-hidden="true"></i>Socios</a>
                <a class="dropdown-item" href="index.php?accion=listarLibros"><i class="fa-solid fa-book" aria-hidden="true"></i>Libros</a>
                <a class="dropdown-item" href="index.php?accion=listarJuegos"><i class="fa-solid fa-dice" aria-hidden="true"></i>Juegos</a>
                <a class="dropdown-item" href="index.php?accion=listarUsuarios"><i class="fa-solid fa-user-gear" aria-hidden="true"></i>Usuarios</a>
                <a class="dropdown-item" href="index.php?accion=listarSolicitudes"><i class="fa-solid fa-inbox" aria-hidden="true"></i>Solicitudes</a>
                <a class="dropdown-item" href="index.php?accion=listarProyectos"><i class="fa-solid fa-lightbulb" aria-hidden="true"></i>Proyectos</a>
            </div>
        </li>            
        <?php endif; ?>
    </ul>
</nav>

<div class="container" style="
    margin-top: 50px;
    padding-top: 10px;">

