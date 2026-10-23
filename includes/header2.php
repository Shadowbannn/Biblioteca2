<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>

<nav>
    <a href="index.php">
        <img src="img/logo.png"
             alt="Inicio" style="width: 270px; height: auto;">
    </a>

    <ul>

        <li>
        <li><a href="index.php"><b>Inicio</b></a></li>
        <li><a href="index.php?accion=listarCatalogo"><b>Catalogo</b></a></li>
        <li><a href="index.php?accion=listarJuegosP"><b>Juegos</b></a></li>
        <li><a href="index.php?accion=listarProyectosP"><b>Proyectos</b></a></li>



            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <a href="index.php?accion=formLogin" class="btn btn-dark btn-lg">
                        <b><i class="fa-solid fa-circle-user"></i></b>
                        </a>
            </div>
        </li>

    </ul>
</nav>

<div class="container" style="
    margin-top: 50px;
    padding-top: 10px;">

