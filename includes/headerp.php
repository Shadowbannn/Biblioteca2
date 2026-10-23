<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>    
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
</head>

<body>


<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php"><i class="bi bi-book-half me-2"></i>BiblioTech</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon" ></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link active" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#noticias">Noticias</a></li>
                <li class="nav-item"><a class="nav-link" href="#destacados">Destacados</a></li>
                <li class="nav-item"><a class="nav-link" href="#recomendaciones">Recomendaciones</a></li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-outline-light ms-lg-2" href="<?= htmlspecialchars($inicioBaseUrl, ENT_QUOTES, 'UTF-8') ?>index.php?accion=login">
                        <i class="bi bi-person-circle me-1"></i>Ingresar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>