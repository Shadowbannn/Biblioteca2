<?php require __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-center mt-3">
    <a href="#" class="btn btn-secondary btn-lg disabled" role="button" aria-disabled="true">
        <b>Bienvenido <?= htmlspecialchars($_SESSION["usuario"] ?? '') ?></b>
    </a>
</div>

<div class="card" style=" margin-top: 12px;">
<div class="row mt-5">

    <!--------------->
    <! Ver libros>
    <!--------------->

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarLibros">
                <img class="card-img-top" src="/carpeta2/img/1.png" alt="Libros">
            </a>
        </div>
    </div>

    <!--------------->
    <! Ver Socios>
    <!--------------->

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarSocios">
                <img class="card-img-top" src="/carpeta2/img/2.png" alt="Socios">
            </a>
        </div>
    </div>

    <!--------------->
    <! Ver Usuarios>
    <!--------------->

    <div class="col-md-4 mb-4">
        <div class="card">
            <a href="index.php?accion=listarUsuarios">
                <img class="card-img-top" src="/carpeta2/img/3.png" alt="Usuarios">
            </a>
        </div>
    </div>    

    <!--------------->
    <! Ver Prestamos >
    <!--------------->

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarPrestamos">
                <img class="card-img-top" src="/carpeta2/img/4.png" alt="Prestamos">
            </a>
        </div>
    </div>

    <!--------------->
    <!-Ver Proyectos->
    <!--------------->

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarProyectos">
                <img class="card-img-top" src="/carpeta2/img/5.png" alt="Prestamos">
            </a>
        </div>
    </div>

<!--------------->    
<!  Ver Juegos   >
<!--------------->

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarJuegos">
                <img class="card-img-top" src="/carpeta2/img/6.png" alt="Juegos">
            </a>
        </div>
    </div>
</div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
