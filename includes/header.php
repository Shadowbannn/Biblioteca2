<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>    
    <link rel="stylesheet" href="css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
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
        <li><a href="index.php?accion=listarSocios"><b>Socios</b></a></li>
        <li><a href="index.php?accion=listarLibros"><b>Libros</b></a></li>
        <li><a href="index.php?accion=listarJuegos"><b>Juegos</b></a></li>
        <li><a href="index.php?accion=listarProyectos"><b>Proyectos</b></a></li>        

            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group" role="group">
                        
                        <button id="btnGroupDrop1" type="button" 
                                                class="btn btn-dark dropdown-toggle" 
                                                data-bs-toggle="dropdown" aria-haspopup="true" 
                                                aria-expanded="false">
                                                Más
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="index.php?accion=listarUsuarios">Usuarios</a>
                            <a class="dropdown-item" href="index.php?accion=logout">Cerrar Sesión</a>



                        </div>
                    </div>
            </div>
        </li>

        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <li class="dropdown">
                <button type="button"
                        class="btn btn-dark"
                        id="notificacionesDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        aria-label="Ver notificaciones">
                    <i class="fa-solid fa-bell" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end"
                     aria-labelledby="notificacionesDropdown"
                     style="min-width: 260px;">
                    <h6 class="dropdown-header">Notificaciones</h6>
                    <p class="px-3 py-2 mb-0 text-muted small">
                        Las notificaciones todav&iacute;a no est&aacute;n habilitadas.
                    </p>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container" style="
    margin-top: 50px;
    padding-top: 10px;">
