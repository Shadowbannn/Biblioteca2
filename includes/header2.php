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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/responsive.css">
    <script src="js/navegacion.js" defer></script>
</head>

<body>

<nav class="navegacion-principal" aria-label="Navegación principal">
    <a href="inicioo.php">
        <img src="img/logo.png"
             alt="Inicio" style="width: 270px; height: auto;">
    </a>

             <button type="button" class="menu-movil btn btn-dark" aria-controls="menu-principal" aria-expanded="false" aria-label="Abrir o cerrar menú"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
    <ul id="menu-principal">
                <li><a href="inicioo.php">Inicio</a></li>
                <li><a href="inicioo.php#destacados">Destacadas</a></li>
                <li><a href="inicioo.php#noticias">Noticias</a></li>

                <?php if (!empty($_SESSION['socio_id'])): ?>
                    <?php require __DIR__ . '/perfilSocio.php'; ?>
                    <?php require __DIR__ . '/notificacionesSocio.php'; ?>
            <li class="dropdown menu-mas-dropdown">
                <button id="menuMasDropdown" type="button" class="menu-mas-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Abrir más opciones">
                   <i class="fa-solid fa-bars" style="color: rgb(255, 255, 255);"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark menu-mas-panel" aria-labelledby="menuMasDropdown">
                    <a class="dropdown-item" href="index.php?accion=listarCatalogo"><i class="fa-solid fa-book-open me-1"></i>Catálogo</a>
                    <a class="dropdown-item" href="index.php?accion=listarJuegosP"><i class="fa-solid fa-dice" aria-hidden="true"></i>Juegos</a>
                    <a class="dropdown-item" href="index.php?accion=listarProyectosP"><i class="fa-solid fa-lightbulb" aria-hidden="true"></i>Proyectos</a>
                    <a class="dropdown-item" href="index.php?accion=misPrestamos"><i class="fa-solid fa-book-open me-1"></i>Mis préstamos</a>
                </div>
            </li>

                <?php else: ?>
                    <li><a class="btn btn-sm btn-dark ms-lg-2" href="index.php?accion=formLogin"><i class="bi bi-person-circle me-1"></i>Ingresar</a></li>
                <?php endif; ?>
            </ul>
</nav>

<div class="container" style="
    margin-top: 50px;
    padding-top: 10px;">

