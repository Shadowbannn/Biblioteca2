<?php require __DIR__ . '/../includes/header.php'; ?>


<h1 class="titulo"><b>Bienvenido <?= htmlspecialchars($_SESSION["usuario"] ?? '') ?></b></h1>

<div class="row mt-5">

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarLibros">
                <img class="card-img-top" src="https://static.vecteezy.com/system/resources/previews/010/161/526/non_2x/books-composition-3d-render-free-png.png" alt="Libros">
            </a>
            <div class="card-body">
                <a href="index.php?accion=listarLibros" class="btn btn-primary">Ver Libritos</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarSocios">
                <img class="card-img-top" src="https://imagenes.excelsior.com.mx/files/og_thumbnail/uploads/2025/03/11/691ffb95e51ea.jpeg" alt="Socios">
            </a>
            <div class="card-body">
                <a href="index.php?accion=listarSocios" class="btn btn-primary">Ver Usuarios</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarPrestamos">
                <img class="card-img-top" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLfB2R3avYAP-mu4DI27Gz26QW7mcJDZTMn3TafilEoWUBRZ4kJoS_Bywt&s=10" alt="Prestamos">
            </a>
            <div class="card-body">
                <a href="index.php?accion=listarPrestamos" class="btn btn-primary">Ver Prestamos</a>
            </div>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
