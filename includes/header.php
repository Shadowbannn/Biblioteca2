<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>    
    <link rel="stylesheet" href="css/style.css">
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
        <li><a href="index.php?accion=listarLibros"><b>Libros</b></a></li>
        <li><a href="index.php?accion=listarSocios"><b>Socios</b></a></li>
        <li><a href="index.php?accion=listarPrestamos"><b>Prestamos</b></a></li>

            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group" role="group">
                        
                        <button id="btnGroupDrop1" type="button" 
                                                class="btn btn-dark dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-haspopup="true" 
                                                aria-expanded="false">
                                                Menú
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="index.php?accion=listarJuegos">Juegos</a>
                            <a class="dropdown-item" href="index.php?accion=listarUsuarios">Usuarios</a>
                            <a class="dropdown-item" href="index.php?accion=listarProyectosP">Proyectos</a>
                            <a class="dropdown-item" href="index.php?accion=logout">Cerrar Sesión</a>



                        </div>
                    </div>
            </div>
        </li>

    </ul>
</nav>

<div class="container" style="
    margin-top: 50px;
    padding-top: 10px;">
