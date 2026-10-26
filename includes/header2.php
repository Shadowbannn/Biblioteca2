<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>    
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
</head>

<body>

<nav>
    <a href="inicioo.php">
        <img src="img/logo.png"
             alt="Inicio" style="width: 270px; height: auto;">
    </a>

             <ul>
                <li><a href="inicioo.php">Inicio</a></li>
                <li><a href="inicioo.php#destacados">Destacadas</a></li>
                <li><a href="inicioo.php#noticias">Noticias</a></li>

                <li class="dropdown">
                    <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="menuExplorar" data-bs-toggle="dropdown" aria-expanded="false">
                        Explorar
                    </button>
                    <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="menuExplorar">
                        <a class="dropdown-item" href="index.php?accion=listarCatalogo">Catálogo</a>
                        <a class="dropdown-item" href="index.php?accion=listarJuegosP">Juegos</a>
                        <a class="dropdown-item" href="index.php?accion=listarProyectosP">Proyectos</a>
                    </div>
                </li>
                <li>
                    <a class="btn btn-sm btn-dark ms-lg-2" href="index.php?accion=formLogin">
                        <i class="bi bi-person-circle me-1"></i>Ingresar
                    </a>
                </li>
            </ul>
</nav>

<div class="container" style="
    margin-top: 50px;
    padding-top: 10px;">

